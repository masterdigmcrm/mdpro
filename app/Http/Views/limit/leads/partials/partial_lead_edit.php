<div id="editLeadDiv" class="hide vDiv" role="dialog" >
    <form id="leadForm">
        <div class="panel panel-white">
            <div class="panel-heading">
                <div class="pull-right">
                    <button type="button" class="btn btn-success" v-on:click="savelead"> Save</button>
                    <button type="button" class="btn btn-default" @click="openList"> <i class="fa fa-arrow-left"></i> Back </button>
                </div>
                <h4><b>{{ lead.leadid ? 'Edit' : 'New' }} Lead </b></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <br />
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" value="" id="first_name" class="form-control" :value="lead.first_name"/>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" value="" id="last_name" class="form-control" :value="lead.last_name"/>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="typeid" class="form-control" v-model="lead.typeid">
                                <option value="0">  </option>
                                <option value="" v-for="t in lead_types" :value="t.typeid"> {{t.type}} </option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="type">Status</label>
                            <select name="status" class="form-control" v-model="lead.statusid">
                                <option value="0">  </option>
                                <option value="" v-for="s in lead_status" :value="s.statusid"> {{s.status}} </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type">Source</label>
                            <select name="sourceid" class="form-control" v-model="lead.sourceid">
                                <option value="0">  </option>
                                <option value="" v-for="s in lead_sources" :value="s.sourceid"> {{s.source}} </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="phone_home">Home Phone</label>
                            <input type="text" name="phone_home" value="" id="phone_home" class="form-control" :value="lead.phone_home"/>
                            <?php //echo \Form::text( 'phone_home' , '{{lead.phone_home}}' , [ 'class' => 'form-control' , 'id'=>'phone_home' ] ) ?>
                        </div>
                        <div class="form-group">
                            <label for="phone_work">Work Phone</label>
                            <input type="text" name="" value="phone_work" id="phone_work" class="form-control" :value="lead.phone_work"/>

                        </div>
                        <div class="form-group">
                            <label for="phone_mobile">Mobile Phone</label>
                            <input type="text" name="phone_mobile" value="" id="phone_mobile" class="form-control" :value="lead.phone_mobile"/>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email1" value="" id="email1" class="form-control" :value="lead.email1"/>
                        </div>
                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" name="company" value="" id="company" class="form-control" :value="lead.company"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-white">
            <div class="panel-heading">
                <h4><b>Address</b></h4>
            </div>
            <div class="panel-body">
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" name="primary_address_city" value="" id="city" class="form-control" :value="lead.primary_address_city" />
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" name="primary_address_state" value="" id="state" class="form-control" :value="lead.primary_address_state" />
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" name="primary_address_country" value="" id="country" class="form-control" :value="lead.primary_address_country" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="street_address">Street Address</label>
                        <input type="text" name="primary_address_street" value="" id="primary_street_address" class="form-control" :value="lead.primary_address_street" />
                    </div>
                    <div class="form-group">
                        <label for="type">Postal Code</label>
                        <input type="text" name="primary_address_postalcode" value="" id="primary_address_postalcode" class="form-control" :value="lead.primary_address_postalcode" />
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-white">
            <div class="panel-heading">
                <h4><b>Assignment</b></h4>
            </div>
            <div class="panel-body">
                <?php $user_map = \App\Models\Users\UserEntity::me()->userMap ?>
                <?php if( $user_map->is_manager() ){ ?>
                    <div class="col-lg-5">
                        <label for="company">Assign To:</label>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <select name="assigned_to" class="form-control" v-model="lead.assigned_to">
                                <option value="" v-for="s in my_staff" :value="s.id"> {{ s.name }} </option>
                            </select>
                        </div>
                        <?php //echo $user_map->subordinateSelectList(); ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="panel panel-white">
            <div class="panel-body">
                <button type="button" class="btn btn-success" v-on:click="savelead"> Save </button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel </button>
            </div>
        </div>

        <input type="hidden" name="lead-index" id="lead-index" value="-1" />
        <input type="hidden" name="leadid" id="leadid" value="" :value="lead.leadid"/>
        <input type="hidden" name="ownerid" id="ownerid" value="<?php echo \App\Models\Users\UserEntity::me()->id ?>" />
        <input type="hidden" name="brokerid" id="brokerid" value="<?php echo \App\Models\Accounts\AccountEntity::me()->brokerid ?>" />

        <?php echo csrf_field() ?>
    </form>
</div>
