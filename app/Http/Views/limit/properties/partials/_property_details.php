<div class="row">
    <div class="col-lg-6">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th colspan="2">
                    Listing Details
                </th>
            </tr>
            </thead>
            <tr>
                <td> MLS ID: </td>
                <td><?php echo $p->MLSID ?></td>
            </tr>
            <tr>
                <td> Price: </td>
                <td><?php echo  App\Http\Models\Properties\Properties::displayPrice(  $p );  ?></td>
            </tr>
            <tr>
                <td> Beds: </td>
                <td><?php echo   $p->Bedrooms;  ?></td>
            </tr>
            <tr>
                <td> Baths: </td>
                <td><?php echo   floor( $p->Baths );  ?></td>
            </tr>
            <tr>
                <td> Floor Area: </td>
                <td><?php echo   $p->FloorArea;  ?> sq ft</td>
            </tr>
            <tr>
                <td> Lot Area: </td>
                <td><?php echo   $p->LotArea;  ?> sq ft</td>
            </tr>
            <tr>
                <td> Status: </td>
                <td><?php echo   $p->Status;  ?></td>
            </tr>
            <tr>
                <td> Type: </td>
                <td><?php echo   isset( $p->Type ) ? $p->Type : '';  ?></td>
            </tr>
            <tr>
                <td> Year Built: </td>
                <td><?php echo !empty( $p->YearBuilt ) ? $p->YearBuilt : '' ;  ?></td>
            </tr>
            <tr>
                <td> Description: </td>
                <td><?php  echo $p->Description;  ?></td>
            </tr>
        </table>
    </div>

    <div class="col-lg-6">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th colspan="2">
                    Address
                </th>
            </tr>
            </thead>
            <?php if( $p->Latitude && $p->Longitude ){ ?>
                <tr>
                    <td colspan="2">
                        <div>
                            <div class="pull-right" style="z-index: 99; ">
                                <button class="btn btn-primary btn-xs" id="showStreetView">Street View</button>
                                <a href="https://www.google.com/maps/preview/?q=<?php echo $p->Latitude ?>,<?php echo $p->Longitude ?>" target="_blank">
                                    <button class="btn btn-primary btn-xs" id="">
                                        Full Page Map
                                    </button>
                                </a>
                                <!--<a href=""><button class="btn btn-primary btn-xs" id="showStreetView">View in Google Maps</button></a>-->
                            </div>
                            <br /><br />
                            <div class="clearfix"></div>
                            <div id="map" style="height:300px;"></div>
                            <br />
                        </div>

                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td> Street: </td>
                <td><?php echo \App\Http\Models\Properties\Properties::getAddress( $p, $mls , false ) ?></td>
            </tr>
            <tr>
                <td> City: </td>
                <td><?php echo   ucwords( strtolower( $p->City )) ?></td>
            </tr>
            <tr>
                <td> County: </td>
                <td><?php echo  !empty( $p->County ) ?  ucwords( strtolower( $p->County )) : ''  ?></td>
            </tr>
            <tr>
                <td> State: </td>
                <td><?php echo   ucwords( strtolower( $p->State )) ?></td>
            </tr>
            <tr>
                <td> Postal Code: </td>
                <td><?php echo   ucwords( strtolower( $p->PostalCode ))  ?></td>
            </tr>
            <tr>
                <td> Community: </td>
                <td><?php echo  isset( $p->Community ) ? ucwords( strtolower( $p->Community )) : '';   ?></td>
            </tr>
            <tr>
                <td> Subdivision: </td>
                <td><?php echo  isset( $p->Subdivision ) ? ucwords( strtolower( $p->Subdivision )) : '';  ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th colspan="2">
                    Internal Features
                </th>
            </tr>
            </thead>
            <tr>
                <td> Fireplace: </td>
                <td><?php echo  isset( $p->Fireplace )  ? $p->Fireplace : 'None'  ?></td>
            </tr>
            <tr>
                <td> Heating and Fuel: </td>
                <td><?php echo   isset( $p->Heating ) ? ucwords( strtolower( $p->Heating )) : '' ;  ?></td>
            </tr>
            <tr>
                <td> Flooring: </td>
                <td><?php echo   isset( $p->Flooring ) ? ucwords( strtolower( $p->Flooring )) : '' ?></td>
            </tr>
            <tr>
                <td> Airconditioning: </td>
                <td><?php
                    if( isset( $p->Airconditioning )) {
                        echo ucwords(strtolower($p->Airconditioning));
                    }
                    ?></td>
            </tr>
            <tr>
                <td> Appliance Included: </td>
                <td><?php
                    if( isset( $p->Appliances )) {
                        echo str_replace(',', ', ', $p->Appliances);
                    }
                    ?></td>
            </tr>
        </table>
    </div>
    <div class="col-lg-6">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th colspan="2">
                    External Features
                </th>
            </tr>
            </thead>
            <tr>
                <td> Construction: </td>
                <td><?php echo   isset( $p->Construction ) ?  $p->Construction : ''; ?></td>
            </tr>
            <tr>
                <td> Foundation: </td>
                <td><?php echo  isset( $p->Foundation ) ? $p->Foundation : ''  ?></td>
            </tr>
            <tr>
                <td> Garage Features: </td>
                <td><?php echo isset( $p->GarageFeatures ) ? $p->GarageFeatures : '' ?></td>
            </tr>
            <tr>
                <td> Garage / Carport: </td>
                <td><?php echo isset( $p->Garage ) ? $p->Garage : ''; ?></td>
            </tr>
            <tr>
                <td> Roof: </td>
                <td><?php echo isset( $p->Roof ) ? $p->Roof : '' ?></td>
            </tr>
            <tr>
                <td> Utilities: </td>
                <td><?php echo isset( $p->Utilities ) ? $p->Utilities : '' ?></td>
            </tr>
            <tr>
                <td> Exterior Features: </td>
                <td><?php
                    if( isset( $p->ExteriorFeatures ) ){
                        echo \App\Http\Models\Properties\Properties::arrayToString( $p->ExteriorFeatures );
                    }
                    //echo isset( $p->ExteriorFeatures ) ? str_replace( ',' , ', ', $p->ExteriorFeatures ) : '';
                    ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th colspan="2">
                    Listing Agent and Office
                </th>
            </tr>
            </thead>
            <tr>
                <td> Listing Office: </td>
                <td><?php echo   isset (  $p->ListingOffice ) ? ucwords( strtolower( $p->ListingOffice )) : ''; ?></td>
            </tr>
            <tr>
                <td> Listing Agent Name: </td>
                <td><?php echo  isset( $p->ListingAgentFullName ) ? ucwords( strtolower( $p->ListingAgentFullName ) ): ''; ?></td>
            </tr>
            <tr>
                <td> Listing Phone Number: </td>
                <td><?php echo  isset( $p->ListingAgentPhone ) ? $p->ListingAgentPhone : ''  ?></td>
            </tr>
            <tr>
                <td> Listing Office Code: </td>
                <td><?php echo  isset( $p->LO_Code ) ? ucwords( strtolower( $p->LO_Code )) : '' ?></td>
            </tr>
        </table>
    </div>
    <div class="col-lg-6">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th colspan="2">
                    Taxes
                </th>
            </tr>
            </thead>
            <tr>
                <td> Taxes: </td>
                <td><?php echo isset(  $p->Tax ) ? $p->Tax : '' ?></td>
            </tr>
            <tr>
                <td> Tax Year: </td>
                <td><?php echo  isset( $p->TaxYear ) ? $p->TaxYear : '';  ?></td>
            </tr>
        </table>

    </div>
</div>


