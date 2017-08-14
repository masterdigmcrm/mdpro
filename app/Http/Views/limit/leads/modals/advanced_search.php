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
                        <label for="keyword">Keyword</label>
                        <input type="text" name="q2" value="" id="q2" class="form-control" v-model="q"  />
                    </div>
                    <div class="form-group">
                        <label for="lead_status">Lead Status</label>
                        <select name="statusid" class="form-control" v-model="statusid">
                            <option value="0"> All </option>
                            <option value="" v-for="s in lead_status" :value="s.statusid"> {{s.status}} </option>
                        </select>
                        <?php
                        //echo  App\Http\Models\Leads\LeadStatus::selectList( App\Http\Models\Accounts\Accounts::my()->brokerid )
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="status">Type</label>
                        <?php
                            //echo \App\Http\Models\Leads\LeadTypes::selectList( $account->brokerid )
                        ?>
                        <select name="typeid" class="form-control" v-model="typeid">
                            <option value="0"> All </option>
                            <option value="" v-for="t in lead_types" :value="t.typeid"> {{t.type}} </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Source</label>
                        <select name="sourceid" class="form-control" v-model="sourceid">
                            <option value="0"> All </option>
                            <option value="" v-for="s in lead_sources" :value="s.sourceid"> {{s.source}} </option>
                        </select>
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