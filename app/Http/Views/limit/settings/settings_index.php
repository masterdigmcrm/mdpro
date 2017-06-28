<div id="sDiv">
    <?php echo csrf_field() ?>
    <div class="row">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h4><b>Settings</b></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">LOB API</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="lob_key" id="lob_key" v-model="lob_key" >
                                    <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" @click="saveLobApiKey"> Save </button>
                               </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

