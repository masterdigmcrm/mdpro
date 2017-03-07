<div id="leadSummaryModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="">
                    <div class="portlet-title">
                        <div class="col-lg-6">
                        </div>
                        <div class="col-lg-6" style="text-align: right">
                            <div class="" style="margin:8px 0px 0px 0px">
                                <a href="javascript:" v-on:click="deletelead( lead.leadid )" class="btn btn-default">
                                    <i class="fa fa-trash-o font-white"></i>
                                </a>
                                <a href="javascript:" v-on:click="editleadsummary( lead.leadid )" class="btn btn-default">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:" data-dismiss="modal" aria-hidden="true" style="margin:0px"
                                   class="btn btn-default">
                                    <i class="fa fa-close"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="col-lg-5 col-sm-12">
                            <h3>{{lead.full_name}}</h3>
                            {{ lead.status }} | {{ lead.type }} | {{lead.source}}
                            <br /> ID: {{lead.leadid}}
                            <br />
                            <!--
                            <span v-bind:class="[ contact.facebook ? 'inline' : 'hide' ]"><a href="{{contact.facebook}}" target="_blank"><img src="<?php //echo Helpers\Html::iconUrl( 'facebook' ) ?>" style="width:24px"/></a></span>
                            <span v-bind:class="[ contact.twitter ? 'inline' : 'hide' ]"><a href="{{contact.twitter}}" target="_blank"><img src="<?php //echo Helpers\Html::iconUrl( 'twitter' ) ?>" style="width:24px"/></a></span>
                            <span v-bind:class="[ contact.linkedin ? 'inline' : 'hide' ]"><a href="{{contact.linkedin}}" target="_blank"><img src="<?php //echo Helpers\Html::iconUrl( 'linkedin' ) ?>" style="width:24px"/></a></span>
                            <span v-bind:class="[ contact.pinterest ? 'inline' : 'hide' ]"><a href="{{contact.pinterest}}" target="_blank"><img src="<?php //echo Helpers\Html::iconUrl( 'pinterest' ) ?>" style="width:24px"/></a></span>
                            <span v-bind:class="[ contact.instagram ? 'inline' : 'hide' ]"><a href="{{contact.instagram}}" target="_blank"><img src="<?php //echo Helpers\Html::iconUrl( 'instagram' ) ?>" style="width:24px"/></a></span>
                            -->
                        </div>
                        <div class="col-lg-7 col-sm-12">
                            <br />
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td style="width:35%">Phone: </td>
                                    <td v-html="lead.phone_full">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email: </td>
                                    <td>{{lead.email1}}</td>
                                </tr>
                                <tr>
                                    <td>Since: </td>
                                    <td>{{lead.since}}</td>
                                </tr>
                                <tr>
                                    <td>Last Modified: </td>
                                    <td>{{lead.last_modified}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row" style="padding:4px">
                            <div class="panel-group accordion" id="accordion1">
                                <!-- Begin Notes accordion -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-lg-8 col-xs-6" style="">
                                                <h5 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1" aria-expanded="false">
                                                        Notes
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="col-lg-4  col-xs-6" style="text-align:right">
                                                <button class="btn btn-primary btn-sm" v-on:click="editnote(-1 , lead.leadid )"><i class="fa fa-plus"></i> </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapse_1" class="panel-collapse collapse in" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="scroller" style="" data-always-visible="1" data-rail-visible1="1">
                                                <!-- START TASK LIST -->
                                                <ul class="task-list" style="list-style: none">
                                                    <li v-for="n in notes" style="padding:12px;border-bottom: 1px #EFEFEF solid ">
                                                        <div>{{ n.date_added }}</div>
                                                        <div class="note" id="note">{{n.note}}</div>
                                                        <div>
                                                            <a href="#"  v-on:click="editnote( $index , lead.leadid )">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a href="javascript:void(0);"  v-on:click="deletenote($index , n.noteid )" >
                                                                <i class="fa fa-trash-o"></i>
                                                            </a>
                                                        </div>

                                                    </li>
                                                </ul>

                                                <!-- END START TASK LIST -->
                                            </div>
                                            <!--<div class="loader"><i class="fa fa-refresh fa-spin"></i> </div>-->
                                        </div>
                                    </div>
                                </div>
                                <!-- End Notes accordion -->
                                <!-- Start TODO accordion -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_3" aria-expanded="false">
                                                To do
                                            </a>
                                        </h4>
                                        <div class="col-lg-6" style="text-align: center">
                                            <button class="btn btn-primary btn-sm" v-on:click="edittodo(-1 , lead.leadid )"><i class="fa fa-plus"></i> </button>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="panel-body">
                                            <div class="col-lg-12">
                                                <ul class="task-list" style="list-style: none">
                                                    <li v-for="t in todos" style="padding:12px;border-bottom: 1px #EFEFEF solid ">
                                                        <div>{{t.start_date}}</div>
                                                        <div class="todo" id="todo">{{t.todo}}</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End ToDo accordion -->

                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>