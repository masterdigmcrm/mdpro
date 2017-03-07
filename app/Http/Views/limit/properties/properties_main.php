<div class="row">
    <div class="col-md-8">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered tasks-widget">
        <div class="portlet-title">
            <div style="float:right">
                Bulk Aciton:
                <select>
                    <option> Send Email</option>
                </select>
                <button class="btn btn-sm btn-primary"> Go </button>
            </div>
            <div class="caption caption-md font-blue">
                <i class="icon-calendar font-blue"></i>
                <span class="caption-subject theme-font bold uppercase">MLS Properties Data</span>
            </div>
        </div>
        <div class="portlet-body">
            <table class="table">
                <?php foreach( $properties as $p ) { ?>
                    <tr>
                        <td style="width:18px">
                            <input type="checkbox" name="cb[]" value="<?php echo $p->ListingId ?>" />
                        </td>
                        <td style="width:72px">
                            <img src="<?php echo \App\Http\Models\Properties\Properties::getPrimaryPhoto( $p )  ?>" style="width:72px"/>
                        </td>
                        <td>
                            <?php echo \App\Http\Models\Properties\Properties::getAddress( $p , $mls , false, true ) ?><br />
                            Beds: <?php echo $p->Bedrooms ?>  Baths: <?php echo $p->Baths ?><br />
                            <h4><?php echo \App\Http\Models\Properties\Properties::displayPrice( $p ) ?></h4>
                        </td>
                    </tr>
                <?php } ?>

            </table>

            <div class="task-footer">
                <div class="btn-arrow-link pull-right">
                    <a href="javascript:;">See All Tasks</a>
                </div>
            </div>
        </div>
        </div>
        <!-- END PORTLET-->
    </div>

    <div class="col-md-4">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered tasks-widget">
            <div class="portlet-title">
                <div class="pull-right">
                    <!--<button class="btn btn-sm btn-primary">Search</button>-->
                </div>
                <div class="caption caption-md font-blue">
                    <i class="icon-calendar font-blue"></i>
                    <span class="caption-subject theme-font bold uppercase">Search</span>
                </div>

            </div>
            <div class="portlet-body">
                <form class="form-horizontal" method="GET">
                <div class="form-group">
                    <label for="q" class="col-sm-3">Keyword</label>
                    <div class="col-sm-9">
                        <?php echo \Form::text( 'q' , \Request::get('q') , [ 'class' => 'form-control' , 'id'=>'q' ] ) ?>
                    </div>
                </div>
                <!--
                <div class="form-group">
                    <label for="" class="col-sm-3">MLS</label>
                    <div class="col-sm-9">
                        <?php //echo \Form::text( 'mls' , \Request::get('q') , [ 'class' => 'form-control' , 'id'=>'q' ] ) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3">Status</label>
                    <div class="col-sm-9">
                        <?php //echo \Form::select( 'mls' , array(), \Request::get('mls') , [ 'class' => 'form-control' , 'id'=>'mls' ] ) ?>
                    </div>
                </div>
                -->
                <div class="form-group">
                    <label for="" class="col-sm-3">Types</label>
                    <div class="col-sm-9">
                        <?php echo App\Http\Models\Properties\Properties::typesSelect( $request->type_select ); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6" >
                        <label for="price">Min Price</label>
                        <?php echo \Form::text( 'min_listprice' , $request->min_listprice , [ 'class' => 'form-control' , 'id'=>'min_listprice' ] ) ?>
                    </div>
                    <div class="col-md-6">
                        <label for="price">Max Price</label>
                        <?php echo \Form::text( 'max_listprice' , $request->max_listprice , [ 'class' => 'form-control' , 'id'=>'max_listprice' ] ) ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6" >
                        <label for="price">Beds</label>
                        <?php echo App\Http\Models\Properties\Properties::bedsSelect( $request->bedrooms ) ?>
                    </div>
                    <div class="col-md-6" >
                        <label for="price">Bath</label>
                        <?php echo App\Http\Models\Properties\Properties::bathsSelect( $request->bathrooms ) ?>
                    </div>
                </div>
                    <hr />
                <div class="form-group">
                    <div class="col-md-6" >
                        <button class="btn btn-sm btn-primary">Search</button>
                    </div>
                </div>
                <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>
