<div class="row" id="pDiv" v-cloak>
    <div id="propertiesDiv" class="row vDiv">
        <div class="col-md-8">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <div style="float:right">
                        <!--
                        Bulk Action:
                        <select>
                            <option> Send Email</option>
                        </select>
                        <button class="btn btn-sm btn-primary"> Go </button>
                        -->
                        <a href="javascript:" class="btn btn-primary btn-sm" @click="newProperty"><i class="fa fa-plus"></i></a>
                    </div>
                    <div class="caption caption-md font-blue">
                        <a href="javascript:" class="btn btn-default btn-sm" v-show="page > 1" @click="previousPage"><i class="fa fa-angle-left"></i> </a>
                        {{ offsetFrom }} {{ offsetTo ? ' - '+offsetTo : '' }} of {{total}}
                        &nbsp; <a href="javascript:" class="btn btn-default btn-sm" v-show="offsetTo < total" @click="nextPage()"><i class="fa fa-angle-right"></i> </a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr v-show=" ! properties.length">
                            <td>
                                <div v-show="searching"><i class="fa fa-spin fa-refresh"></i> Searching...</div>
                                <div v-show="! searching"> No property found </div>
                            </td>
                        </tr>
                        <tr v-for="p in properties" style="cursor: pointer" @click="openProperty( p.id )">
                            <td style="vertical-align:top;width: 90px;padding:4px ">
                                <img src="" style="width: 82px" :src="p.p_photo.url ? p.p_photo.url : '/icons/home.png' " />
                            </td>
                            <td style=" vertical-align: top">
                                <b>{{p.tag_line}}</b><br />
                                {{p.type}}<br />
                                <span v-show="p.beds > 0 ">{{p.beds}} Beds</span> |
                                <span v-show="p.baths > 0 ">{{p.baths}} Baths</span>
                                <br />
                                <b><span style="padding: 1px;font-size: 16px"><?php echo $account->getParamByKey('currency') ?> {{p.price}}</span></b><br />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <!-- BEGIN PORTLET-->
        <div class="panel panel-flat">
            <div class="panel-heading">
                <div class="pull-right">
                    <!--<button class="btn btn-sm btn-primary">Search</button>-->
                </div>
                <div class="caption caption-md font-blue">
                    <i class="icon-calendar font-blue"></i>
                    <span class="caption-subject theme-font bold uppercase">Search</span>
                </div>

            </div>
            <div class="panel-body">
                <?php echo view( 'properties.partials.partial_search') ?>

            </div>
        </div>
    </div>
    </div>

    <div class="row hide vDiv" id="propertyDiv">
        <div class="col-md-12">
        <?php echo view( 'properties.partials.property_view') ?>
        </div>
    </div>
    <div class="row hide vDiv" id="editPropertyDiv">
        <div class="col-md-12">
            <?php echo view( 'properties.partials.partial_property_edit') ?>
        </div>
    </div>
</div>


