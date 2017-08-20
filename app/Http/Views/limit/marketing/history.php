<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
<div id="hDiv" v-cloak>

    <div class="row">
        <div class="col-lg-12">
            <form id="sForm">
            <div class="panel panel-white">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="from">From</label>
                                <input type="text" name="from" value="" id="from" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="to">To</label>
                                <input type="text" name="to" value="" id="to" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">

                                <label for="q"> Lead Name </label>
                                <input type="text" name="q" value="" id="lead_name" class="form-control" />
                                <i v-show="searching_lead" class="fa fa-spin fa-refresh" style="position:relative;top:-26px;left:90%"></i>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="s"> &nbsp; </label> <Br />
                                <a href="javascript:" class="btn btn-success" @click="search" :class=" loading ? 'disabled' : ''"
                                   v-html="loading ? spinner : 'Search' ">
                                </a><br />
                                <a href="javascript:" @click=" more_search_options = !more_search_options ">{{ more_search_options ? 'Less Options' : 'More Options' }}</a>
                                <input type="hidden" name="leadid" id="leadid" value="" />
                            </div>

                        </div>
                    </div>
                    <div class="row" v-show="more_search_options">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""> Status </label>
                                <select name="status" class="form-control">
                                    <option value="0"> All </option>
                                    <option value="sent"> Sent </option>
                                    <option value="onqueue"> On Queue </option>
                                    <option value="cancelled"> Canceled </option>
                                    <option value="failed"> Failed </option>
                                </select>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""> Type </label>
                                <select name="action_typeid" class="form-control">
                                    <option value="0"> All </option>
                                    <option value="3"> Letters </option>
                                    <option value="6"> Postcards </option>
                                    <option value="1"> Emails </option>
                                    <option value="2"> To do </option>
                                    <option value="4"> One Off </option>
                                    <option value="5"> Newsletter </option>
                                </select>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
                <input type="hidden" name="page" id="page" value="" v-model="current_page" />
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h4><b>Marketing History</b></h4>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tr>
                            <th> Lead </th>
                            <th> Action </th>
                            <th> Type </th>
                            <th> Sending Date </th>
                            <th> Status </th>
                        </tr>
                        <tr v-show="!loading && !entries.length">
                            <td colspan="5"><b>No record found</b></td>
                        </tr>
                        <tr v-for="h in entries">
                            <td> {{ h.last_name+', '+h.first_name }}</td>
                            <td> {{ h.subject }} </td>
                            <td> {{ h.type }} </td>
                            <td> {{ h.date_sending_sched }} </td>
                            <td> {{ h.status }}</td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer" style="padding:12px">
                    <ul class="pagination" v-show="page_count.length > 1">
                        <li>
                            <a href="javascript:" aria-label="Previous" @click="prev()">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="hidden-xs" v-for="p in page_count" :class="p==current_page ? 'active' : '' ">
                            <a href="javascript:" @click="goToPage(p)">{{p}}</a>
                        </li>
                        <li v-show="current_page < total_pages ">
                            <a href="javascript:" aria-label="Next" @click="next()">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

