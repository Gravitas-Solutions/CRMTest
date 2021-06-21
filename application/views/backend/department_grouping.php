
<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fas fa-layer-group"></i> <?php echo ucwords(str_replace('_', ' ', $client->organization)); ?>'s</em> Departments Grouping</div>
                                    <div class="card-tools">
                                        <button class="btn btn-primary btn-xs" onclick="add_group()"><i class="fa fa-plus-circle"></i> Add Group</button>   
                                        <a href="<?php echo base_url()?>backend/admin/client_profile/<?php echo $client->client_id;?>" ><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                				<div class="table-responsive">
                					 <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                						<thead>
                							<th>#</th>
                							<th>Group Name</th>
                							<th>Departments</th>
                							<th  class="noExport">Actions</th>
                						</thead>
                						<tbody>
                						<?php $i = 0; foreach ($groups as $group) {?>
                						<tr>
                							<td><?php echo ++$i; ?></td>
                							<td><?php echo ucwords($group->group_name);?></td>

                								<?php $dept = " "; foreach ($client_departments as $department) {?>
                										<?php if ($group->group_id === $department->group_id) {
                											$dept .= ' '.$department->dept_name . ',';
                										} ?>
                										
                								<?php } ?>
                							<td><?php echo rtrim($dept, ',');?></td>
                							<td>
                								<button class="btn btn-warning btn-xs" onclick="edit_group('<?php echo $group->group_name;?>', <?php echo $group->group_id;?>)" title = "Edit Group"><i class="fa fa-edit"></i></button> |
                								<button class="btn btn-danger btn-xs" onclick="delete_group(<?php echo $group->group_id;?>)" title = "Delete department"><i class="fa fa-trash"></i></button>
                								
                							</td>
                						</tr>
                						<?php } ?>
                						</tbody>
                					</table>
                				</div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
            
<?php $this->load->view('templates/includes/footer_start'); ?>
<script>

$(document).keypress(function(e){
    if (e.which == 13){
        $("#deptSave, #deptUpdate").click();
        return false;
    }
});

function remove(){
	$('#remove').parent().parent().parent().remove();
}

function add_group(){
	save_method = 'add';
    $('#group_add')[0].reset();
    $('#add_msg').html(''); 
    $('#add_groupModal').modal('show'); 
}

function edit_group(group, id){
	save_method = 'update';
	$('#edit_msg').html(''); 
    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_group')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="group_name"]').val(group);
                $('[name="group_id"]').val(id);
                $.each(data.dept, function(i, val){
                   $('input:checkbox[name="group[]"][value="' + val.dept_id + '"]').prop('checked', true);
                });
                $('#groupModal').modal('show'); 
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

function update_group(){ 
	$('#edit_msg').html(''); 
	var formdata = new FormData($('#group_edit')[0]);  
	$.ajax({
	    url : "<?php echo site_url('backend/admin/update_group')?>",
	    type: "POST",
	    data: formdata,
	    dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
	    success: function(data)
	    {
	        if(data.status){
	            $('#groupModal').modal('hide');
	            alert(data.msg);
	            location.reload();
	        }
	        else{
	            $('#edit_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
	        }
	    },
	    error: function (jqXHR, textStatus, errorThrown)
	    {
	        alert('Error updating Group'); 
	    }
	});
}

function save_group(){ 
	$('#add_msg').html('');   
	$.ajax({
	    url : "<?php echo site_url('backend/admin/add_group')?>",
	    type: "POST",
	    data: $('#group_add').serialize(),
	    dataType: "JSON",
	    success: function(data)
	    {
	        if(data.status){
	            $('#add_groupModal').modal('hide');
	            alert(data.msg);
	            location.reload();
	        }
	        else{
	            $('#add_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
	        }
	    },
	    error: function (jqXHR, textStatus, errorThrown)
	    {
	        alert('Error saving Group'); 
	    }
	});
}

function delete_group(id){
    if(confirm('Are you sure you want to delete this Group?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_group')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
            	if (data.status) {
            		alert(data.msg);
            		location.reload();
            	} else{
            		alert(data.msg);
            	};
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting group');
            }
        });
    }
}


</script>

<!-- Add Departments Modal-->
    <div class="modal fade" id="add_groupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h5 class="modal-title" id="exampleModalLabel">Add group for <em class="text-muted"><?php echo ucwords(str_replace('_', ' ', $client->organization)); ?></em></h5>
          </div>
          <div class="modal-body form">
	        <div id="add_msg"></div>
	        <?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'group_add', 'method' => 'POST']);?>
	        <input type="hidden" value="<?php echo $client->client_id?>" name="client_id"/> 
	        <input type="hidden" value="" name="group_id"/>     
			<div class="form-group">
                <div class="col-md-6">
                    <label class="control-label">Client Department Group Name: </label>
                </div>
                <div class="col-md-6">
                    <input name="group_name" placeholder="Group Name" class="form-control input-sm" type="text" />
                </div>
            </div> 
            <div class="form-group">
                <h6 class="text-muted"><em>Select Departments to add to the group:</em></h6>                    
                <div class="checkbox-inline">
                <?php foreach ($client_departments as $cd) {?>
                	<?php if(strpos($cd->dept_name, "overview") === false){?>
                		<div class="col-md-6"><label style="white-space: nowrap;"><input type="checkbox" name="group[]" value="<?php echo ucwords($cd->dept_id);?>"><?php echo ucwords($cd->dept_name);?></label></div>
                	<?php }?>
                <?php }?>   
                </div>  
            </div>
			</form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-secondary btn-sm" id="deptSave" onclick="save_group()"><i class="fa fa-save"></i> Save Group</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Department Modal-->
    <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Group</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="edit_msg"></div>
          	<?php echo form_open_multipart('#', ['class' => 'form-horizontal', 'id' => 'group_edit', 'method' => 'POST']);?>
          	<input type="hidden" value="<?php echo $client->client_id?>" name="client_id"/> 
	        <input type="hidden" value="" name="group_id"/>     
			<div class="form-group">
                <div class="col-md-6">
                    <label class="control-label">Client Department Group Name: </label>
                </div>
                <div class="col-md-6">
                    <input name="group_name" placeholder="Group Name" class="form-control input-sm" type="text" />
                </div>
            </div> 
            <div class="form-group">
                <h6 class="text-muted"><em>Select Departments to add to the group:</em></h6>                    
                <div class="checkbox-inline">
                <?php foreach ($client_departments as $cd) {?>
                	<?php if(strpos($cd->dept_name, "overview") === false){?>
                		<div class="col-md-6"><label style="white-space: nowrap;"><input type="checkbox" name="group[]" value="<?php echo ucwords($cd->dept_id);?>"><?php echo ucwords($cd->dept_name);?></label></div>
                	<?php }?>
                <?php }?>   
                </div>  
            </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-secondary btn-sm" id="deptUpdate" onclick="update_group()"><i class="fa fa-refresh"></i> Update Group</button>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>