var mVue = new Vue({
    el:'#mDiv',
    data:{
        campaign:{ actions: [] , trigger:{} },
        campaigns:[],
        action:{ action_typeid : 0 , sending_delay : 0 , sending_month : '00' , sending_day: '00', sending_year: '0000' },
        actions:[],
        action_message: '',
        action_typeid : '1',
        action_sending_delay:0,
        postcards:[],
        postcard:{},
        postcard_id:0,
        lead_status: [],
        lead_types:[],
        loading_campaigns: false,
        loading_postcards: false
    },
    methods:{
        init(){
            let vm = this;
            vm.loading_campaigns = true;

            $.get('/ajax/campaign/init')
            .done(function( data ){
                if( data.success){
                    vm.campaigns= data.campaigns;
                    vm.lead_status = data.lead_status;
                    vm.lead_types = data.lead_types;
                }else{
                    toastr.error( data.message );
                }
                vm.loading_campaigns = false;
            })
            .error(function( data ){
                toastr.error('Something went wrong');
                vm.loading_campaigns = false;
            });
        },
        campaignSelected( cid ){
            try{
                this.campaign = $.grep( this.campaigns , function(c){
                    return c.campaignid == cid;
                })[0]
            }catch( e ){
                toastr.error( e.message )
            }

        },
        saveCampaign( e ){
            let vm = this;
            let btn = $(e.target);
            let h   = btn.html();

            $('.btn').prop('disabled', true );
            btn.html( '<i class="icon-spinner2 spinner"></i>' );

            $.post( '/ajax/campaign', $('#campaignForm').serialize() )
            .done( function( data ){
                if( data.success){
                    let is_new = true;

                    for( i=0; i < vm.campaigns.length; i++ ){
                        d = vm.campaigns[i];
                        if( d.campaignid == data.campaign.campaignid ){
                            Vue.set( vm.campaigns , i , data.campaign);
                            is_new = false;
                        }
                    }

                    if( is_new ){
                        vm.campaigns.push( data.campaign );
                    }

                    vm.init();
                    $('#editModal').modal( 'toggle' );
                }else{
                    toastr.error( data.message );
                }
                    $('.btn').prop('disabled', false );
                    btn.html(h);
            }).error(function( data ){
                toastr.error('Something went wrong');
                    $('.btn').prop('disabled', false );
                    btn.html(h);
            });
        },
        openEditDiv(){
            this.campaign = { actions: [] , trigger: {} };
            $('#editModal').modal();
        },
        editCampaign( e ){
            $('#editModal').modal();
        },
        deleteCampaign( e ){
            if( ! confirm( 'Are you sure you want to delete this campaign ? All pending actions will be cancelled. ' ) ){
                return null;
            }

            let vm = this;
            $.ajax({ url: '/ajax/marketing/campaign', type: 'DELETE',dataType:'json',
                data:{ campaignid:  vm.campaign.campaignid , _token:$('input[name="_token"]').val() },
                success: function( result ) {
                    vm.campaign = { actions: [] , trigger: {} };
                    vm.init();
                    toastr.success( 'Campaign successfully deleted' );
                }
            }).fail(function() {

            })
        },
        getPostcards(){
            let vm = this;
            vm.loading_postcards = true;

            $.get( '/ajax/marketing/postcards' )
            .done( function( data ){
                if( data.success){
                    vm.postcards = data.postcards;
                }else{
                    toastr.error( data.message );
                }
                vm.loading_postcards = false;
            }).error(function( data ){
                toastr.error('Something went wrong');
                vm.loading_postcards = false;
            });
        },
        addAction(){
            let vm = this;
            $('#editActionModal').modal();
            CKEDITOR.instances['editor1'].setData('');
            $('#subject').val( '' );
        },
        editAction( actionid ){
            this.action = $.grep( this.campaign.actions , function( a ){
                return a.actionid == actionid;
            })[0];
            this.getPostcards();
            CKEDITOR.instances['editor1'].setData( this.action.message );
            $('#editActionModal').modal();
        },
        saveAction( e ){
            let vm = this;
            this.action_message = CKEDITOR.instances.editor1.getData();
            let btn = $(e.target);
            let h   = btn.html();

            $('.btn').prop('disabled', true );
            btn.html( '<i class="fa fa-spin fa-refresh"></i>' );

            //validation
            if( this.action_typeid == 6 ){
                if( ! this.postcard_id ){
                    toastr.error( 'Please select a postcard!' );
                    $('.btn').prop('disabled', false );
                    btn.html( h );
                    return;
                }
            }

            $('#message').val( CKEDITOR.instances['editor1'].getData() );
            $.post( '/ajax/campaign/action' , $('#actionForm').serialize() )
            .done(function( data ){
                if( data.success ){
                    let is_new = true;
                    for( i=0; i < vm.campaign.actions.length; i++ ){
                        d = vm.campaign.actions[i];
                        if( d.actionid == data.action.actionid  ){
                            is_new = false;
                            Vue.set( vm.campaign.actions , i , data.action );
                            break;
                        }
                    }

                    if( is_new ){
                        vm.campaign.actions.push( data.action );
                    }

                    toastr.success( 'Action successfully saved' );
                    $('#editActionModal').modal( 'toggle' );
                }else{
                    toastr.error( data.message );
                }
                $('.btn').prop('disabled', false );
                btn.html( h );
            })
            .error(function( data ){
                toastr.error('Something went wrong');
                $('.btn').prop('disabled', false );
                btn.html( h );
            });
        },
        deleteAction( actionid ){
            if( ! confirm( 'Are you sure you want to delete this action?' ) ){
                return ;
            }
            let vm  = this;
            $.ajax({ url: '/ajax/campaign/action', type: 'DELETE',dataType:'json',
                data:{ actionid:actionid, _token:$('input[name="_token"]').val() },
                success: function( data ) {
                    if( data.success ){

                        for( i=0; i < vm.campaign.actions.length; i++ ){
                            d = vm.campaign.actions[i];
                            if( d.actionid == actionid ){
                                d.deleted = 1;
                                vm.campaign.actions.splice( i , 1 );
                                toastr.success( 'Action successfully deleted' );
                            }
                        }
                        
                        for( i=0; i < vm.campaigns.length; i++ ){
                            let c = vm.campaigns[i];
                            if( c.campaignid == vm.campaignid ){
                                for( j=0; j < c.actions.length; j++ ){
                                    let act = c.actions[ j ];
                                    if( act.actionid == actionid ){
                                        vm.campaigns[i].actions.splice( j, 1 );
                                    }
                                }
                            }
                        }
                    }else{
                        toastr.error( data.message );
                    }
                }
            }).fail(function(){

            })
        },
        actionDelaySelected( e ){
            this.action.sending_delay = $(e.target).val();
        },
        actionTypeSelected( e ){
            let typeid = $(e.target ).val();
            if( this.postcards.length == 0 ){
                this.getPostcards();
            }

            this.action.action_typeid = typeid;

        },
        campaignActions(){
            return $.grep( this.campaign.actions, function( a ){
                return a.deleted == 0;
            });
        },
        previewPostcard( postcard_id ){
            this.postcard = $.grep( this.postcards , function(p){
                return p.postcard_id == postcard_id;
            })[0];
            $('#postcardModal' ).modal()
        }
    },
    computed:{
        sortedCampaigns(){

        },
        sortedActions(){
            return _.sortBy( this.campaign.actions , function( a ){ return a.sending_delay } );
        }
    },
    mounted:function(){
        this.init();
    }
});

$(document).ready(
    function(){

        CKEDITOR.replace( 'editor1' );
    }
);