<div id="editBucketModal" class="modal fade large" role="dialog" >
    <form id="bucketForm">
        <div class="modal-dialog">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div style="padding:12px">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"> Bucket </h4>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label for="first_name">Bucket Name</label>
                                <?php echo \Form::text( 'bucket_name' , '' , [ 'class' => 'form-control' , 'id'=>'bucket_name' ] ) ?>
                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="clearfix"></div>
                    <br /><br />
                    <button type="button" class="btn btn-success" v-on:click="savelead()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <br /><br />

                </div>
            </div>
        </div>
        <input type="hidden" name="lead-index" id="lead-index" value="-1" />
    </form>
</div>