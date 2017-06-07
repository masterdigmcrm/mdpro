<div class="" id="postcard_div">

    <div>
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-white">

                    <div class="panel-body">
                        <div class="pull-right">
                            <button class="btn btn-success btn-sm" @click="openPostcardModal"> Add Postcard </button>
                        </div>
                        <h4>Postcards</h4>
                        <table class="table table-stripped">
                            <tr>
                                <th> &nbsp;</th>
                                <th> Front </th>
                                <th> Back </th>
                                <th></th>
                            </tr>
                            <tr v-for="p in postcards">
                                <td>{{p.postcard_name}}</td>
                                <td> <img src="" v-bind:src="p.frondt" style="width:72px"/></td>
                                <td> <img src="" v-bind:src="p.back" style="width:72px"/></td>
                                <td>
                                    <!--<button class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button>-->
                                    <button class="btn btn-default btn-xs"><i class="fa fa-trash"></i></button>
                                    <button class="btn btn-default btn-xs" v-on:click="selectCities( p.postcard_id )"><i class="fa fa-building-o"></i></button>
                                    <button class="btn btn-default btn-xs" v-on:click="viewProperties( p.postcard_id )"><i class="fa fa-home"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="citiesModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">City Coverage</h4>

                </div>
                <div class="modal-body">
                    <div style="overflow-y: auto;height:400px">
                    <table class="table table-striped">
                        <tr>
                            <th></th>
                            <th>City</th>
                            <th></th>
                        </tr>
                        <tr v-for="c in cities">
                            <td></td>
                            <td>{{c.full}}</td>
                            <td>
                                <button class="btn btn-sm" :class="'btn-'+c.is_added ? 'danger' : 'success' " :id="c.no_space" @click="toggleCity(c.full)">
                                    <i class="fa " :class="'fa-'+c.is_added ? 'minus' : 'plus' " :id="'fa-'+c.no_space"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="postcardPropertiesModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Properties sent with Postcard</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="pull-right">  </div>
                        <div> Total: {{total_postcard_properties}} Sent : {{postcard.sent_count}}</div>
                    </div>
                    <table class="table table-striped">
                        <tr>
                            <th></th>
                            <th>Status</th>
                            <th>Date Sent</th>
                        </tr>
                        <tr v-for="p in properties">
                            <td>
                                <div v-bind:class="p.owner_name ? '' : 'hide' "><b>{{p.owner_name}}</b></div>
                                {{p.property_address}}
                            </td>
                            <td>{{p.status}}</td>
                            <td>{{p.sent_at}}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="poscardModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">New Postcard</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Name">Postcard Name</label>
                        <input type="text" class="form-control" id="postcard_name" name="postcard_name"/>
                        <?php //echo \Form::text( 'postcard_name' , '{{postcard.postcard_name}}' , [ 'class' => 'form-control' , 'id'=>'postcard_name' ] ) ?>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="aid" id="aid" value="<?php echo $account->brokerid ?>" />
</div>