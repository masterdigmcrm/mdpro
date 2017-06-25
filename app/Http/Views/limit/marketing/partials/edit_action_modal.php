<div id="editActionModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><b>Campaign Action</b></h4>
            </div>
            <form id="actionForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" name="subject" value="" id="subject" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="type">Action Type</label>
                                <select name="action_typeid" class="form-control" v-model="action_typeid" @change="actionTypeSelected">
                                    <option value="1" > Email </option>
                                    <option value="3" > Letter </option>
                                    <option value="6" > Postcard </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <label> Send After / On </label>
                                            <select name="sending_delay" class="form-control" @change="actionDelaySelected">
                                                <option value="0"> Same Day </option>
                                                <option value="-1"> Specific Date </option>
                                                <option value="" v-for="n in 30" :value="n"> {{n}} Days </option>
                                                <option value="60"> Two Months </option>
                                                <option value="90"> Three Months </option>
                                                <option value="180"> Six Months </option>
                                                <option value="365"> One Year </option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div v-show="action_sending_delay == -1">
                                                <div class="col-lg-4">
                                                    <select name="send_month" class="form-control" style="display: inline-table;">
                                                        <option value="00">Month</option>
                                                        <option value="01">January</option>
                                                        <option value="02">February</option>
                                                        <option value="03">March</option>
                                                        <option value="04">April</option>
                                                        <option value="05">May</option>
                                                        <option value="06">June</option>
                                                        <option value="07">July</option>
                                                        <option value="08">August</option>
                                                        <option value="09">September</option>
                                                        <option value="10">October</option>
                                                        <option value="11">November</option>
                                                        <option value="12">December</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <select name="send_day" class="form-control form-inline">
                                                        <option value="00">Day</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                        <option value="13">13</option>
                                                        <option value="14">14</option>
                                                        <option value="15">15</option>
                                                        <option value="16">16</option>
                                                        <option value="17">17</option>
                                                        <option value="18">18</option>
                                                        <option value="19">19</option>
                                                        <option value="20">20</option>
                                                        <option value="21">21</option>
                                                        <option value="22">22</option>
                                                        <option value="23">23</option>
                                                        <option value="24">24</option>
                                                        <option value="25">25</option>
                                                        <option value="26">26</option>
                                                        <option value="27">27</option>
                                                        <option value="28">28</option>
                                                        <option value="29">29</option>
                                                        <option value="30">30</option>
                                                        <option value="31">31</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <select name="send_year" class="form-control form-inline">
                                                        <option value="0000">Year</option>
                                                        <option value="2017">2017</option>
                                                        <option value="2018">2018</option>
                                                        <option value="2019">2019</option>
                                                        <option value="2020">2020</option>
                                                        <option value="2021">2021</option>
                                                        <option value="2022">2022</option>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-show = "action_typeid == 1 || action_typeid == 3">
                        <textarea name="editor1" id="editor1" rows="10" cols="80"></textarea>
                    </div>
                    <div v-show="action_typeid == 6">
                        <h5> <b>Choose A Postcard</b></h5>
                        <table class="table table-striped">
                            <tr v-for="p in postcards">
                                <td style="width:32px">
                                    <input type="radio" name="postcard_id" value="" :value="p.postcard_id" v-model="postcard_id"/>
                                </td>
                                <td>{{ p.postcard_name }}</td>
                                <td>
                                    <div class="pull-right">
                                        <a href="javascript:" class="btn btn-success"> Preview </a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div v-show=" ! loading_postcards && ! postcards.length ">
                            No postcard found
                        </div>
                        <div v-show="loading_postcards">
                            <i class="icon-spinner2 spinner"></i> Loading postcards
                        </div>
                    </div>
                </div>
                <?php echo csrf_field() ?>
                <input type="hidden" name="campaignid" id="campaignid" value="" v-model="campaign.campaignid"/>
                <input type="hidden" name="template_name" id="template_name" value="" />
                <input type="hidden" name="message" id="message" value=""  />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="saveAction">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

