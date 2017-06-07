<div id="editLeadGroupModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>

            <div class="modal-body">
                <form id="groupForm">
                    <div class="form-group">
                        <label for=""> Group Name</label>
                        <input type="text" name="group_name" value="" id="group_name" class="form-control" />
                    </div>
                    <?php echo csrf_field() ?>
                    <input type="hidden" name="lead_group_id" id="lead_group_id" value="" :value="group.lead_group_id" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="saveGroup">Save</button>
            </div>
        </div>
    </div>
</div>

<!--
<div id="" class="modal fade large" role="dialog">
    <form id="leadGroupForm">
        <div class="modal-dialog">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div style="padding:12px">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"> <?php echo trans( 'leads.lead group' ) ?> </h4>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="group_name"><?php echo trans('leads.group name') ?> </label>
                                <?php echo \Form::text( 'group_name' , '{{group.group_name}}' , [ 'class' => 'form-control' , 'id'=>'group_name' ] ) ?>
                            </div>
                        </div>
                    </div>
,
                    <div class="clearfix"></div>
                    <br />
                    <hr />
                    <button type="button" class="btn btn-success" v-on:click="saveGroup()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <br /><br />

                </div>
            </div>
        </div>
        <input type="hidden" name="lead_group_id" id="lead_group_id" value="{{group.lead_group_id}}" />
    </form>
</div>
-->