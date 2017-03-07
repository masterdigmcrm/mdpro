<style>
    .img-container { position: relative; width: 100px; height: 100px; float: left; margin-left: 10px; }
    .img-checkbox { position: absolute; bottom: 0px; right: 0px; }
</style>
<div id="sendPostcardModal" class="modal fade" role="dialog">
    <form id="postcardForm">
        <div class="modal-dialog modal-lg">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div style="padding:12px">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"> Send Postcard To {{sending_to}} </h4>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="col-lg-6 col-sm-6">
                                <input type="radio" class="img-cb" id="check1" name="postcard" value="1" />
                                <img src="<?php echo Url('/public/images/postcards/p1_thumb.png') ?>" class="img-responsive" />
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <input type="radio" class="img-cb" id="check2" name="postcard" value="2" />
                                <img src="<?php echo Url('/public/images/postcards/p2_thumb.png') ?>" class="img-responsive" />
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <input type="radio" class="img-cb" id="check2" name="postcard" value="3" />
                                <img src="<?php echo Url('/public/images/postcards/p3_thumb.png') ?>" class="img-responsive" />
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <input type="radio" class="img-cb" id="check2" name="postcard" value="4" />
                                <img src="<?php echo Url('/public/images/postcards/p4_thumb.png') ?>" class="img-responsive" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label for="message">Message</label>
                                <?php echo \Form::textarea( 'message' , \Request::get('message') , [ 'style'=>'height:120px', 'class' => 'form-control' , 'id'=>'message' ] ) ?>
                            </div>
                            <div class="form-group">
                                <label for="text1">Text 1</label>
                                <?php echo \Form::text( 'text1' , \Request::get('text1') , [ 'class' => 'form-control' , 'id'=>'text1' ] ) ?>
                            </div>
                            <div class="form-group">
                                <label for="text2">Text 2</label>
                                <?php echo \Form::text( 'text2' , \Request::get('text2') , [ 'class' => 'form-control' , 'id'=>'text2' ] ) ?>
                            </div>
                            <div class="form-group">
                                <label for="text3">Text 3</label>
                                <?php echo \Form::text( 'text3' , \Request::get('text3') , [ 'class' => 'form-control' , 'id'=>'text3' ] ) ?>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="clearfix"></div>
                    <div class="pull-right">
                        Sending to {{postcard_lead_count}} Leads
                    </div>
                    <button type="button" class="btn btn-success" v-on:click="sendPostcard()">Send</button>
                    <button type="button" class="btn btn-primary" v-on:click="preview()">Preview</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                </div>
                <div class="portlet-footer">
                    <br /><br />
                </div>
            </div>
        </div>
        <input type="hidden" name="lead-index" id="lead-index" value="-1" />
        <input type="hidden" name="send_to_leads" id="send_to_leads" value="" />
    </form>
</div>