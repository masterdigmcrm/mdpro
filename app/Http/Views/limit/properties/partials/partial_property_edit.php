<form id="pForm" class="" method="GET">
<div class="panel panel-white">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-6">
                <h4>Property Details</h4>
            </div>
            <div class="col-lg-6" style="text-align: right">
                <a href="javascript:" class="btn btn-primary" @click="saveProperty"><i class="fa fa-save"></i> Save</a>
                <a href="javascript:" class="btn btn-default" @click="openPanel('propertyDiv')"> Cancel</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
            <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                   <div class="form-group">
                       <label for="tag_line">Tag Line</label>
                       <input type="text" name="tag_line" value="" id="tag_line" class="form-control" :value="property.tag_line" />
                   </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" style="min-height: 144px" v-html="property.description"></textarea>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" name="price" value="" id="price" class="form-control" :value="property.price"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="beds">Beds {{property.beds}} </label>
                            <select name="beds" class="form-control" v-model="property.beds">
                                <option value="0"> None </option>
                                <option value="" v-for="n in 10" :value="n"> {{n}} </option>
                                <option value="11"> 10+ </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="baths">Baths</label>
                            <select name="baths" class="form-control" v-model="property.baths">
                                <option value="0"> None </option>
                                <option value="" v-for="n in 10" :value="n"> {{n}} </option>
                                <option value="11"> 10+ </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="floor_area">Floor Area</label>
                            <input type="text" name="floor_area" value="" id="floor_area" class="form-control" :value="property.floor_area" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="lot_area">Lot Area</label>
                            <input type="text" name="lot_area" value="" id="lot_area" class="form-control" :value="property.lot_area" />
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="status">Status </label>
                    <select name="property_status" class="form-control" v-model="property.property_status">
                        <option value="0"> All </option>
                        <option value="" v-for="s in property_status" :value="s.statusid"> {{s.status}} </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="property_type">Type</label>
                    <select name="property_type" class="form-control" v-model="property.property_type">
                        <option value="0"> All </option>
                        <option value="" v-for="t in property_types" :value="t.typeid"> {{t.type}} </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="transaction_type">Transaction Type</label>
                    <select name="transaction_type" class="form-control" v-model="property.transaction_type">
                        <option value="" v-for="t in transaction_types" :value="t"> {{t}} </option>
                    </select>
                </div>
            </div>
            </div>
            <?php echo csrf_field() ?>
            <input type="hidden" name="id" id="property_id" value="" :value="property.id" />

    </div>
</div>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h4>Location</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" value="" id="address" class="form-control" :value="property.address"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="countryid">Country</label>
                            <select name="countryid" id="countryid" class="form-control" @change="countrySelected" v-model="property.countryid">
                                <option value="0"> All </option>
                                <option value="" v-for="c in countries" :value="c.countryid"> {{c.country}} </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="stateid"> State / Province </label>
                            <select name="stateid" id="stateid" class="form-control" @change="stateSelected">
                                <option value="0"> All </option>
                                <option value="" v-for="s in states" :value="s.id"> {{s.state}} </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="cityid">City</label>
                            <select name="cityid" class="form-control" id="cityid">
                                <option value="0"> All </option>
                                <option value="" v-for="c in city_selection" :value="c.id"> {{c.city}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" name="postal_code" value="" id="postal_code" :value="property.postal_code" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" name="latitude" value="" id="latitude" :value="property.latitude" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" name="longitude" value="" id="longitude" :value="property.longitude" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div id="map_canvas"></div>
            </div>
        </div>

    </div>
</div>
</form>