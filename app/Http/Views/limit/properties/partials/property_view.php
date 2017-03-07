<div>
    <div class="panel panel-flat">
        <div class="panel-heading">
            <div class="row">
            <div class="pull-right">
                <a href="javascript:" class="btn btn-default" @click="openProperties()">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <a href="javascript:" class="btn btn-default" @click="editProperty()">
                    <i class="fa fa-edit"></i>
                </a>
            </div>
            <div class="hidden-xs"><b><span style="font-size: 18px">{{property.tag_line}}</span></b></div>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="thumbnail">
                        <div class="thumb">
                            <img src="" :src=" current_photo.url ? current_photo.url : '/icons/home.png' " />
                            <div class="caption-overflow">
                                    <span>
                                        <a href="javascript:" @click="prevPhoto"
                                           class="prev-btn btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-arrow-left12"></i></a>
                                        <a href="javascript:" @click="setPrimary()" v-show="current_photo.is_primary == 1 ? false : true" title="Set as Primary"
                                           class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5" title="Delete"><i class="icon-check"></i></a>
                                        <a href="javascript:" @click="deletePhoto()"
                                           class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-trash"></i></a>
                                        <a href="javascript:" @click="nextPhoto" data-popup="lightbox" rel="gallery"
                                           class="next-btn btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-arrow-right13"></i></a>
                                    </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="thumb col-lg-3 col-xs-3" v-for="(p, index) in property_photos" >
                            <a href="javascript:" @click="gotoPhoto( index )"><img src="" :src="p.url" style="height:72px;width:72px;padding:2px" :style=" current_photo_index==index ? 'border:2px solid #7777FF' : '' "/></a>
                        </div>
                    </div>
                    <br />
                    <div class="row pace-demo">
                        <form enctype="multipart/form-data">
                        <label class="btn btn-default btn-file">
                            <i class="fa fa-upload"></i> Upload Photo
                            <input style="display: none; color: transparent;" id="fileupload" type="file" name="photo" class="file-input"
                                   data-url="<?php echo Url('/ajax/property/photo/upload') ?>">
                        </label>

                        <div id="progress" style="padding:8px; margin-top:12px;background-color:black;" v-show=" progress > 0 ">
                            <div style="padding:1px; background-color:lightgray; ">
                                <div class="bar" style="width:0%;background-color:green;display:block;height:4px;">&nbsp;</div>
                            </div>
                        </div>
                            <input type="hidden" name="propertyid" id="" value="" :value="property.id"/>
                            <?php echo csrf_field() ?>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table">
                                <tr>
                                    <td>Price</td>
                                    <td class="val">{{property.price}}</td>
                                </tr>
                                <tr>
                                    <td>Beds</td>
                                    <td class="val">{{property.beds}}</td>
                                </tr>
                                <tr>
                                    <td>Baths</td>
                                    <td class="val">{{property.baths}}</td>
                                </tr>
                                <tr>
                                    <td>Floor Area</td>
                                    <td class="val">{{property.floor_area}}</td>
                                </tr>
                                <tr>
                                    <td>Lot Area</td>
                                    <td class="val">{{property.floor_area}}</td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table class="table">
                                <tr>
                                    <td>Property ID</td>
                                    <td class="val">{{property.id}}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td class="val">{{property.status}}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td class="val">{{property.type}}</td>
                                </tr>
                                <tr>
                                    <td>Transaction</td>
                                    <td class="val">{{property.transaction_type}}</td>
                                </tr>
                                <tr>
                                    <td>Date Added</td>
                                    <td class="val">{{property.posted_at}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row col-lg-12" style="margin-top: 24px">
                            <b>Description </b><Br/><br />
                           <div v-html="property.description"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-flat">
        <div class="panel-heading">
            <b><span style="font-size: 18px">Location</span></b>
            <div class="heading-elements">
                <a href="javascript:" class="btn btn-default" @click="">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-6">
                <table class="table table-striped">
                    <tr>
                        <td>Address:</td>
                        <td>{{property.address}}</td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td>{{property.country}}</td>
                    </tr>
                    <tr>
                        <td>State</td>
                        <td>{{property.state}}</td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td>{{property.city}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


