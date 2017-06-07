<div id="editLeadModal" class="modal fade large" role="dialog" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="padding:12px">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"> Lead Info </h4>
                    </div>
                </div>
                <form id="leadForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12" style="padding: 12px">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab1" data-toggle="tab" aria-expanded="false"> Contact Details </a>
                                </li>
                                <li class="">
                                    <a href="#tab2" data-toggle="tab" aria-expanded="false"> Address </a>
                                </li>
                                <li class="">
                                    <a href="#tab3" data-toggle="tab" aria-expanded="false"> Assigned To </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active in" id="tab1">
                                    <div class="row">
                                        <br />
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" name="first_name" value="" id="first_name" class="form-control" :value="lead.first_name"/>
                                                <?php //echo \Form::text( 'first_name' , '{{lead.first_name}}' , [ 'class' => 'form-control' , 'id'=>'first_name' ] ) ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" name="last_name" value="" id="last_name" class="form-control" :value="lead.last_name"/>
                                                <?php //echo \Form::text( 'last_name' , '{{lead.last_name}}' , [ 'class' => 'form-control' , 'id'=>'last_name' ] ) ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="status">Type</label>
                                                <?php echo \App\Models\Leads\LeadTypes::selectList( $account->brokerid   ) ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="type">Status</label>
                                                <?php echo \App\Models\Leads\LeadStatus::selectList( $account->brokerid   ) ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="type">Source</label>
                                                <?php echo \App\Models\Leads\LeadSource::selectList( $account->broker_userid ) ?>
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
                                                <?php //echo \Form::text( 'phone_work' , '{{lead.phone_work}}' , [ 'class' => 'form-control' , 'id'=>'phone_work' ] ) ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone_mobile">Mobile Phone</label>
                                                <input type="text" name="phone_mobile" value="" id="phone_mobile" class="form-control" :value="lead.phone_mobile"/>
                                                <?php //echo \Form::text( 'phone_mobile' , '{{lead.phone_mobile}}' , [ 'class' => 'form-control' , 'id'=>'phone_mobile' ] ) ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email1" value="" id="email1" class="form-control" :value="lead.email1"/>
                                                <?php //echo \Form::text( 'email1' , '{{lead.email}}' , [ 'class' => 'form-control' , 'id'=>'email' ] ) ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="company">Company</label>
                                                <input type="text" name="company" value="" id="company" class="form-control" :value="lead.company"/>
                                                <?php //echo \Form::text( 'company' , '{{lead.company}}' , [ 'class' => 'form-control' , 'id'=>'company' ] ) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab2">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="type">Country</label>
                                            <?php echo \App\Models\Locations\Countries::selectList(); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">State</label>
                                            <select class="form-control" name="stateid" id="stateid" v-on:change="stateSelected()">
                                                <option value="0" selected></option>
                                                <option v-for="s in states" value="{{s.id}}" v-bind:selected="lead.stateid==s.id ? true : false ">{{s.state}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <select class="form-control" name="cityid" id="cityid">
                                                <option value="0"> </option>
                                                <option v-for="ci in cities" value="{{ci.id}}" v-bind:selected="lead.cityid==ci.id ? true : false ">{{ci.city}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="street_address">Street Address</label>
                                            <input type="text" name="primary_street_address" value="" id="primary_street_address" class="form-control" :value="lead.primary_address_street"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Postal Code</label>
                                            <input type="text" name="primary_address_postalcode" value="" id="primary_address_postalcode" class="form-control" :value="lead.primary__street_postalcode" />
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab3">
                                    <?php $user_map = \App\Models\Users\UserEntity::me()->userMap ?>
                                    <?php if( $user_map->is_manager() ){ ?>
                                        <div class="col-lg-5">
                                            <label for="company">Assign To:</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <?php echo $user_map->subordinateSelectList(); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <hr />


                        </div>
                        <div>
                            <button type="button" class="btn btn-success" v-on:click="savelead()">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                    <input type="hidden" name="lead-index" id="lead-index" value="-1" />
                    <input type="hidden" name="leadid" id="leadid" value="{{lead.leadid}}" />
                </div>
                </form>
            </div>

            <div class="modal-footer">

            </div>
        </div>
</div>
