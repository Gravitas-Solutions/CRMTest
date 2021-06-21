<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-institution"></i> Agencies Management</div>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-xs" onclick="add_agency()"><i class="fa fa-plus-circle"></i> Add Agency</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
        				<div class="table-responsive">
            				<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
            					<thead>
            						<th>#</th>
            						<th>Agency</th>
            						<th>State</th>
            						<th class="noExport">Actions</th>
            					</thead>
            					<tbody>
            						<?php $i = 0; foreach ($agencies as $agency) {?>
            						<tr>
            							<td><?php echo ++$i; ?></td>
            							<td><?php echo ucfirst($agency->agency_name);?></td>
            							<td><?php echo ucwords($agency->state_name);?></td>
            							<td>
            								<button class="btn btn-warning btn-xs" onclick="edit_agency(<?php echo $agency->agency_id;?>)" title = "Edit agency details"><i class="fa fa-edit"></i></button> |
            								<button class="btn btn-danger btn-xs" onclick="delete_agency(<?php echo $agency->agency_id;?>)" title = "Delete agency"><i class="fa fa-trash"></i></button>
            							</td>
            						</tr>
            						<?php } ?>
            					</tbody>
            				</table>
        				</div>
		             </div><!-- panel-body -->
	            </div><!-- panel panel-primary -->
	        </div>
       </div>
    </div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">

function add_agency(){
	save_method = 'add';
    $('#new_agency')[0].reset();
    $('#msg').html(''); 
    $('#agencyModal').modal('show'); 
    $('#agencySave').text('Save Agency');
    $('.modal-title').text('Add Agency'); 
}

function edit_agency(id){
    save_method = 'update';
    $('#new_agency')[0].reset(); 
    $('#msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_agency')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.agency.agency_id);
                $('[name="agencyName"]').val(data.agency.agency_name);
                $('[name="stateName"]').val(data.agency.state_id);

                $('#agencyModal').modal('show'); 
                $('#agencySave').text('Update Agency');
                $('.modal-title').text('Edit Agency');
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
    $('#agencySave').text('Saving...'); 
    $('#agencySave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_agency')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_agency')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_agency').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#agencyModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#agencySave').text('Save Agency'); 
                $('#agencySave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#agencySave').text('Save Agency'); 
                $('#agencySave').attr('disabled',false);  
            }
        });
}

function delete_agency(id){
    if(confirm('Are you sure you want to delete this data?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_agency')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                alert('Record deleted successfully');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
    }
}
</script>

<!-- New Agency Modal-->
    <div class="modal fade" id="agencyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Agency</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="msg"></div>
          	<?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_agency', 'method' => 'POST']);?>
          	<input type="hidden" value="" name="id"/> 
		        <div class="form-group">
                    <div class="row">
    		        	<div class="col-md-6">
    	                <input name="agencyName" placeholder="Agency Name" class="form-control" type="text" />
    	                </div>
    	                <div class="col-md-6">
    	                <select name="stateName" class="form-control">
    	                    <option selected="selected" disabled="disabled" value="">--Select State--</option>
    	                    <?php foreach ($states as $state) {?>
    	                        <option value="<?php echo $state->state_id;?>"><?php echo ucwords($state->state_name);?></option>
    	                    <?php }?>
    	                </select>
    	                </div>
    		        </div>
                </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-success btn-sm" id="agencySave" onclick="save()"><i class="fa fa-save"></i> Save Agency</button>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>