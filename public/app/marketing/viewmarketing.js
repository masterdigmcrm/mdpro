var mVue = new Vue({
    el:'#mDiv',
    data:{
        campaign:{ actions: [] },
        campaigns:[],
        action:{},
        actions:[],
        action_message: '',
        action_typeid : '1',
        action_sending_delay:0,
        postcards:[],
        postcard:{},
        postcard_id:0,

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
        openEditDiv(){
            $('#editModal').modal();
        },
        campaignSelected( cid ){
            this.campaign = $.grep( this.campaigns , function(c){
                return c.campaignid == cid;
            })[0]

        },
        saveCampaign(){
            let vm = this;
            $.post( '/ajax/campaign', $('#campaignForm').serialize() )
            .done( function( data ){
                if( data.success){

                }else{
                    toastr.error( data.message );
                }
            }).error(function( data ){
                toastr.error('Something went wrong');
            });
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

            $.post( '/ajax/campaign/action' , $('#actionForm').serialize() )
            .done(function( data ){
                if( data.success ){
                    vm.campaign.actions.push( data.action );
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
            if( $(e.target).val() == -1 ){
                this.action_sending_delay = -1;
            }
        },
        actionTypeSelected(){

            if( this.postcards.length == 0 ){
                this.getPostcards();
            }
        },
        campaignActions(){
            return $.grep( this.campaign.actions, function( a ){
                return a.deleted == 0;
            });
        }
    },
    computed:{
        sortedCampaigns(){

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