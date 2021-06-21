<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-map-marker"></i> States Management</div>
                                    <div class="card-tools">
                                        <button class="btn btn-primary btn-xs" onclick="add_state()"><i class="fa fa-plus-circle"></i> Add State</button>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                				<div class="table-responsive">
                				<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                					<thead>
                						<th>#</th>
                						<th>State Name</th>
                						<th>State Code</th>
                						<th  class="noExport">Actions</th>
                					</thead>
                					<tbody>
                						<?php $i = 0; foreach ($states as $state) {?>
                						<tr>
                							<td><?php echo ++$i; ?></td>
                							<td><?php echo ucwords($state->state_name);?></td>
                							<td><?php echo ucwords($state->state_code);?></td>
                							<td>
                								<button class="btn btn-warning btn-xs" onclick="edit_state(<?php echo $state->state_id;?>)" title = "Edit state details"><i class="fa fa-edit"></i></button> |
                								<button class="btn btn-danger btn-xs" onclick="delete_state(<?php echo $state->state_id;?>)" title = "Delete state"><i class="fa fa-trash"></i></button>
                							</td>
                						</tr>
                						<?php } ?>
                					</tbody>
                				</table>
                				</div>
			                 </div><!-- card-body -->
		              </div><!-- card -->
                  </div>
		  </div>
	</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
function add_state(){
	save_method = 'add';
    $('#new_state')[0].reset();
    $('#msg').html(''); 
    $('#stateModal').modal('show'); 
    $('.modal-title').text('Add State Type'); 
}

function edit_state(id){
    save_method = 'update';
    $('#new_state')[0].reset(); 
    $('#msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_state')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.state.state_id);
                $('[name="stateName"]').val(data.state.state_name);
                $('[name="stateCode"]').val(data.state.state_code);

                $('#stateModal').modal('show'); 
                $('#stateSave').text('Update State');
                $('.modal-title').text('Edit State');
            }else{
                alert(data.msg);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save(){
    $('#stateSave').text('saving...'); 
    $('#stateSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_state')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_state')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_state').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#stateModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#stateSave').text('Save State'); 
                $('#stateSave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#stateSave').text('Save State'); 
                $('#stateSave').attr('disabled',false);  
            }
        });
}

function delete_state(id){
    if(confirm('Are you sure you want to delete this data?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_state')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                if (data.status) {
                    alert(data.msg);
                    location.reload();
                } else{
                    alert(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
    }
}
</script>

<!-- New State Modal-->
    <div class="modal fade" id="stateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add State Type</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="msg"></div>
          	<?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_state', 'method' => 'POST']);?>
          	<input type="hidden" value="" name="id"/> 
		        <div class="form-group">
                    <div class="row">
    		        	<div class="col-md-6">
    	                <input name="stateName" placeholder="State Name" class="form-control" type="text" />
    		            </div>
    		            <div class="col-md-6">
    	                <input name="stateCode" placeholder="State Code" class="form-control" type="text" />
    		            </div>
                    </div>
		        </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-success btn-sm" id="stateSave" onclick="save()"><i class="fa fa-save"></i> Save State</button>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>