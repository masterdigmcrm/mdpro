
<div id="leads_div">
    <div class="panel panel-flat vDiv" id="leadList">
        <div class="panel-body">
            <form id="leadsForm">
                <?php echo view('leads.modals.advanced_search')
                    ->with( 'account' , $account )
                    ->render(); ?>
                <input type="hidden" name="page" id="page" value="1" />
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
                                <span style="font-weight:normal"> {{ displayed_lead_count }} of {{ lead_count}} </span>
                                <a id="show-more" href="javascript:;" v-on:click="showMoreLeads()" class="btn btn-sm btn-success hide show-more">
                                    More
                                </a>
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
                                        <li>
                                            <a href="javascript:" class="add-postcard"  v-on:click="selectPostcard"><?php echo trans('leads.send postcard') ?></a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="add-bucket"  v-on:click="addToGroup()"><?php echo trans('leads.add to group') ?></a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="delete_leads"  v-on:click="deleteLeads()"> <?php echo trans('leads.delete leads') ?> </a>
                                        </li>
                                        <li>
                                            Options
                                        </li>
                                        <li class="divider">
                                        </li>
                                        <li>
                                            <a href="javascript:" v-on:click="createLeadGroup()"><?php echo trans('leads.create lead groups') ?> </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo Url('leads/groups') ?>"><?php echo trans('leads.view lead groups') ?> </a>
                                        </li>
                                    </ul>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tr v-show="!leads.length">
                            <td colspan="2">
                                <div id="loader">
                                    No leads found
                                </div>
                            </td>
                        </tr>
                        <tbody>
                            <tr v-for="(lead , index) in leads">
                            <td>
                                <label>
                                    <input type="checkbox" class="checkbox-master cb" name="cb[]" value=""  />
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle"
                                            data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li>
                                            <a href="#" class="lead-summary" v-on:click="leadsummary( index, lead.leadid)"> <?php echo trans('leads.summary') ?> </a>
                                        </li>
                                        <li>
                                            <a href="#" class="lead-delete" @click="deletelead( lead.leadid )"> <?php echo trans('leads.delete') ?> </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="add-note"  v-on:click="editlead( lead.leadid )"><?php echo trans('leads.edit lead') ?> </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="add-note"  v-on:click="editnote(-1 , lead.leadid)"> <?php echo trans('leads.add notes') ?> </a>
                                        </li>

                                        <li class="divider">
                                        </li>
                                        <li>
                                            <a href="javascript:" v-on:click="call( -1 , lead.leadid)"><?php echo trans('leads.call') ?> </a>
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
    <?php //echo view('leads.modals.lead_group_create')->render(); ?>
    <?php //echo view('leads.modals.lead_group_list')->render(); ?>
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