
var cardVue = new Vue({
    el:'#postcard_div',
    data:{
        postcards:[],
        postcard:{ 'sent_count' : 0 },
        properties:[],
        total_postcard_properties:0,
        cities:[],
        checkedCities:[]
    },
    mounted:function(){
        $.get('/ajax/postcards/get' , {aid:$('#aid').val()})
        .done(function( data ){
            if( data.success ){
                for( i=0; i < data.postcards.length; i++ ){
                    d = data.postcards[i];
                    cardVue.$data.postcards.push( d );
                }
            }
        })
        .error(function( data ){

        });
    },
    methods:{
        toggleCity:function( city ){
          $('.btn').prop('disabled' , true);
          $.post('/ajax/pc/togglecity' , { city:city , postcard_id:this.postcard.postcard_id } )
              .done(function( data ){
                  ns = city.replace(' ', "_");
                  if( data.action == 'added'){
                      $('#'+ns).removeClass('btn-success');
                      $('#'+ns).addClass('btn-danger');
                      $('#fa-'+ns).removeClass('fa-plus');
                      $('#fa-'+ns).addClass('fa-minus');
                  }else{
                      $('#'+ns).removeClass('btn-danger');
                      $('#'+ns).addClass('btn-success');
                      $('#fa-'+ns).removeClass('fa-minus');
                      $('#fa-'+ns).addClass('fa-plus');
                  }
                  $('.btn').prop( 'disabled' , false );
              })
          .error(function( data ){
                  $('.btn').prop( 'disabled' , false );
          });

        },
        selectCities:function( postcard_id ){

            $('#citiesModal').modal();

            if( ! this.cities.length ){
                this.postcard.postcard_id = postcard_id;

                $.get('/ajax/pc/getcities' , {postcard_id:postcard_id})
                .done(function( data ){
                    for( i=0; i < data.lookups.length; i++ ){
                        d = data.lookups[i];
                        cardVue.$data.cities.push( d )
                    }
                })
                .error(function( data ){

                });
            }
        },

        viewProperties:function( postcard_id )
        {
            $('#postcardPropertiesModal').modal();
            $.get('/ajax/postcards/properties' , {postcard_id: postcard_id})
            .done(function( data ){
                cardVue.$data.properties = [];
                if( data.success ){
                    for( i=0; i < data.properties.length; i++ ){
                        d = data.properties[i];
                        cardVue.$data.properties.push(d);
                    }

                    cardVue.$data.total_postcard_properties = data.total;
                    cardVue.$data.postcard.sent_count = data.sent_count;
                }
            })
            .error(function( data ){

            });
        }
    }
});