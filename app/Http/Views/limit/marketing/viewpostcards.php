<div id="pDiv">
    <div>
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-white">

                    <div class="panel-body">
                        <div class="pull-right">
                            <button class="btn btn-success btn-sm" @click="openPostcardModal"> Add Postcard </button>
                        </div>
                        <h4><b>Postcards</b></h4>
                        <table class="table table-stripped">
                            <tr>
                                <th> &nbsp;</th>
                                <th style="width:20%"> Front </th>
                                <th style="width:20%;"> Back </th>
                                <th></th>
                            </tr>
                            <tr v-for="p in postcards">
                                <td style="vertical-align: top">  {{ p.postcard_name }}</td>
                                <td> <img src="" class="img-responsive" :src="p.front ? p.front : '/images/placeholder.jpg'" /></td>
                                <td> <img src="" class="img-responsive" :src="p.back ? p.back : '/images/placeholder.jpg'" /> </td>
                                <td style="text-align: right">
                                    <a href="javascript:" class="btn btn-default" @click="editPostcard( p.postcard_id)"> <i class="fa fa-edit"></i> </a>
                                    <a href="javascript:" class="btn btn-default" @click="deletePostcard( p.postcard_id)"> <i class="fa fa-trash" style="color:red"></i> </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="postcardNameModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="postcardModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <div style="float:right"> <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"> X </button> </div>

                    <h4 class="modal-title"><b>Postcard</b></h4>
                    <hr />
                </div>
                <div class="modal-body">
                    <form id="pForm">
                    <div class="row">
                        <div class="form-group col-lg-8" >
                            <div><label for="dimension">Postcard Name:</label></div>
                            <div class="input-group">
                                 <input type="text" v-model="postcard.postcard_name" placeholder="Postcard Name" name="postcard_name" value="" id="postcard_name" class="form-control" />
                                 <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" @click="savePostcard"> Save </button>
                                  </span>
                            </div>
                            <div class="form-group">
                                <label for="dimension">Postcard Size:</label>
                                <select name="dimension" class="form-control" v-model="postcard.dimension">
                                    <option value="1275-1875"> 4.25" x 6.25" </option>
                                    <option value="1875-2775"> 6.25" x 9.25" </option>
                                    <option value="1875-3375"> 6.25" x 11.25" </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-show="postcard.postcard_id">
                        <div class="row">
                            <div class="col-lg-12" style="text-align:right">
                                 <i class="fa fa-question"></i> Tip
                            </div>
                            <div class="col-lg-12">
                                <div class="alert alert-info" id="p_info">
                                    When using PNGs or JPEGs for postcards, we require a minimum of 300 dpi.
                                    The dpi is calculated as (width of image in pixels) / (width of product in inches) and
                                    (length of image in pixels) / (length of product in inches).
                                    Right now we support the following image sizes:
                                    <br /> 1275px x 1875px for a 4.25" x 6.25" postcard with a dpi of 300.
                                    <br /> 1875px x 2775px for a 6.25"x9.25" postcard with a dpi of 300. ( US Only )
                                    <br /> 1875px x 3375px for a 6.25"x11.25" postcard with a dpi of 300. ( US Only )

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <h4><b>Front</b></h4>
                                            </div>
                                            <div class="col-lg-8" style="text-align: right">
                                                <label class="btn btn-success btn-file">
                                                    <i class="fa fa-upload"></i> Upload Front Design
                                                    <input style="display: none; color: transparent;" id="frontUpload" type="file" name="postcard_front" class="file-input"
                                                           data-url="<?php echo Url('/ajax/postcards/upload/front') ?>">
                                                </label>
                                                <div v-show="progress_front">
                                                    <div class="bar" style="height: 8px;background-color: green; margin-top:12px"> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <img src="" class="img-responsive" :src="frontPostcardSrc" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <h4><b>Back</b></h4>
                                            </div>
                                            <div class="col-lg-8" style="text-align: right">
                                                <label class="btn btn-success btn-file">
                                                    <i class="fa fa-upload"></i> Upload Back Design
                                                    <input style="display: none; color: transparent;" id="backUpload" type="file" name="postcard_back" class="file-input"
                                                           data-url="<?php echo Url('/ajax/postcards/upload/back') ?>">
                                                </label>
                                                <div v-show="progress_back">
                                                    <div class="bar_back" style="height: 8px;background-color: green; margin-top:12px"> </span> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <img src="" class="img-responsive" :src="backPostcardSrc" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo csrf_field() ?>
                    <input type="hidden" name="postcard_id" id="postcard_id" value="" :value="postcard.postcard_id" />
                    <input type="hidden" name="account_id" id="account_id" value="<?php echo \App\Models\Accounts\AccountEntity::me()->brokerid ?>" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

