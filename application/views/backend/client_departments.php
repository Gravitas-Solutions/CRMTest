<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
            <div class="col-md-12">
               <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fas fa-code-branch"></i> <?php echo ucwords(str_replace('_', ' ', $client->organization)); ?>'s</em> Departments
                            </div>
                            <div class="card-tools">
                                <a  onclick="add_depts()" class="btn btn-info btn-border btn-round btn-sm mr-2"><i class="fa fa-plus-circle"></i> Add Departments</a>
                                <a href="<?php echo base_url()?>backend/admin/client_profile/<?php echo $client_id;?>"><i class="fas fa-step-backward fa-1x"></i> </a>
                            </div>
                        </div>
                    </div>
                <div class="card-body">
					<div class="table-responsive">
						 <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
							<thead>
								<th>#</th>
								<th>Department Name</th>
								<th>Logo</th>
								<th  class="noExport">Actions</th>
							</thead>
							<tbody>
								<?php $i = 0;
								foreach ($departments as $dept) { $i++; 
									if (strpos($dept->dept_name, 'overview') !== false) {
										continue;
									}
									?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo ucwords($dept->dept_name);?></td>
									<td><?php echo ($dept->logo) ? "<img src='".base_url()."assets/images/client_logo/".$dept->logo."' style='height: 30px; width: 50px' class='img-thumbnail' />" : "<img src='".base_url()."assets/images/client_logo/".$dept->client_logo."' style='height: 30px; width: 50px' class='img-thumbnail' /> ";?>
									</td>
									<td>
										<button class="btn btn-warning btn-xs" onclick="edit_dept(<?php echo $dept->dept_id;?>)" title = "Edit department details"><i class="fa fa-edit"></i></button> |
										<a class="btn btn-default btn-xs" href="<?php echo base_url()?>backend/admin/sub_depts/<?php echo $dept->dept_id;?>" title = "Sub departments"><i class="fa fa-sitemap"></i></a> | 
										<button class="btn btn-danger btn-xs" onclick="delete_dept(<?php echo $dept->dept_id;?>)" title = "Delete department"><i class="fa fa-trash"></i></button>
										
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
$(document).ready(function(){

			var dept = '<div class="col-md-6" style="margin-top: 2px;"><div class="input-group"><input type="text" name="department[]" class="form-control input-sm"  placeholder="Department" /><span class="input-group-addon"><a class="fa fa-remove text-danger" href="#" id="remove" onclick="remove()"></a></span></div></div>';
			
			$('#more_dept').click(function(e){
				$('#append_dept').append(dept);
			});
});

$(document).keypress(function(e){
    if (e.which == 13){
        $("#deptSave, #deptUpdate").click();
        return false;
    }
});

function remove(){
	$('#remove').parent().parent().parent().remove();
}

function add_depts(){
	save_method = 'add';
    $('#dept_add')[0].reset();
    $('#add_msg').html(''); 
    $('#add_deptsModal').modal('show'); 
}

function edit_dept(id){
	save_method = 'update';
	$('#edit_msg').html(''); 
    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_dept')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="dept_id"]').val(data.dept.dept_id);
                $('[name="dept_name"]').val(data.dept.dept_name);
                $('[name="dept_logo"]').val(data.dept.logo);
                $('.img-responsive').attr('src', '<?php echo site_url('assets/images/client_logo/')?>' + data.dept.logo);
                $('#deptModal').modal('show'); 
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

function update_dept(){ 
	$('#edit_msg').html(''); 
	var formdata = new FormData($('#dept_edit')[0]);  
	$.ajax({
	    url : "<?php echo site_url('backend/admin/update_dept')?>",
	    type: "POST",
	    data: formdata,
	    dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
	    success: function(data)
	    {
	        if(data.status){
	            $('#deptModal').modal('hide');
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

function save_dept(){ 
	$('#add_msg').html('');   
	$.ajax({
	    url : "<?php echo site_url('backend/admin/add_dept')?>",
	    type: "POST",
	    data: $('#dept_add').serialize(),
	    dataType: "JSON",
	    success: function(data)
	    {
	        if(data.status){
	            $('#add_deptsModal').modal('hide');
	            alert(data.msg);
	            location.reload();
	        }
	        else{
	            $('#add_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
	        }
	    },
	    error: function (jqXHR, textStatus, errorThrown)
	    {
	        alert('Error saving department'); 
	    }
	});
}

function delete_dept(id){
    if(confirm('Are you sure you want to delete this department?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_dept')?>/"+id,
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
                alert('Error deleting department');
            }
        });
    }
}
</script>

<!-- Add Departments Modal-->
    <div class="modal fade" id="add_deptsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Department(s) for <em class="text-muted"><?php echo ucwords(str_replace('_', ' ', $client->organization)); ?></em> <span class="btn btn-default btn-xs" style="margin-left: 40px;" id="more_dept"><i class="fa fa-plus-circle"></i> Add More</span></h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="add_msg"></div>
          	<?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'dept_add', 'method' => 'POST']);?>
          		<input type="hidden" value="<?php echo $client_id?>" name="client_id"/> 
			    <div class="form-group" id="append_dept" style="margin: 10px 0;">
			    	<div class="col-md-6">
				    	<input type="text" name="department[]" class="form-control input-sm" placeholder="Department" />
				    </div>
			    </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-secondary btn-sm" id="deptSave" onclick="save_dept()"><i class="fa fa-save"></i> Save Department(s)</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Department Modal-->
    <div class="modal fade" id="deptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Department</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="edit_msg"></div>
          	<?php echo form_open_multipart('#', ['class' => 'form-horizontal', 'id' => 'dept_edit', 'method' => 'POST']);?>
          		<input type="hidden" value="" name="dept_id"/> 
		        <div class="form-group">
		        	<div class="col-md-2">
	                <label class="control-label">Department: </label>
		            </div>
		            <div class="col-md-10">
	                <input name="dept_name" placeholder="Department Name" class="form-control" type="text" />
		            </div>
		        </div>
		        <div class="form-group">
		        	<div class="col-md-2"><label class="control-label">Logo:</label></div>
		    		<div class="col-md-2"><input name="logo"  type="file" /></div>
		    		<div class="col-md-8"><span class="pull-right"><img class="img-responsive" style="width: 100px; height: 50px; border: solid #999 1px;" /></span></div>
		        </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-secondary btn-sm" id="deptUpdate" onclick="update_dept()"><i class="fa fa-refresh"></i> Update Department</button>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>