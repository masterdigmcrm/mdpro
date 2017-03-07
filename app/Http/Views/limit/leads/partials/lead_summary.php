<div id="leadSummary" class="vDiv hide">
    <div class="panel panel-flat">
        <div class="panel-heading" style="padding: 8px">
            <div class="col-lg-6">
            </div>
            <div class="col-lg-6" style="text-align: right">
                <div class="" style="margin:4px 0px 0px 0px">
                    <a href="javascript:" v-on:click="deletelead( lead.leadid )" class="btn btn-default">
                        <i class="fa fa-trash-o font-white"></i>
                    </a>
                    <a href="javascript:" v-on:click="editleadsummary( lead.leadid )" class="btn btn-default">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="javascript:" class="btn btn-default" @click="closeSummary">
                        <i class="fa fa-close"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-5 col-sm-12">
                    <h3><b>{{lead.full_name}}</b></h3>
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
            </div>
            <hr />
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-flat">
                        <div class="panel-heading" style="padding: 8px">
                            <h5 class="panel-title">Notes</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                    <li><a data-action="close"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="task-list" style="list-style: none">
                                <li v-for="n in notes" style="padding:12px;border-bottom: 1px #EFEFEF solid ">

                                    <div class="note" id="note">{{n.note}}</div>
                                    <div>{{ n.date_added }}</div>
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
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-flat">
                        <div class="panel-heading" style="padding: 8px">
                            <h5 class="panel-title">ToDos</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                    <li><a data-action="close"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="task-list" style="list-style: none">
                                <li v-for="t in todos" style="padding:12px;border-bottom: 1px #EFEFEF solid ">

                                    <div class="todo" id="todo">{{t.todo}}</div>
                                    <div>{{t.start_date}}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>