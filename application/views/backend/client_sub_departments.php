
<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-users"></i> <?php echo ucwords(str_replace('_', ' ', $client->organization)); ?>'s <?php echo ucwords($dept_name) ?></em> Sub-departments</div>
                                    <div class="card-tools">
                                    	<button class="btn btn-primary btn-xs" onclick="add_sub_depts()"><i class="fa fa-plus-circle"></i> Add Sub-departments</button>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                  								<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                  									<thead>
                  										<th>#</th>
                  										<th>Sub-department Name</th>
                  										<th  class="noExport">Actions</th>
                  									</thead>
                  									<tbody>
                  										<?php $i = 0; foreach ($sub_departments as $sub_dept) { ?>
                  										<tr>
                  											<td><?php echo ++$i; ?></td>
                  											<td><?php echo ucwords($sub_dept->sub_dept_name);?></td>
                  											<td>
                  												<button class="btn btn-warning btn-xs" onclick="edit_sub_dept(<?php echo $sub_dept->sub_dept_id;?>)" title = "Edit sub-department details"><i class="fa fa-edit"></i></button> | 
                  												<button class="btn btn-danger btn-xs" onclick="delete_sub_dept(<?php echo $sub_dept->sub_dept_id;?>)" title = "Delete sub-department"><i class="fa fa-trash"></i></button>
                  												
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
        $("#subDeptSave, #subDeptUpdate").click();
        return false;
    }
});

function remove(){
	$('#remove').parent().parent().parent().remove();
}

function add_sub_depts(){
	save_method = 'add';
    $('#sub_dept_add')[0].reset();
    $('#add_msg').html(''); 
    $('#add_subDeptsModal').modal('show'); 
}

function edit_sub_dept(id){
	save_method = 'update';
	$('#edit_msg').html(''); 
    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_sub_dept')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="sub_dept_id"]').val(data.sub_dept.sub_dept_id);
                $('[name="sub_dept_name"]').val(data.sub_dept.sub_dept_name);
                $('#subDeptModal').modal('show'); 
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

function update_sub_dept(){ 
	$('#edit_msg').html(''); 
	var formdata = new FormData($('#sub_dept_edit')[0]);  
	$.ajax({
	    url : "<?php echo site_url('backend/admin/update_sub_dept')?>",
	    type: "POST",
	    data: formdata,
	    dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
	    success: function(data)
	    {
	        if(data.status){
	            $('#subDeptModal').modal('hide');
	            alert(data.msg);
	            location.reload();
	        }
	        else{
	            $('#edit_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
	        }
	    },
	    error: function (jqXHR, textStatus, errorThrown)
	    {
	        alert('Error updating department'); 
	    }
	});
}

function save_sub_dept(){ 
	$('#add_msg').html('');   
	$.ajax({
	    url : "<?php echo site_url('backend/admin/add_sub_dept')?>",
	    type: "POST",
	    data: $('#sub_dept_add').serialize(),
	    dataType: "JSON",
	    success: function(data)
	    {
	        if(data.status){
	            $('#add_subDeptsModal').modal('hide');
	            alert(data.msg);
	            location.reload();
	        }
	        else{
	            $('#add_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
	        }
	    },
	    error: function (jqXHR, textStatus, errorThrown)
	    {
	        alert('Error saving sub-department'); 
	    }
	});
}

function delete_sub_dept(id){
    if(confirm('Are you sure you want to delete this sub-department?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_sub_dept')?>/"+id,
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
                alert('Error deleting sub-department');
            }
        });
    }
}
</script>

<!-- Add Departments Modal-->
    <div class="modal fade" id="add_subDeptsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h5 class="modal-title" id="exampleModalLabel">Add Sub-Department(s) for <em class="text-muted"><?php echo ucwords(str_replace('_', ' ', $client->organization)); ?></em> <span class="btn btn-default btn-xs" style="margin-left: 40px;" id="more_sub_dept"><i class="fa fa-plus-circle"></i> Add More</span></h5>
          </div>
          <div class="modal-body form">
          	<div id="add_msg"></div>
          	<?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'sub_dept_add', 'method' => 'POST']);?>
          		<input type="hidden" value="<?php echo $dept_id?>" name="dept_id"/> 
			    <div class="form-group" id="append_sub_dept" style="margin: 10px 0;">
			    	<div class="col-md-6">
				    	<input type="text" name="sub_department[]" class="form-control input-sm" placeholder="Sub-department" />
				    </div>
			    </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-secondary btn-sm" id="subDeptSave" onclick="save_sub_dept()"><i class="fa fa-save"></i> Save Sub-department(s)</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Department Modal-->
    <div class="modal fade" id="subDeptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h5 class="modal-title" id="exampleModalLabel">Edit Sub-department</h5>
          </div>
          <div class="modal-body form">
          	<div id="edit_msg"></div>
          	<?php echo form_open_multipart('#', ['class' => 'form-horizontal', 'id' => 'sub_dept_edit', 'method' => 'POST']);?>
          		<input type="hidden" value="" name="sub_dept_id"/> 
		        <div class="form-group">
		        	<div class="col-md-4">
	                <label class="control-label">Sub-department: </label>
		            </div>
		            <div class="col-md-8">
	                <input name="sub_dept_name" placeholder="Sub-department Name" class="form-control input-sm" type="text" />
		            </div>
		        </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-xs" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-secondary btn-xs" id="subDeptUpdate" onclick="update_sub_dept()"><i class="fa fa-refresh"></i> Update Sub-department</button>
          </div>
        </div>
      </div>
    </div>