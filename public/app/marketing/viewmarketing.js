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
        postcard_id:0
    },
    methods:{
        init(){
            let vm = this;
            $.get('/ajax/campaign/init')
            .done(function( data ){
                if( data.success){
                    vm.campaigns= data.campaigns;
                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
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
            $.get( '/ajax/marketing/postcards' )
            .done( function( data ){
                if( data.success){
                    vm.postcards = data.postcards;
                }else{
                    toastr.error( data.message );
                }
            }).error(function( data ){
                toastr.error('Something went wrong');
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
                    vm.actions.push( data.action );
                    toastr.success( 'Action successfully saved' );
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
        actionDelaySelected( e ){
            if( $(e.target).val() == -1 ){
                this.action_sending_delay = -1;
            }
        },
        actionTypeSelected(){

            if( this.postcards.length == 0 ){
                this.getPostcards();
            }
        }
    },
    computed:{
        sortedCampaigns(){

        },
        campaignActions(){

            return $.grep( this.campaign.actions, function( a ){
                return a.deleted == 0;
            });
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