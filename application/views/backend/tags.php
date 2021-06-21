<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-tags"></i> Tag Type Management</div>
                                    <div class="card-tools">
                                       <button class="btn btn-primary btn-xs" onclick="add_tag()"><i class="fa fa-plus-circle"></i> Add Tag Type</button>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                				<div class="table-responsive">
                				<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                					<thead>
                						<th>#</th>
                						<th>Tag Type</th>
                						<th  class="noExport">Actions</th>
                					</thead>
                					<tbody>
                						<?php $i = 0; foreach ($tags as $tag) {?>
                						<tr>
                							<td><?php echo ++$i; ?></td>
                							<td><?php echo ucwords($tag->tag_type);?></td>
                							<td>
                								<button class="btn btn-warning btn-xs" onclick="edit_tag(<?php echo $tag->tag_id;?>)" title = "Edit tag details"><i class="fa fa-edit"></i></button> |
                								<button class="btn btn-danger btn-xs" onclick="delete_tag(<?php echo $tag->tag_id;?>)" title = "Delete tag"><i class="fa fa-trash"></i></button>
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
function add_tag(){
	save_method = 'add';
    $('#new_tag')[0].reset();
    $('#msg').html(''); 
    $('#tagModal').modal('show'); 
    $('.modal-title').text('Add Tag Type'); 
}

function edit_tag(id){
    save_method = 'update';
    $('#new_tag')[0].reset(); 
    $('#msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_tag')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.tag.tag_id);
                $('[name="tagName"]').val(data.tag.tag_type);

                $('#tagModal').modal('show'); 
                $('#tagSave').text('Update Tag');
                $('.modal-title').text('Edit Tag Type');
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
    $('#tagSave').text('saving...'); 
    $('#tagSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_tag')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_tag')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_tag').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#tagModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#tagSave').text('Save Tag'); 
                $('#tagSave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#tagSave').text('Save Tag'); 
                $('#tagSave').attr('disabled',false);  
            }
        });
}

function delete_tag(id){
    if(confirm('Are you sure you want to delete this data?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_tag')?>/"+id,
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

<!-- New Tag Type Modal-->
    <div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Tag Type</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="msg"></div>
          	<?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_tag', 'method' => 'POST']);?>
          	<input type="hidden" value="" name="id"/> 
		        <div class="form-group">
	                <input name="tagName" placeholder="Tag Type" class="form-control" type="text" />
		        </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-success btn-sm" id="tagSave" onclick="save()"><i class="fa fa-save"></i> Save Tag</button>
          </div>
        </div>
      </div>
    </div>