
<div id="nextActionModal" class="modal fade large" role="dialog">
    <div class="modal-dialog">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <h4><?php echo trans('leads.next action items') ?> ( {{lead.first_name}} {{lead.last_name}} )</h4>
                </div>
                <div class="tools">
                    <a href="javascript:;" data-dismiss="modal" aria-hidden="true" class="remove" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="panel-group accordion" id="actionAccordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#actionAccordion"
                                   href="#collapse_3_1" aria-expanded="false">
                                    <?php echo trans( 'leads.add to campaign' ); ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_3_1" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                <form id="campaignForm">
                                    <div class="loader"><i class="fa fa-refresh fa-spin"></i> Loading Marketing Campaigns </div>
                                    <table class="table table-striped">
                                        <tr v-for="c in campaigns">
                                            <td><input type="checkbox" class="campaign_cb" name="cb[]" value="{{c.campaignid}}" /></td>
                                            <td>{{c.campaign_name}}</td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="campaign_leadid" id="campaign_leadid" value="{{lead.leadid}}" />
                                </form>
                                <button type="button" class="btn btn-success" id="add-campaigns-button" v-on:click="addcampaigns(lead.leadid)">Add</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#actionAccordion"
                                   href="#collapse_3_2" aria-expanded="false">
                                    <?php echo trans( 'leads.add todo' ); ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_3_2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                <form id="addTodoForm">
                                    <div class="form-group">
                                        <label for="todo">Todo</label>
                                        <textarea id="todoTextAfterSave" class="form-control" style="width:100%"></textarea>
                                    </div>
                                    <div class="input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                                        <label for="todoDate">Date</label>
                                        <input class="span2 form-control" style="position: relative; z-index: 100000;" id="todoDate" size="16" type="text" value="12-02-2015">
                                        <span class="add-on"><i class="icon-th"></i></span>
                                    </div>
                                </form>
                                <button type="button" class="btn btn-success" id="add-todo-button" v-on:click="addTodoAfterSave( lead.leadid )"><?php echo trans( 'leads.add todo' ) ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">

                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#actionAccordion"
                                   href="#collapse_3_3" aria-expanded="false" >
                                    <?php echo trans( 'leads.add notes' ); ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_3_3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                <form id="addNotesForm2">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea id="notesTextAfterSave" class="form-control" style="width:100%"></textarea>
                                    </div>
                                </form>

                                <button type="button" class="btn btn-success" id="add-notes-button" v-on:click="addNotesAfterSave(lead.leadid)"><?php echo trans('leads.add notes') ?> </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="portlet-footer" style="padding:8px">
                <div class="pull-right">
                    <button type="button" class="btn btn-default" id="add-campaigns-button" data-dismiss="modal" aria-hidden="true"> <?php echo trans('leads.skip') ?>  </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>
