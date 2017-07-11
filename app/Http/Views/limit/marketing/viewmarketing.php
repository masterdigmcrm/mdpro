<div id="mDiv" v-cloak>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <div class="pull-right">
                        <button class="btn btn-primary" @click="openEditDiv"> <i class="icon-plus3"></i> Create Campaign </button>
                    </div>
                    <h4> <b> Marketing Campaigns </b> </h4>
                </div>
                <div class="panel-body">
                    <div class="col-lg-3">
                        <div v-show="loading_campaigns"> <i class="fa fa-spin fa-refresh"></i> &nbsp;Loading Campaigns... </div>
                        <table class="table">
                            <tr v-for="c in campaigns">
                                <td> <a href="javascript:" @click="campaignSelected( c.campaignid )"> <b>{{ c.campaign_name }} </b> </a> </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-9">
                        <div class="row msection" id="mainDiv">
                            <div class="panel panel-white" v-show=" ! campaign.campaignid">
                                <div class="panel-body">
                                    No Marketing Campaign Selected
                                </div>
                            </div>
                            <div class="panel panel-white" v-show="campaign.campaignid">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h4><b>{{ campaign.campaign_name }}</b></h4>
                                        </div>
                                        <div class="col-lg-6" style="text-align: right">
                                            <button class="btn btn-default" @click="editCampaign"> <i class="icon-pencil3"></i> Edit </button>
                                            <button class="btn btn-primary" @click="addAction"> <i class="icon-plus3"></i> Create Action </button>
                                            <button class="btn btn-danger" @click="deleteCampaign"> <i class="icon-cross"></i> </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <div>
                                        <table class="table table-striped">
                                            <tr>
                                                <th> Action Name</th>
                                                <th> Type </th>
                                                <th> Sending Date </th>

                                                <th></th>
                                            </tr>

                                            <tr v-for="c in sortedActions" v-show="campaign.actions.length">
                                                <td>{{ c.subject }}</td>
                                                <td>{{ c.type }}</td>
                                                <td>{{ c.delay }}</td>
                                                <td>
                                                    <ul class="icons-list">
                                                        <li class="dropdown">
                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-right">
                                                                <li><a href="javascript:" @click="editAction( c.actionid )"><i class="icon-pencil3"></i> Edit Action</a></li>
                                                                <li>
                                                                    <a href="javascript:" @click="deleteAction( c.actionid )"><i class="icon-cross2 text-danger"></i> Delete Action</a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr v-show="!campaign.actions.length">
                                                <td colspan="4"> No action found </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="editModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 class="modal-title"><b> {{ campaign.campaignid ? 'Edit' : 'Create New'  }} Campaign </b> </h5>
                </div>
                <div class="modal-body">
                    <form id="campaignForm">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label for="campaign_name">Campaign Name</label>
                                    <input type="text" name="campaign_name" value="" id="campaign_name" class="form-control" :value="campaign.campaign_name"/>
                                </div>
                                <div class="form-group">
                                    <label for="campaign_description">Description</label>
                                    <textarea name="campaign_description" id="campaign_description" class="form-control" v-html="campaign.campaign_description"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="">Published</label>
                                    <select name="published" class="form-control" v-model="campaign.published">
                                        <option value="0"> No </option>
                                        <option value="1"> Yes </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="availability"> Availability </label>
                                    <select name="availability" class="form-control" v-model="campaign.availability">
                                        <option value="private"> Private </option>
                                        <option value="public"> Public </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <b>Triggers</b>
                            <hr />
                            <div class="col-lg-6">
                                Send message when <b>lead status</b> equals <br />
                                <div class="form-group">
                                    <select name="statusid" class="form-control" :value="campaign.trigger.statusid">
                                        <option value="0"> Any </option>
                                        <option value="" v-for="s in lead_status" :value="s.statusid"> {{s.status}} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                Send message when <b>lead type</b> equals <br />
                                <div class="form-group">
                                    <select name="typeid" class="form-control" >
                                        <option value="0"> Any </option>
                                        <option value="" v-for="t in lead_types" :value="t.typeid"> {{t.type}} </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="campaignid" id="campaignid" value="" :value="campaign.campaignid" />
                        <?php echo csrf_field() ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="saveCampaign">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo view( 'marketing.partials.edit_action_modal' ) ?>

    <div id="postcardModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><b>Postcard Preview</b></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <h6><b>Front</b></h6>
                        <div class="col-lg-12">
                            <img src="" class="img-responsive" :src="postcard.front" />
                        </div>
                    </div>
                    <div class="row">
                        <h6><b>Back</b></h6>
                        <div class="col-lg-12">
                            <img src="" class="img-responsive" :src="postcard.back" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.


</script>

