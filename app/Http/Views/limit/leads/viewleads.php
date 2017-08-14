<div id="leads_div" v-cloak>
    <div class="panel panel-flat vDiv" id="leadList">
        <div class="panel-body">
            <form id="leadsForm">
                <?php echo view('leads.modals.advanced_search')
                    ->with( 'account' , $account )
                    ->render(); ?>
                <input type="hidden" name="page" id="page" value="1" v-model="page"/>
                <div class="col-lg-12">
                    <header id="header-sec">
                        <div class="col-lg-6 col-md-12">
                            <h2><?php echo trans('leads.leads') ?></h2>
                        </div>
                        <div  class="col-lg-6 col-md-12">
                            <div class="input-group">
                                <input class="form-control" type="text" name="q" id="q" placeholder="First Name, Last Name, Email" />
                                <span class="input-group-btn">
                                    <button id="search-btn" class="btn btn-primary search-btn" v-on:click="search()" type="button">
                                        <i class="fa fa-search"></i> <?php echo trans('leads.search') ?>
                                    </button>
                                </span>
                            </div>
                            <a href="javascript:;" v-on:click="advSearch()"><span>Advanced Search</span></a>
                        </div>
                    </header><!-- End #header-sec -->
                    <br />
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="blue">
                            <th style="width:32px">
                                <label><input type="checkbox" class="" id="cb-toggle"/><span></span></label>
                            </th>
                            <th scope="col">
                                <div class="col-lg-8">
                                    <ul class="pagination">
                                        <li>
                                            <a href="javascript:" aria-label="Previous" @click="prev()">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li class="" v-for="p in page_count" :class="p==page ? 'active' : '' ">
                                            <a href="javascript:" @click="goToPage(p)">{{p}}</a>
                                        </li>
                                        <li v-show="page < totalPages ">
                                            <a href="javascript:" aria-label="Next" @click="next()">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <br />
                                <span v-show="!loading" style="font-weight:normal"> &nbsp;&nbsp;&nbsp; {{ displayed_lead_count }} of {{ lead_count}} </span>

                                </div>
                                <div class="col-lg-4">
                                    <div class="btn-group pull-right">
                                    <a href="javascript:" class="btn btn-primary btn-sm add-new" @click="editlead( 0 )"> <i class="fa fa-plus"></i> <?php echo trans('leads.add new') ?> </a>

                                    <button type="button" class="btn btn-sm btn-default btn-fit-height dropdown-toggle"
                                            data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu" style="padding:12px">
                                        <li>
                                            With Selected
                                        </li>
                                        <!--
                                        <li>
                                            <a href="javascript:" class="add-postcard"  v-on:click="selectPostcard"><?php echo trans('leads.send postcard') ?></a>
                                        </li>
                                        -->
                                        <li><a href="javascript:" class="add-bucket"  v-on:click="addToGroup()"><?php echo trans('leads.add to group') ?></a></li>
                                        <li><a href="javascript:" class="delete_leads"  v-on:click="deleteLeads()"> <?php echo trans('leads.delete leads') ?> </a></li>

                                        <li>
                                            Options
                                        </li>
                                        <li class="divider">
                                        </li>
                                        <li>
                                            <a href="javascript:" v-on:click="createLeadGroup()"><?php echo trans('leads.create lead groups') ?> </a>
                                        </li>
                                        <li>
                                            <!--<a href="<?php echo Url('leads/groups') ?>"><?php echo trans('leads.view lead groups') ?> </a>-->
                                            <a href="javascript:" @click="openGroupList"><?php echo trans('leads.view lead groups') ?> </a>
                                        </li>
                                    </ul>
                                </div>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tr class="loader" v-show="loading">
                            <td colspan="2">
                                <i class="icon-spinner2 spinner"></i> <b>Searching leads...</b>
                            </td>

                        </tr>
                        <tbody>
                            <tr v-for="(lead , index) in sortedLeads">
                            <td>
                                <label>
                                    <input type="checkbox" class="checkbox-master cb" name="cb[]" value="" :value="lead.leadid" />
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <!--<li><a href="javascript:"><i class="icon-file-text2"></i> Edit Action</a></li>-->
                                        <li>
                                            <a href="#" class="lead-summary" v-on:click="leadsummary( index, lead.leadid)"> <i class="icon-magazine"></i> <?php echo trans('leads.summary') ?> </a>
                                        </li>
                                        <li>
                                             <a href="#" class="lead-delete" @click="deletelead( lead.leadid )"> <i class="icon-cross2 text-danger"></i> Delete </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="add-note"  v-on:click="editlead( lead.leadid )">
                                                <i class="icon-pencil3"></i> <?php echo trans('leads.edit lead') ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="add-note"  v-on:click="editnote(-1 , lead.leadid)">
                                                <i class="icon-pencil7"></i> <?php echo trans('leads.add notes') ?>
                                            </a>
                                        </li>
                                        <li><a href="javascript:" class="add_campaign"  v-on:click="openCampaignListModal( lead.leadid )">
                                                <i class="icon-envelop3"></i> Add to Campaign </a>
                                        </li>

                                        <li class="divider">
                                        </li>
                                        <li>
                                            <a href="javascript:" v-on:click="call( -1 , lead.leadid)"> <i class="icon-phone-plus2"></i> <?php echo trans('leads.call') ?> </a>
                                        </li>
                                    </ul>
                                </div>
                                <div style="cursor:pointer" v-html @click="leadsummary( lead.leadid)">
                                    <b><span>{{lead.full_name}}</span></b><br />
                                    <span>{{lead.status}}</span><br />
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div v-show="!loading && !leads.length">
                        No leads found
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!------------ Lead Summary Modal ----->
    <?php echo view('leads.partials.lead_summary')->render(); ?>
    <!------------ edit Lead Modal ---->
    <?php echo view('leads.partials.partial_lead_edit')->with( 'account' , $account ); ?>
    <!------------ edit notes Modal ---->
    <?php //echo view('leads.modals.note_edit')->render(); ?>
    <!------------ Modal when lead is saved  ---->
    <?php //echo view('leads.modals.next_action')->render(); ?>
    <!------------ Todo Modal --------->
    <?php //echo view('leads.modals.todo')->render(); ?>
    <!------------ Lead Group Modal --------->
    <?php echo view('leads.modals.lead_group_create')->render(); ?>
    <?php echo view('leads.modals.lead_groups')->render(); ?>
    <?php echo view('leads.modals.lead_campaigns')->render(); ?>
    <?php //echo view('leads.modals.postcard_send')->render(); ?>

</div>

<script>
    var tkn = '<?php echo str_random( 32 ) ?>';
    var delete_note_confirm ='<?php echo trans('leads.sure delete note') ?>';
    var delete_lead_confirm ='<?php echo trans('leads.sure delete lead') ?>';
    var note_save_success = '<?php echo trans('leads.note successfully saved') ?> ';
    var postcard_cannot_be_sent = '<?php echo trans('leads.need to select leads') ?> ';
    var need_to_select_leads = '<?php echo trans('leads.need to select leads') ?> ';
</script>