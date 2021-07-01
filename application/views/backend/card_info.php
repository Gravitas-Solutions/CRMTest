<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-institution"></i>Payment Card Management</div>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-xs" onclick="add_card_info()"><i class="fa fa-plus-circle"></i> Add Card</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
        				<div class="table-responsive">
            				<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
            					<thead>
            						<th>#</th>
                                    <th>Client</th>
            						<th>Holder</th>
            						<th>Type</th>
                                    <th>Postal Code</th>
                                    <th>Expiration Date</th>
                                    <th>CVC</th>
            						<th class="noExport">Actions</th>
            					</thead>
            					<tbody>
            						<?php $i = 0; foreach ($cards as $card) {?>
            						<tr>
            							<td><?php echo ++$i; ?></td>
            							<td><?php echo strtoupper(ucwords(str_replace('_', ' ', $card->organization))) ?></td>
                                        <td><?php echo strtoupper(ucwords( $card->holder_name)) ?></td>
                                        <td><?php echo strtoupper(ucwords( $card->type)) ?></td>
                                        <td><?php echo $card->postal_code ?></td>
                                        <td><?php echo $card->expiration_date ?></td>
                                        <td><?php echo $card->cvc ?></td>
            							<td>
            								<button class="btn btn-warning btn-xs" onclick="edit_card_info(<?php echo $card->card_info_id;?>)" title = "Edit card details"><i class="fa fa-edit"></i></button> |
            								<button class="btn btn-danger btn-xs" onclick="delete_card_info(<?php echo $card->card_info_id;?>)" title = "Delete Card"><i class="fa fa-trash"></i></button>
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
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover({
            placement : 'top',
            trigger : 'hover',
            html : true,
            content : '<div class="media"><img src="<?php echo base_url() ?>logo-footer.png />" class="mr-3" alt="CVC smaple"><div class="media-body"><h5 class="media-heading">Verification Code</h5><p>It is a 4 digit verification code in American Express and 3 digit in Discover, Master Card and Visa.</p></div></div>'
        });
    });

function add_card_info(){
	save_method = 'add';
    $('#new_card_info')[0].reset();
    $('#msg').html(''); 
    $('#cardModel').modal('show'); 
    $('#cardInfoSave').text('Save Card Info');
    $('.modal-title').text('Add Card Info'); 
}

function edit_card_info(id){
    save_method = 'update';
    $('#new_card_info')[0].reset(); 
    $('#msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_card_info')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.card_data.card_info_id);
                $('[name="holder_name"]').val(data.card_data.holder_name);
                $('[name="card_type"]').val(data.card_data.card_type);
                $('[name="card_number"]').val(data.card_data.card_number);
                $('[name="cvc"]').val(data.card_data.cvc);
                $('[name="postal_code"]').val(data.card_data.postal_code);
                $('[name="expiration_date"]').val(data.card_data.expiration_date);
                $('[name="client_id"]').val(data.card_data.client_id);

                $('#cardModel').modal('show'); 
                $('#cardInfoSave').text('Update Card Info');
                $('.modal-title').text('Edit Card Info');
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
    $('#cardInfoSave').text('Saving...'); 
    $('#cardInfoSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_card_info')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_card_info')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_card_info').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#cardModel').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#cardInfoSave').text('Save Card Info'); 
                $('#cardInfoSave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#cardInfoSave').text('Save Card Info'); 
                $('#cardInfoSave').attr('disabled',false);  
            }
        });
}

function delete_card_info(id){
    if(confirm('Are you sure you want to delete this Card Info?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_card_info')?>/"+id,
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

<!-- New Card Info Modal-->
    <div class="modal fade" id="cardModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Card Details</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="msg"></div>
          	<?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_card_info', 'method' => 'POST']);?>
          	<input type="hidden" value="" name="id"/> 
		        <div class="form-group">
                    <div class="row">
                        <div class="col-md-2"><label class="control-label"> Holder Name:</label></div>
    		        	<div class="col-md-4">
    	                   <input name="holder_name" placeholder="Card Holder Name" class="form-control" type="text" />
    	                </div>
    	                <div class="col-md-6">
    	                <select name="card_type" class="form-control">
    	                    <option selected="selected" disabled="disabled" value="">--Select Card Type--</option>
    	                    <?php foreach ($card_types as $types) {?>
    	                        <option value="<?php echo $types->card_type_id ;?>"><?php echo ucwords($types->type);?></option>
    	                    <?php }?>
    	                </select>
    	                </div>
    		        </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2"><label class="control-label"> Card Number:</label></div>
                        <div class="col-md-4">
                            <input name="card_number" placeholder="Card Number" class="form-control" type="text" />
                        </div>
                        <div class="col-md-1"><label data-toggle="popover" title="CVC"> <i class="fa fa-info-circle text-primary"></i></label></div>
                        <div class="col-md-2">
                            <input name="cvc" placeholder="CVC" class="form-control" type="text" />
                        </div>
                        <div class="col-md-3">
                            <input name="postal_code" placeholder="Postal Code" class="form-control" type="text" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2"><label class="control-label"> Expire:</label></div>
                        <div class="col-md-4">
                            <div class='input-group date' id="expiration_date">
                                <input type='text' id="expiration_date" name="expiration_date" class="form-control input-sm" value="<?php echo set_value('expiration_date')?>"  placeholder="Expiration Date" />
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                            <span class="help_block"><?php echo form_error('expiration_date')?></span>
                        </div>
                        <div class="col-md-6">
                        <select name="client_id" class="form-control">
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
            <button class="btn btn-success btn-sm" id="cardInfoSave" onclick="save()"><i class="fa fa-save"></i> Save Card Info</button>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>