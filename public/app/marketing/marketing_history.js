var hVue = new Vue({
    el:'#hDiv',
    data:{
        entries:[],
        loading:false,
        spinner: '<i class="fa fa-spin fa-refresh"></i>',
        more_search_options: false,
        page_count:[],
        current_page: 1,
        total_pages: 0
    },
    methods:{
        init(){
            this.getHistory();
        },
        getHistory(){
            let vm = this;
            vm.loading = true;
            $.get('/ajax/marketing/history' , $('#sForm').serialize() )
            .done( function( data ){
                if( data.success ){
                    vm.entries = data.entries;
                    vm.page_count = data.page_count;
                    vm.total_pages = data.total_pages;
                }else{

                }
                vm.loading = false
            }).error(function( data ){
                vm.loading = false
            });
        },
        search(){
            this.getHistory();
        },
        goToPage( page ){
            this.current_page = page;
            $('#page').val( this.current_page );
            setTimeout( this.getHistory() , 500 );
        },
        prev(){
            this.current_page = this.current_page - 1;
            $('#page').val( this.current_page );
            this.getHistory();
        },
        next(){
            this.current_page = this.current_page + 1;
            $('#page').val( this.current_page );
            this.getHistory();
        }
    },
    mounted:function(){
        this.init();
    }
});

$(document).ready(
    function(){
        $( "#from" ).datepicker( { dateFormat: 'yy-mm-dd' } );
        $( "#to" ).datepicker( { dateFormat: 'yy-mm-dd' }  );


        $( "#lead_name" ).autocomplete({
            source: '/ajax/leads/getleads?autocomplete=1',
            minLength: 2,
            select: function( event, ui ){
                $('#leadid').val( ui.item.id );
            }
        });
    }
);