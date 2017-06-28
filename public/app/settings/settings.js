var sVue = new Vue({
    el:'#sDiv',
    data:{
        lob_key: ''
    },
    methods:{
        init(){
            let vm = this;
            $.get('/ajax/settings/init' )
            .done(function( data ){
                if( data.success ){
                    vm.lob_key = data.lob_key;
                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
            });
        },
        saveLobApiKey( e ){
            let vm = this;
            let btn = $(e.target);
            let h   = btn.html();

            $('.btn').prop('disabled', true );
            btn.html( '<i class="icon-spinner2 spinner"></i>' );

            $.post( '/ajax/settings/lob' , { _token: $( 'input[name="_token"]' ).val(), lob_key: vm.lob_key } )
            .done(function( data ){
                if( data.success){
                    toastr.success('Lob key successfully updated')
                }else{
                    toastr.error( data.message );
                }
                $('.btn').prop('disabled', false );
                btn.html( h );

            }).error(function( data ){
                toastr.error('Something went wrong');
                $('.btn').prop('disabled', false );
                btn.html( h );
            });
        }
    },
    mounted:function(){
        this.init();
    }
});