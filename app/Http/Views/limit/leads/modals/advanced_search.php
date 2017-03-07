<div id="advanceSearchModal" class="modal fade large" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header green">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Advanced Search</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="lead_status">Lead Status</label>
                        <?php
                            //echo  App\Http\Models\Leads\LeadStatus::selectList( App\Http\Models\Accounts\Accounts::my()->brokerid )
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="status">Type</label>
                        <?php //echo \App\Http\Models\Leads\LeadTypes::selectList( $account->brokerid )
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="type">Source</label>
                        <?php //echo \App\Http\Models\Leads\LeadSource::selectList( $account->broker_userid ) ?>
                    </div>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" v-on:click="advProcessSearch()" id="adv_search_button">Search</button>
            </div>
        </div>
    </div>
</div>