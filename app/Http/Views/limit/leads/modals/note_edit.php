<div id="editNoteModal" class="modal fade large" role="dialog">
    <div class="modal-dialog">
        <form>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header green">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="addEditNote">{{addEditNote}}</span> Note</h4>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <textarea id="note-text" name="note" style="width:100%;height:240px"></textarea>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" v-on:click="savenote()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <input type="hidden" name="note-index" id="note-index" value="" />
            <input type="hidden" name="noteid" id="noteid" value="" />
            <input type="hidden" name="note_leadid" id="note_leadid" value="" />
        </form>
    </div>
</div>