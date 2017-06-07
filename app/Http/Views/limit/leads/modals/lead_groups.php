<div id="leadGroupsModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Lead Groups <button class="btn btn-primary" @click="createLeadGroup"> <i class="fa fa-plus"></i> </button> </h4>
            </div>
            <div class="modal-body">
                <form id="leadGroupsForm">
                <div class="row">
                    <div class="col-lg-4">
                        <div>
                            <div class="pull-right">
                                <i class="fa fa-envelope-o"></i>
                            </div>
                        </div>
                        <table class="table">
                            <tr v-for="( g , index ) in lead_groups">
                                <td> <input type="checkbox" name="grp[]" class="grp" :id="'grp'+g.lead_group_id" :data-name="g.group_name" value="" :value="g.lead_group_id" /> </td>
                                <td> <a href="javascript:" data-gid="" :data-gid="g.lead_group_id" @click="showGroupLeads(g.lead_group_id)"><b>{{ g.group_name }}</b></a>  </td>
                                <td>
                                    <ul class="icons-list">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="javascript:" data-gid="" :data-gid="g.lead_group_id" @click="showGroupLeads(g.lead_group_id)"><i title="Show Members" class="fa fa-user"  style="cursor: pointer"></i> Show Members</a></li>
                                                <li><a href="javascript:" @click="addToCampaign(g.lead_group_id)"><i title="Add to Campaign" class="icon-envelop3"  style="cursor: pointer"></i> Add to Campaign </a></li>
                                                <li><a href="javascript:" data-gid="" :data-gid="g.lead_group_id" @click="deleteGroup"><i class="fa fa-trash-o" style="cursor: pointer"></i> Delete </a
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-8">
                        <div class="lead_group_panel" id="members_div">
                            <div v-show="selected_leads.length" class="alert alert-success">
                                <div>
                                    {{ selected_leads.length }} leads are currently selected. You may add those leads to one or more groups by selecting the groups at the left and clicking the Add button below
                                </div>
                                <div style="margin-top: 32px">
                                    <button class="btn btn-primary" id="altg-btn" @click="saveLeadsToGroups"> <i class="fa fa-plus"></i> Add Leads To Groups </button>
                                </div>
                            </div>
                            <div v-show="group.lead_group_id">
                                <h4><b> Group Members ( {{ group.group_name }} ) </b></h4>
                                <table class="table">
                                    <tr v-show="!group_members.length">
                                        <td> No lead associated with this group</td>
                                    </tr>
                                    <tr v-for="m in group_members">
                                        <td>{{ m.last_name+' '+m.first_name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="lead_group_panel hide" id="lead_campaign_div">
                            <div class="row">
                                <div class="pull-right">
                                    <a href="javascript:" class="btn btn-primary" @click="addGroupsToCampaign"> <i class="icon-plus3"></i> Add Selected Groups to Campaign</a>
                                </div>
                                <h5><b>Marketing Campaigns</b></h5>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-striped">
                                        <tr v-for="c in campaigns">
                                            <td> <input type="checkbox" name="cbcampaigns[]" value="" :value="c.campaignid" /> {{c.campaign_name}} </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php echo csrf_field() ?>
                </form>
            </div>

            <div>

            </div>
        </div>
    </div>
</div>