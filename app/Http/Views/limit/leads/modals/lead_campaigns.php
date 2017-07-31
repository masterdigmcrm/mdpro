<div id="leadCampaignModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"> Add {{ lead.first_name }} {{ lead.last_name }} to campaigns</h4>
            </div>
            <div class="modal-body">
                <form id="leadCampaignForm">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table">
                            <tr>
                                <th style="width: 32px"></th>
                                <th>Campaign</th>
                            </tr>
                            <tr v-for="c in lead_campaigns">
                                <td><input type="checkbox" name="campaignid[]" value="" :value="c.campaignid" /></td>
                                <td> {{ c.campaign_name }} </td>
                            </tr>
                        </table>
                    </div>
                </div>
                    <?php echo csrf_field() ?>
                    <input type="hidden" name="leadid" id="leadid" value="" :value="lead.leadid" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="addLeadToCampaign"> Add </button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Close </button>
            </div>
        </div>
    </div>
</div>