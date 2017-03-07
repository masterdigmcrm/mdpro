<div id="addTodoModal" class="modal fade large" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="panel-body">
                <form id="addTodoForm2">
                    <div class="form-group">
                        <label for="todo">Todo</label>
                        <textarea id="todoTextLeadSummary" class="form-control todoText" style="width:100%"></textarea>
                    </div>
                    <div class="input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                        <label for="todoDate">Date</label>
                        <input class="span2 form-control" style="position: relative; z-index: 100000;" id="todoDateLeadSummary" size="16" type="text" value="12-02-2015">
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                </form>
                <button type="button" class="btn btn-success" id="add-todo-button" v-on:click="addTodo( lead.leadid )"><?php echo trans( 'leads.add todo' ) ?></button>
            </div>
        </div>
    </div>
</div>