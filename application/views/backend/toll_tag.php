<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-institution"></i> Tag Management</div>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-xs" onclick="add_toll_tag()"><i class="fa fa-plus-circle"></i> Add Tag</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
        				<div class="table-responsive">
            				<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
            					<thead>
            						<th>#</th>
            						<th>Toll Tag</th>
            						<th>Client</th>
                                    <th>Status</th>
            						<th class="noExport">Actions</th>
            					</thead>
            					<tbody>
            						<?php $i = 0; foreach ($tags as $tag) {?>
            						<tr>
            							<td><?php echo ++$i; ?></td>
            							<td><?php echo ucfirst($tag->tag);?></td>
            							<td><?php echo ucwords($tag->organization);?></td>
                                        <td class="text-center"><?php echo ($tag->status) ? 'Assigned' : 'Active'; ?></td>
            							<td>
            								<button class="btn btn-warning btn-xs" onclick="edit_toll_tag(<?php echo $tag->tag_id;?>)" title = "Edit toll tag details"><i class="fa fa-edit"></i></button> |
            								<button class="btn btn-danger btn-xs" onclick="delete_toll_tag(<?php echo $tag->tag_id;?>)" title = "Delete toll tag"><i class="fa fa-trash"></i></button>
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

function add_toll_tag(){
	save_method = 'add';
    $('#new_toll_tag')[0].reset();
    $('#msg').html(''); 
    $('#tollTagModal').modal('show'); 
    $('#tollTagSave').text('Save Toll Tag');
    $('.modal-title').text('Add Toll Tag'); 
}

function edit_toll_tag(id){
    save_method = 'update';
    $('#new_toll_tag')[0].reset(); 
    $('#msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_toll_tag')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.tag_data.tag_id);
                $('[name="toll_tag"]').val(data.tag_data.tag);
                $('[name="previx"]').val(data.tag_data.previx);
                $('[name="client"]').val(data.tag_data.client_id);

                $('#tollTagModal').modal('show'); 
                $('#tollTagSave').text('Update Toll Tag');
                $('.modal-title').text('Edit Toll Tag');
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
    $('#tollTagSave').text('Saving...'); 
    $('#tollTagSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_toll_tag')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_toll_tag')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_toll_tag').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#tollTagModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#tollTagSave').text('Save Toll Tag'); 
                $('#tollTagSave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#tollTagSave').text('Save Toll Tag'); 
                $('#tollTagSave').attr('disabled',false);  
            }
        });
}

function delete_toll_tag(id){
    if(confirm('Are you sure you want to delete this data?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_toll_tag')?>/"+id,
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

<!-- New Toll Tag Modal-->
    <div class="modal fade" id="tollTagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Toll Tag</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="msg"></div>
          	<?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_toll_tag', 'method' => 'POST']);?>
          	<input type="hidden" value="" name="id"/> 
		        <div class="form-group">
                    <div class="row">
    		        	<div class="col-md-2">
    	                <input name="previx" placeholder="Tag Prefix" class="form-control" type="text" />
    	                </div>
                        <div class="col-md-4">
                        <input name="toll_tag" placeholder="Toll Tag" class="form-control" type="text" />
                        </div>
    	                <div class="col-md-6">
    	                <select name="client" class="form-control">
    	                    <option selected="selected" disabled="disabled" value="">--Select Client--</option>
    	                    <?php foreach ($clients as $client) {?>
    	                        <option value="<?php echo $client->id;?>"><?php echo ucwords($client->organization);?></option>
    	                    <?php }?>
    	                </select>
    	                </div>
    		        </div>
                </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-success btn-sm" id="tollTagSave" onclick="save()"><i class="fa fa-save"></i> Save Toll Tag</button>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>