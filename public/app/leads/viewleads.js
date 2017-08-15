$(document).ready(
    function(){
        //$('#todoDate').datepicker();
        //$('#todoDateLeadSummary').datepicker();
        //$('.scroller').slimScroll({});
    }
);
var leadsVue = new Vue({
    el: '#leads_div',
    data:{
        lead:   { leadid: 0 , assigned_to : 0 },
        lead_count:0,
        page:   1,
        page_count:[],
        offset: 1,
        limit:  20,

        notes:  [],
        leads:  [],
        lead_status:    [],
        lead_types:     [],
        lead_sources:   [],
        lead_campaigns: [],

        statusid: 0,
        typeid: 0 ,
        sourceid:0,
        q: '',

        todos:  [],
        campaigns:[],
        postcard_leads:[],
        addEditNote:'Add',
        lead_groups:[],
        selected_leads:[],
        my_staff: [],
        group:{},
        group_members:[],
        contact:{facebook:false,twitter:false,pinterest:false,linkedin:false,googleplus:false,youtube:false},
        states:[],
        cities:[],
        countries:[],

        loading_members : false,
        loading:false // loading leads
    },
    mounted:function(){
        $('#cb-toggle').prop('checked', false);
        $('.btn').prop( 'disabled' , false );
        $('#page').val( 1 );
        this.getLeads();
        this.init();
    },
    methods:{
        getLeads:function(){
            let vm = this;
            this.loading = true;
            this.leads = [];
            $.get('/ajax/leads/getleads', $('#leadsForm').serialize() )
            .done(function( data ){
                if( data.success ){
                    vm.leads = data.leads;
                    vm.lead_count = data.total;
                    vm.offset   = data.offset;
                    vm.limit    = data.limit;

                    l = vm.page_count.length - 1;

                    if( vm.page == vm.page_count[0] || vm.page == vm.page_count[ l ] || vm.page == 1 ){
                        vm.page_count = data.page_count;
                    }


                }else{
                    toastr.error( data.message );
                }

                vm.loading = false;

            });
        },
        savelead: function( e ){
            let vm = this;
            $('.btn').prop( 'disabled' , true );
            $(e.target).html( '<i class="fa fa-spin fa-refresh"></i>');
            vm.lead_campaigns = [];
            $.post( "/ajax/leads/savelead",  $('#leadForm').serialize() )
                .done(function( data ){
                    if( data.success){
                        if(data.success){
                            toastr.success( 'Lead successfully saved' );
                            //vm.lead = data.lead;
                            var is_new = true;
                            for( i=0; i < vm.leads.length; i++ ){
                                d = vm.leads[i];
                                if( d.leadid == data.lead.leadid ){
                                    Vue.set( vm.leads , i , data.lead );
                                    is_new = false; break;
                                }
                            }
                            if( is_new ){
                                vm.leads.push( data.lead );
                            }
                            if( data.campaigns.length ){
                                vm.lead = data.lead;
                                vm.lead_campaigns = data.campaigns;
                                $('#leadCampaignModal').modal();
                            }else{
                                vm.openList();
                            }
                        }else{
                            toastr.error( data.message );
                        }
                        $('.btn').prop( 'disabled' , false );
                        $(e.target).html( 'Save');
                    }else{
                        toastr.error( data.message );
                        $('.btn').prop( 'disabled' , false );
                        $(e.target).html( 'Save');
                    }
                }).error(function( data ){
                    toastr.error('Something went wrong');
                    $('.btn').prop( 'disabled' , false );
                    $(e.target).html( 'Save');
                });

        },
        editlead( lid ){
            this.lead = {};
            var arr = $.grep( this.leads , function( lead ){
                return lead.leadid == lid;
            });
            if (typeof arr[0] !== 'undefined') {
                this.lead = arr[0];
            }

            this.openPanel( 'editLeadDiv' );
        },
        deletelead: function( lid ){
            let vm = this;
            if( confirm( delete_lead_confirm )){

                $.post( "/ajax/leads/deletelead", { _token: $("input[name='_token']").val(), lid:lid })
                .done( function( data ){
                    if( data.success ){
                        if( data.success ){
                            for( i=0; i < vm.leads.length; i++ ){
                                d = vm.leads[i];
                                if( d.leadid == lid ){
                                    vm.leads.splice( i, 1 );
                                    toastr.success( 'Lead successfully deleted' );
                                }
                            }
                        }else{
                            toastr.error( data.message );
                        }
                    }
                }).error(
                    function( data ){
                        toastr.error('Something went wrong');
                    }
                );
            }

        },
        /*** campaigns   ****/
        openCampaignListModal( leadid ){
            let vm = this;
            vm.lead_campaigns = [];

            this.lead = $.grep( this.leads , function( l ){
                return l.leadid == leadid;
            })[0];

            $.get("/ajax/lead/campaigns" , {leadid:leadid} )
            .done(function( data ){
                if( data.success){
                    vm.lead_campaigns = data.campaigns;
                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
            });
            $('#leadCampaignModal').modal();
        },
        addLeadToCampaign(e){
            let vm = this;
            let h = $(e.target).html();
            $('.btn').prop( 'disabled' , true );
            $(e.target).html( '<i class="fa fa-spin fa-refresh"></i>');

            $.post('/ajax/lead/addtocampaigns' , $('#leadCampaignForm').serialize() )
            .done(function( data ){
                if( data.success){
                    toastr.success( 'Lead successfully added to campaign' );
                    $('#leadCampaignModal').modal( 'toggle' );
                    $('.btn').prop( 'disabled' , false );
                    $(e.target).html( h );
                    vm.openList();
                }else{
                    toastr.error( data.message );
                    $('.btn').prop( 'disabled' , false );
                    $(e.target).html( h );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
                $('.btn').prop( 'disabled' , false );
                $(e.target).html( h );
            });
        },
        goToPage( page ){
            this.page = page;
            $('#page').val( this.page );
            this.getLeads();
        },
        prev(){
            this.page = this.page - 1;
            $('#page').val( this.page );
            this.getLeads();
        },
        next(){
            this.page = this.page + 1;
            $('#page').val( this.page );
            this.getLeads();
        },
        showMoreLeads:function(){
            var p = $('#page');
            var next_page = parseInt(p.val()) + 1;

            $('.btn').prop( 'disabled', true );
            p.val( next_page );
            $('#show-more').html( '<i class="fa fa-spin fa-refresh"></i>' );

            this.getLeads();
        },
        stateSelected:function( stateid ) {
            var sid = stateid ? stateid :$('#stateid').val();
            $.get( '/ajax/loc/ss', { sid:sid })
                .done( function( data ){
                    leadsVue.$data.cities = [];
                    if( data.success ){
                        for (i = 0; i < data.cities.length; i++){
                            d = data.cities[ i ];
                            leadsVue.$data.cities.push( d );
                        }
                    }
                })
                .error(function (data) {

                });
        },
        countrySelected:function(){
          $.get( '/ajax/loc/cs' , {cid:$('#countryid').val()} )
          .done( function( data ){
             leadsVue.$data.states = [];
             if( data.success ){
                for( i=0; i < data.states.length; i++ ){
                    d = data.states[i];
                    leadsVue.$data.states.push( d )
                }
             }
          })
          .error( function( data ){

          });
        },
        saveGroup:function(){
            $('.btn').prop( 'disabled', true );
            $.post( '/ajax/leads/save/group' ,  $('#groupForm').serialize() )
            .done( function( data ){
                $('.btn').prop( 'disabled', false );
                    if( data.success ){
                        leadsVue.$data.lead_groups.push( data.group );
                        $('#group_name').val('');
                        toastr.success( 'Group successfully saved' );
                        $('#editLeadGroupModal').modal('toggle');
                    }
            })
            .error(function( data ){
                $('.btn').prop( 'disabled', false );
                toastr.error( data.message );
            })
        },
        deleteGroup( e ){
            if( ! confirm( 'Are you sure you want to delete this group ? ') ){
                return;
            }
            let vm = this;
            let btn = $( e.target );
            let gid = btn.data('gid');
            $.post( "/ajax/leads/delete/group" , { gid: gid , _token : $('input[name=_token]').val() } )
            .done(function( data ){
                if( data.success){
                    for( i=0; i < vm.lead_groups.length; i++ ){
                        d   =   vm.lead_groups[i];
                        if( d.lead_group_id == gid ){
                            vm.lead_groups.splice( i , 1 );
                        }
                    }
                    toastr.success( 'Group successfully removed');
                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
            });
        },
        showGroupLeads( gid ){
            let vm = this;
            this.openLeadGroupPanel( 'members_div' );
            vm.group = $.grep( vm.lead_groups, function( g ){
                return g.lead_group_id == gid;
            })[0];

            vm.loading_members = true;
            vm.group_members = [];

            $.get('/ajax/leads/ggm' , {gid:gid})
            .done(function( data ){
                if( data.success){
                    vm.group_members = data.members;
                }else{
                    toastr.error( data.message );
                }
                vm.loading_members = false;
            })
            .error(function( data ){
                toastr.error('Something went wrong');
                vm.loading_members = false;
            });
        },
        addOneGroupToCampaign( index , groups ){

            let nxt = index + 1;
            let group_id = groups[index];
            let vm = this;

            if( group_id == undefined ){
                toastr.error( 'Group with index '+index+' does not exists' );
                return
            }

            let group_name = $('#grp'+group_id).data('name');
            $.blockUI( { message: '<h5><b>Group '+group_name+' currently being added to campaigns. <br /> Please wait ... <i class="fa fa-refresh fa-spin"></i></b></h5>' } );

            $.post( '/ajax/lead/groups/campaigns' , $('#leadGroupsForm').serialize()+'&group_id='+group_id )
            .done(function( data ){
                if( data.success){
                    // check if group_id is last
                    for( i=0; i < groups.length; i++ ){
                        d = groups[ i ];
                        if( groups[ nxt ] == undefined ){
                            toastr.success( 'Groups successfully added to campaigns' );
                            $.unblockUI();
                            return; // end the script
                        }
                    }
                    setTimeout(function(){
                        vm.addOneGroupToCampaign( nxt , groups );
                        $.blockUI( { message: '<h5><b>Group '+group_name+' currently being added to campaigns. <br /> Please wait ... <i class="fa fa-refresh fa-spin"></i></b></h5>' } );
                    }, 2000 );

                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
                $.unblockUI();
            });
        },
        addGroupsToCampaign(){

            let vm  = this;
            let group_name , d;
            let grp = ( $( 'input[name="grp\\[\\]"]:checked' ).map(
                function( ){ return this.value; }
            ).get() );

            let cmp = ( $( 'input[name="cbcampaigns\\[\\]"]:checked' ).map(
                function( ){ return this.value; }
            ).get() );

            if( ! grp.length ){
                toastr.error( 'Please select a group' );
                return;
            }

            if( ! cmp.length ){
                toastr.error( 'Please select a campaign' );
                return;
            }
            $('#leadGroupsModal').modal( 'toggle' );
            this.addOneGroupToCampaign( 0 , grp );
        },
        addToCampaign(){
            this.openLeadGroupPanel( 'lead_campaign_div' );
            let vm = this;
            $.get('/ajax/account/campaigns' )
                .done(function( data ){
                    if( data.success){
                        vm.campaigns = data.campaigns;
                    }else{
                        toastr.error( data.message );
                    }
                })
                .error(function( data ){
                    toastr.error('Something went wrong');
                });
        },
        populateGroupList:function(){
            let vm = this;
            if( ! this.lead_groups.length ){
                $.get('/ajax/leads/getgroups')
                .done( function( data ){
                    if( data.success ){
                        for( i=0; i < data.groups.length; i++ ){
                            d = data.groups[i];
                            vm.lead_groups.push( d );
                        }
                    }
                })
            }
        },
        openGroupList(){
            $('#leadGroupsModal').modal();
            if( ! this.lead_groups.length ){
                this.populateGroupList();
            }
        },
        addToGroup:function(){
            let vm = this;
            this.group = {};
            $('.cb:checkbox:checked').each( function(){
                vm.selected_leads.push( $(this).val() )
            });
            if( ! vm.selected_leads.length ){
                toastr.error( 'You need to select a lead' );
                return;
            }
            //$('#leadGroupsModal').modal();
            $('#addLeadsGroupModal').modal();
            this.populateGroupList();
        },
        saveLeadsToGroups(){
            let vm = this;
            let btn = $( '#altg-btn' );
            let h  = btn.html();
            let selected_groups = [];
            let dg = $( '#dropdown_group_id');
            if( dg.val() == 0  ){
                toastr.error( 'Please select a group to add the leads' );
                return;
            }
            let group_id = dg.val();
            /**
            $('.grp:checkbox:checked').each( function(){
                selected_groups.push( $(this).val() )
            });
            **/

            let algc = $('#algc').val();
            btn.prop( 'disabled' , true );
            btn.html( '<i class="fa fa-spin fa-refresh"></i>' );

            $.post( '/ajax/leads/sltg' , { l:this.selected_leads, group_id: group_id, algc: algc, _token:$( "input[name=_token]").val() } )
            .done( function( data ){
                if( data.success ){
                    gtext = data.group_count.length > 1 ? 'groups' : 'group';
                    toastr.success( data.success_count+' leads successfully added to  group' );
                }else{
                    toastr.error( data.message );
                }
                $('.btn').prop( 'disabled', false );
                btn.html( h );
                $('#addLeadsGroupModal').modal( 'toggle' );
            }).error(function(){
                toastr.error( 'Something went wrong');
                $('.btn').prop( 'disabled', false );
                btn.html( h );
            });
        },
        createLeadGroup:function(){
            this.group = {};
            $( '#editLeadGroupModal' ).modal();
            $( '#leadGroupsModal' ).modal( 'hide' );
        },
        saveLeadsToGroup:function(){
            var gid = $('#select_lead_group_id').val();
            $('.btn').prop( 'disabled', true );
            if( ! gid ){
                toastr.error( 'No lead group selected');
                $('.btn').prop( 'disabled', false );
                return
            }
        },
        sendPostcard:function(){
            $('.btn').prop( 'disabled', true );
            if( ! leadsVue.$data.postcard_leads.length ){
                toastr.error( postcard_cannot_be_sent );
                return;
            }
            $('#send_to_leads').val( leadsVue.$data.postcard_leads.join('|') );
            $.post( '/ajax/leads/sendpostcard' ,  $('#postcardForm').serialize() )
            .done( function( data ){
                if( data.success ){
                    toastr.success( data.message );
                }
                $('.btn').prop( 'disabled', false );
            })
            .error(function(){
                $('.btn').prop( 'disabled', false );
                toastr.error( 'Something went wrong' );
            });
        },
        selectPostcard:function(){
            var cb_checked = $('.checkbox-master:checked');
            if( ! cb_checked.length ){
                toastr.error( need_to_select_leads );
                return
            }
            cb_checked.each(function() {
                if( $.inArray( $(this).val() , leadsVue.$data.postcard_leads ) == -1 ){
                    leadsVue.$data.postcard_leads.push( $(this).val() );
                }

            });
            $('#sendPostcardModal').modal();
        },
        editleadDeprecated: function( idx , lid ){
            this.lead = {};
            if( idx != -1 ){
                // search the lead
                for(var i=0; i<leadsVue.$data.leads.length; i++) {
                    if( leadsVue.$data.leads[i].leadid == lid ){
                        this.lead = leadsVue.$data.leads[i]
                    }
                }

            }

            // force use of jquery since vue wont refresh some values
            // if edited
            $('#first_name').val( this.lead.first_name );
            $('#last_name').val( this.lead.last_name );
            $('#email').val( this.lead.email );
            $('#company').val( this.lead.company );
            $('#phone_home').val( this.lead.phone_home );
            $('#phone_work').val( this.lead.phone_work );
            $('#phone_mobile').val( this.lead.phone_mobile );

            $('#statusid').val( this.lead.statusid );
            $('#sourceid').val( this.lead.sourceid );
            $('#typeid').val( this.lead.typeid );

            $('#lead-index').val( idx );
            $('#leadid').val( lid );
            $('#assigned_to').val( this.lead.assigned_to );

            $('#editLeadModal').modal();
            $('#leadSummaryModal').modal('toggle');

            if( this.lead.countryid ){
                this.countrySelected();
            }

            if( this.lead.stateid ){
                this.stateSelected( this.lead.stateid );
            }

            $('#countryid').val( this.lead.countryid );
            $('#stateid').val( this.lead.stateid );
            $('#cityid').val( this.lead.cityid );
            $('#primary_address_street').val( this.lead.primary_address_street );
            $('#primary_address_postalcode').val( this.lead.primary_address_postalcode);

            if( this.lead.primary_address_state && ! this.lead.stateid   ){
                // try to find the city
                $.get('/ajax/leads/fc', { lid:this.lead.leadid })
                    .done(function( data ){
                        if( data.success ){
                            leadsVue.$data.lead = data.lead;

                            if(  data.lead.countryid ){
                                $('#countryid').val( data.lead.countryid );
                                leadsVue.countrySelected();
                            }
                            if( data.lead.stateid ){
                                $('#stateid').val( data.lead.stateid );
                                leadsVue.stateSelected( data.lead.stateid );
                            }
                            if( data.lead.cityid ){
                                $('#cityid').val( data.lead.cityid );
                            }
                        }
                    });
            }


            //console.log( leadsVue.$data.lead )
        },
        editleadsummary:function( lid ){
            this.editlead( $('#lead-index').val() , lid)
        },
        edittodo:function( idx , lid){
            $( '#addTodoModal' ).modal();
        },
        editnote: function( idx , lid ){

            if( idx == -1 ){
                leadsVue.$data.addEditNote ='Add';

                $('#note-index').val( -1 );
                $('#note-text').val( '' );
                $('#noteid').val( 0 );
                $('#note_leadid').val( lid );

            }else{
                leadsVue.$data.addEditNote ='Edit';
                note = leadsVue.$data.notes[idx];
                $('#note-text').val( $('#note'+idx).html() );
                $('#note-index').val(idx);
                $('#noteid').val( note.noteid );
                $('#note_leadid').val( lid )

            }

            //$('.modal').modal('hide');
            $( '#editNoteModal' ).modal();
            $( '.loader').hide();

        },
        deletenote: function( idx, noteid ){
            if( confirm( delete_note_confirm )){
                $.post( "/ajax/leads/deletenote", { tkn: tkn, nid:noteid } )
                    .done( function( data ){
                        if( data.success ){

                            note = leadsVue.$data.notes[ idx ];
                            leadsVue.$data.notes.$remove( note );

                        }
                    });
            }
        },
        savenote: function(){
            $('.btn').prop( 'disabled' , true );

            $('.loader').show();
            $.post( "/ajax/leads/savenotes", { tkn: tkn , lid: $('#note_leadid').val() , noteid:$('#noteid').val(), n : $('#note-text').val() })
                .done( function( data ){
                    $('.btn').prop( 'disabled' , false );
                    if( data.success){
                        if( $('#note-index').val()  == -1 ){
                            leadsVue.$data.notes.push( data.note );
                        }else{
                            leadsVue.$data.notes.$set( $('#note-index').val() ,  data.note );
                        }
                    }
                    $( '#editNoteModal' ).modal( 'toggle' );
                    $('.loader').hide();
                });

        },
        leadsummary: function( lid ){
            let vm = this;
            let i;
            //vm.lead = { leadid: -1 };

            this.openPanel( 'leadSummary');

            //$( '#lead-index').val( idx );
            $( '#leadid').val( lid );
            
            for( i=0; i < this.leads.length; i++ ){
                d = this.leads[i];
                if( d.leadid == lid ){
                    vm.lead = d
                }
            }
            vm.notes = [];

            $.get('/ajax/leads/summary' , {leadid:lid} )
            .done(function( data ){
                if( data.success){
                    vm.notes = data.notes;
                    vm.todos = data.todos;
                }else{
                    toastr.error( data.message );
                }
            }).error(function( data ){
                toastr.error('Something went wrong');
            });

        },
        search: function(){
            var v = $('#search-btn').html();

            $('#search-btn').html( '<i class="fa fa-spin fa-refresh"></i>' );
            $('.btn').prop( 'disabled', true );

            this.page = 1;
            $('#page').val( 1 )
            this.getLeads();

            $('.btn').prop( 'disabled', false );
            $('#search-btn').html( v );
            /**
            $.getJSON( "/ajax/leads/search", { tkn: tkn , q:$('#q').val() })
                .done( function( r ){
                    leadsVue.$data.leads = [];
                    leadsVue.$data.lead_count = r.count;
                    for( i = 0 ; i< r.leads.length ;i++){
                        leadsVue.$data.leads.push( r.leads[i] );
                    }
                    $('.btn').prop( 'disabled', false );
                    $('#search-btn').html( v );
                })
                .error( function( data ){
                    $('.btn').prop( 'disabled', false );
                    $('#search-btn').html( v );
                });
            **/
        },
        addcampaigns:function( leadid ){
            $('.btn').prop( 'disabled' , true );
            $.post( "/ajax/leads/addcampaigns", $('#campaignForm').serialize())
                .done( function( data ) {
                    $('.btn').prop( 'disabled' , false );
                    if (data.success) {
                        toastr.success( data.message );
                    }else{
                        toastr.error( data.message );
                    }
                });

        },
        addNotesAfterSave:function( leadid ){
            $('.loader').show();
            $('.btn').prop( 'disabled' , true );
            $.post( "/ajax/leads/savenotes", { tkn: tkn , lid: leadid, noteid:0, n:$('#notesTextAfterSave').val() })
                .done( function( data ){
                    $('.btn').prop( 'disabled' , false );
                    if( data.success ){
                        $('#notesTextAfterSave').val('');
                        toastr.success( note_save_success );
                    }else{
                        toastr.error( data.message );
                    }
                    $('.loader').hide();
                });
        },
        addTodo:function( leadid ){
            $('.btn').prop( 'disabled' , true );
            $.post( "/ajax/leads/savetodo", { tkn: tkn , lid: leadid, tdate:$('#todoDateLeadSummary').val(), todo:$('#todoTextLeadSummary').val() })
                .done( function( data ){
                    $('.btn').prop( 'disabled' , false );
                    if( data.success ){
                        leadsVue.$data.todos = [];
                        for( i = 0 ; i< data.todos.length ;i++ ){
                            leadsVue.$data.todos.push( data.todos[i] );
                        }
                        $('.todoText').val('');
                        toastr.success( data.message );
                    }else{
                        toastr.error( data.message );
                    }
                    $('.loader').hide();
                });
        },
        addTodoAfterSave:function( leadid ){
            $('.btn').prop( 'disabled' , true );
            $.post( "/ajax/leads/savetodo", { tkn: tkn , lid: leadid, tdate:$('#todoDate').val(), todo:$('#todoTextAfterSave').val() })
                .done( function( data ){
                    $('.btn').prop( 'disabled' , false );
                    if( data.success ){
                        $('#todoTextAfterSave').val('');
                        toastr.success( data.message );
                    }else{
                        toastr.alert( data.message );
                    }
                    $('.loader').hide();
                });
        },
        advSearch:function(){
            $('#advanceSearchModal').modal()
        },
        advProcessSearch:function(){
            $('.btn').prop( 'disabled' , true );
            $('#adv_search_button').html( '<i class="fa fa-spin fa-refresh"></i>' );
            this.getLeads();

            $('#advanceSearchModal').modal( 'toggle' );
            $('#adv_search_button').html( 'Search' );
            $('.btn').prop( 'disabled' , false );
            /**
            $.post('/ajax/leads/advs' , $('#leadsForm').serialize() )
            .done( function( data ){
                $('.btn').prop( 'disabled' , false );

                if( data.success ){
                    leadsVue.$data.leads = [];
                    var l = leadsVue.$data.leads;
                    leadsVue.$data.lead_count = data.count;
                    for( i = 0 ; i < data.leads.length; i++){
                        l.push( data.leads[i] )
                    }
                }
                $('#advanceSearchModal').modal( 'toggle' );
                $('#adv_search_button').html( 'Search' );
            })
            .error( function( data ){
                $('.btn').prop( 'disabled' , false );
                toastr.error( 'Something went wrong');
                $('#adv_search_button').html( 'Search' );
            });
            **/
        },
        closeSummary(){
            this.openPanel( 'leadList');
        },
        openList(){
            this.openPanel('leadList');
        },
        openPanel( panel ){
            $( '.vDiv').addClass( 'hide' );
            $('#'+panel).removeClass( 'hide' );
        },
        openLeadGroupPanel( panel ){
            $( '.lead_group_panel').addClass( 'hide' );
            $('#'+panel).removeClass( 'hide' );
        },

        init(){
            let vm = this;
            $.get( '/ajax/leads/init' )
            .done(function( data ){
                if( data.success){
                    vm.lead_status  = data.lead_status;
                    vm.lead_types   = data.lead_types;
                    vm.lead_sources = data.lead_sources;
                    vm.countries    = data.countries;
                    vm.my_staff     = data.staff;
                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
            });
        }
    },
    computed:{
        postcard_lead_count:function(){
            return this.postcard_leads.length
        },
        selected_count:function(){
            return this.selected_leads.length
        },
        displayed_lead_count:function(){
            return ( parseInt( this.offset ) + 1 ) +' to '+ ( parseInt( this.offset ) + this.leads.length )
        },
        sortedGroups(){

        },
        sortedLeads(){
           return  _.orderBy( this.leads, [ 'date_entered' ], [ 'desc' ] );
        },
        totalPages(){
            return parseInt( this.lead_count / this.limit ) + 1;
        }

    }
});

function editLead(){
    $('.modal').modal('hide');
    $('#editLeadModal').modal();
}

$('.add-new').click(
    function(){
        $('#leadid').val( 0 );
        $('#editLeadModal').modal();
    }
);

$('#cb-toggle').click(function(){
    if( $(this).is(':checked')){
        $('.checkbox-master').prop('checked' , true )
    }else{
        $('.checkbox-master').prop('checked' , false )
    }
});