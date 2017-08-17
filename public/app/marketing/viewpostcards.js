var pVue = new Vue({
    el:'#pDiv',
    data:{
        postcards:[],
        postcard:{ dimension : '1275-1875' },
        progress_front: 0,
        progress_back: 0,
        edit_postcard : false
    },
    methods:{
        init(){
            let vm = this;
            $.get('/ajax/postcards' )
            .done( function( data ){
                if( data.success ){
                    vm.postcards = data.postcards;
                }
            }).error(function( data ){

            });
        },
        openPostcardModal(){
            this.postcard = {};
            $('#postcardModal').modal()
        },
        editPostcard( postcard_id ){
            this.postcard = $.grep( this.postcards, function( p ){
                return p.postcard_id == postcard_id;
            })[0];

            if( ! this.postcard.dimension ){
                this.postcard.dimension = '1275-1875';
            }
            $('#postcardModal').modal()
        },
        savePostcard( e ){
            let vm = this;
            let btn = $(e.target);
            let h   = btn.html();

            $('.btn').prop('disabled', true );
            btn.html( '<i class="fa fa-spin fa-refresh"></i>' );

            $.post( '/ajax/postcards' ,  $('#pForm').serialize())
            .done(function( data ){
                if( data.success){
                    vm.postcard = data.postcard;
                    toastr.success( 'Postcard successfully saved. You may now upload the front and back images of the postcard' );

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
        deletePostcard( postcard_id ){
            let vm = this;
            if( ! confirm( 'Are you sure you want to delete postcard ? All campaign actions using this postcard will also be canceled ' ) ){
                return;
            }

            $.ajax({ url: '/ajax/postcards', type: 'DELETE',dataType:'json',
            data:{ postcard_id : postcard_id , _token : $('input[name=_token]').val() },
            success: function( result ) {
                toastr.success( 'Postcard successfully deleted' );
                for( i=0; i < vm.postcards.length; i++ ){
                    d = vm.postcards[i];
                    if( d.postcard_id == postcard_id ){
                        vm.postcards.splice( i , 1 );
                    }
                }
            }
            }).fail(function() {

            })
        }
    },
    computed:{
        frontPostcardSrc(){
            return this.postcard.front ? this.postcard.front : '/images/placeholder.jpg';
        },
        backPostcardSrc(){
            return this.postcard.back ? this.postcard.back : '/images/placeholder.jpg';
        }
    },
    mounted:function(){
        this.init();
    }
});

$( '#frontUpload' ).fileupload({
    dataType: 'json',
    progress: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.bar').css('width', progress + '%');
        pVue.progress_front = progress;
    },
    done: function (e, data) {
        $('.bar').css('width', '0%');
        pVue.progress_front = 0;

        if( data.result.success){
            pVue.postcard = data.result.postcard;
            toastr.success( 'Postcard successfully uploaded' );
            for( i=0; i < pVue.postcards.length; i++ ){
                d = pVue.postcards[i];
                if( d.postcard_id == data.result.postcard.postcard_id ){
                    Vue.set( pVue.postcards , i , data.result.postcard );
                }
            }
        }else{
            toastr.error( data.result.message )
        }
    },
    error: function (e, data) {
        pVue.progress_front = 0;
        toastr.error( 'Something went wrong')
    }
});

$( '#backUpload' ).fileupload({
    dataType: 'json',
    progress: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.bar_back').css('width', progress + '%');
        pVue.progress_back = progress;
    },
    done: function (e, data) {
        $('.bar_back').css('width', '0%');
        pVue.progress_back = 0;

        if( data.result.success){
            pVue.postcard = data.result.postcard;
            toastr.success( 'Postcard back cover successfully uploaded' );
            for( i=0; i < pVue.postcards.length; i++ ){
                d = pVue.postcards[i];
                if( d.postcard_id == data.result.postcard.postcard_id ){
                    Vue.set( pVue.postcards , i , data.result.postcard );
                }
            }
        }else{
            toastr.error( data.result.message )
        }
    },
    error: function (e, data) {
        pVue.progress_back = 0;
        toastr.error( 'Something went wrong')
    }
});