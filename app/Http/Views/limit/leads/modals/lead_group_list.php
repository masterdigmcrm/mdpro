<div id="groupListModal" class="modal" role="dialog" >
    <form id="groupListForm">
        <div class="modal-dialog">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div style="padding:12px">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"> <?php echo trans( 'leads.add leads to group' ); ?> </h4>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <select class="form-control" id="select_lead_group_id">
                                    <option value="0"> Select </option>
                                    <option value="" :value="l.lead_group_id" v-for="l in lead_groups">{{l.group_name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <hr />
                    <div class="pull-right">
                        {{selected_count}} <?php echo trans('leads.leads selected') ?>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" v-on:click="saveLeadsToGroup()">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <br /><br />

                </div>
            </div>
        </div>
    </form>
</div>

