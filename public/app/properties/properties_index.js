var pVue = new Vue({
    el:'#pDiv',
    data:{
        properties:[],
        property:{},
        countries:[],
        current_photo:{},
        current_photo_index: 0,
        property_photos:[],
        property_status:[],
        property_types:[],
        agents:[],
        states:[],
        cities:[],
        city_selection:[],
        progress: 0,
        transaction_types:['For Sale','For Rent']
        /***models****/


    },
    methods:{
        getProperties(){
            let vm = this;
            $.get( '/ajax/properties/get' )
            .done(function( data ){
                if(data.success){
                    vm.properties = data.properties;
                }else{
                    toastr.error( data.message );
                }
            });
        },
        init(){
            let vm = this;
            $.get( '/ajax/properties/init' )
            .done(function( data ){
                if( data.success){
                    vm.property_status = data.property_status;
                    vm.property_types = data.property_types;
                    vm.countries = data.countries;
                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
            });
        },
        editProperty(){
            this.openPanel( 'editPropertyDiv');
        },
        saveProperty( e ) {
            let vm = this;
            let i;
            $('.btn').prop('disabled', true );
            $(e.target).html('<i class="fa fa-spin fa-refresh"></i>');

            $.post( '/ajax/property/save' , $('#pForm').serialize())
            .done(function( data ){
                if( data.success){
                    for( i=0; i < vm.properties.length; i++ ){
                        d = vm.properties[ i ];
                        if( d.id == data.property.id ){
                            Vue.set( vm.properties , i , data.property );
                            vm.property =  data.property;
                        }
                    }
                    toastr.success( 'Property saved' );
                    vm.openPanel( 'propertyDiv' );
                }else{
                    toastr.error( data.message );
                }
                $('.btn').prop( 'disabled', false );
                $(e.target).html('<i class="fa fa-save"></i> Save');

            })
            .error(function( data ){
                toastr.error('Something went wrong');
                $('.btn').prop( 'disabled', false );
                $(e.target).html('<i class="fa fa-save"></i> Save');
            });
        },
        openProperties() {
            this.openPanel( 'propertiesDiv' );
        },
        openProperty( pid ) {
            this.property            = {};
            this.property_photos     = [];
            this.current_photo       = {};
            this.current_photo_index = 0;
            let i, j = 0;
            let d = {};

            for( i=0; i < this.properties.length; i++ ){

                d = this.properties[i];
                if( d.id == pid ){
                    if( d.countryid ){
                        this.statePopulate( d.countryid , d.stateid );
                    }

                    if( d.stateid ){
                        this.cityPopulate( d.stateid , d.cityid )
                    }

                    this.property = d;
                    this.property_photos = d.photos;
                    console.log( d.photos );
                    if( d.photos instanceof Array && d.photos.length ){
                        // get the primary photo
                        for( j=0; j < d.photos.length; j++ ){

                            dd = d.photos[j];
                            if( dd.is_primary == 1  ){
                                this.current_photo = dd;
                                this.current_photo_index = j;
                            }

                        } 
                    }

                    //setup the country list

                }

            }


            this.openPanel( 'propertyDiv' );
            //$('#propertiesDiv').fadeOut();
            //$('#propertyDiv').removeClass( 'hide' );
        },
        openPanel( panel ){
            $('.vDiv').addClass( 'hide' );
            $('#'+panel).removeClass( 'hide' );
        },
        nextPhoto(){
            next_index = this.current_photo_index + 1;

            if( typeof this.property_photos[next_index] === 'undefined') {
                next_index = 0;
            }

            this.current_photo = this.property_photos[next_index];
            this.current_photo_index = next_index;
        },
        prevPhoto(){
            prev_index = this.current_photo_index - 1;
            if( typeof this.property_photos[ prev_index ] === 'undefined') {
                prev_index = this.property_photos.length - 1
            }

            this.current_photo = this.property_photos[ prev_index ];
            this.current_photo_index = prev_index;
        },
        gotoPhoto( idx ){
            this.current_photo = this.property_photos[ idx ];
            this.current_photo_index = idx;
        },
        deletePhoto(idx){

            if( !confirm('Are you sure you want to delete photo ?') ){
                return;
            }
            let photo_id = this.current_photo.id;
            let vm = this;

            $.post('/ajax/property/photo/delete' , { _token: $('input[name="_token"]').val(), photo_id: photo_id } )
            .done(function( data ){
                if( data.success){
                    toastr.success( 'Photo successfully deleted' );

                    for( i=0; i < vm.property_photos.length; i++ ){
                        d = vm.property_photos[i];
                        if( d.id == data.photo_id ){
                            vm.property_photos.splice( i, 1 );
                            break;
                        }
                    }

                    if( vm.property_photos.length ){
                        vm.current_photo        = this.property_photos[0];
                        vm.current_photo_index  = idx;
                    }

                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
            });
        },
        setPrimary() {
            let photo_id = this.current_photo.id;
            let vm = this;

             $.post( '/ajax/property/photo/setprimary' , { _token: $('input[name="_token"]').val(), photo_id: photo_id } )
             .done(function( data ){
                 if( data.success){
                     toastr.success( 'Photo successfully set as primary' );
                     p = $.grep( vm.properties, function(e){ return e.id == data.property_id; });
                     p[0].p_photo = data.photo;

                 }else{
                     toastr.error( data.message );
                 }
             }).error(function( data ){
                 toastr.error('Something went wrong');
             });
        },
        search( e ){
            let vm = this;
            $('.btn').prop('disabled', true );
            $(e.target).html('<i class="fa fa-refresh fa-spin"></i>');
            $.get( '/ajax/properties/search' , $('#sForm').serialize() )
            .done(function( data ){
                if( data.success){
                    vm.properties = data.properties;
                }else{
                    toastr.error( data.message );
                }

                $('.btn').prop('disabled', false );
                $(e.target).html('Search');
            })
            .error(function( data ){
                toastr.error('Something went wrong');
                $('.btn').prop('disabled', false );
                $(e.target).html('Search');
            });
        },
        countrySelected(){
            let countryid = $('#countryid').val();
            this.statePopulate( countryid );
        },
        statePopulate( countryid, stateid ){
            let vm = this;
            $.get( '/ajax/location/states', { countryid: countryid } )
            .done(function( data ){
                if( data.success){
                    vm.states = data.states;
                    if( stateid ){
                        setTimeout( function(){
                            $('#stateid').val( stateid )
                        },500)

                    }
                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
            });
        },
        cityPopulate( stateid , cityid ){

            let vm = this;
            let ci , d;

            for( ci=0; ci < vm.cities.length; ci++ ){
                d = vm.cities[ci];
                if( d.stateid == stateid ){
                    vm.city_selection = d.cities;
                    return;
                }
            }

            $.get( '/ajax/location/cities', { stateid: stateid } )
            .done(function( data ){
                if( data.success){

                    vm.cities.push( data.cities );
                    vm.city_selection = data.cities.cities;

                    if( cityid ){
                        setTimeout( function(){
                            $('#cityid').val( cityid )
                        },500)

                    }
                }else{
                    toastr.error( data.message );
                }
            })
            .error(function( data ){
                toastr.error('Something went wrong');
            });
        },
        stateSelected(){
            let stateid = $('#stateid').val();
            this.cityPopulate( stateid );
        },
        newProperty(){
            this.property = {};
            this.openPanel( 'editPropertyDiv');
        }

    },
    mounted:function(){
        this.init();
        this.getProperties();
    }
});

$(document).ready(function(){

    $( '#fileupload' ).fileupload({
        dataType: 'json',
        progress: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.bar').css('width', progress + '%');
            pVue.progress = progress;
        },
        done: function (e, data) {
            $('.bar').css('width', '0%');
            pVue.progress = 0;
            if( data.result.success){
                toastr.success( 'Photo successfully uploaded' );
                pVue.property_photos.push( data.result.photo );
                idx = pVue.property_photos.length - 1;
                pVue.current_photo        = data.result.photo;
                pVue.current_photo_index  = idx;
            }else{
                toastr.error( data.result.message )
            }
        }
    });

    function initMap() {

        var uluru = {lat: -125.363, lng: 121.044};
        var map = new google.maps.Map(document.getElementById( 'map_canvas' ), {
            zoom: 12,
            center: uluru
        });
        var marker = new google.maps.Marker({
            position: uluru,
            map: map
        });


    }

    //initMap();

});

