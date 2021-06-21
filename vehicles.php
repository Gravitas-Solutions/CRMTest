<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div id="wrapper">
	<div id="page-wrapper-client">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"> 
					<i class="fa fa-car"></i> Vehicles
					<span class="pull-right">
					<button data-toggle="modal" href='#vehicle_upload_modal' class="btn btn-outline btn-xs btn-primary"><i class="fa fa-cloud-upload"></i> Upload Vehicles</button>
					</span>
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
				<table id="table_id" class="table nowrap table-condensed table-hover table-bordered">
					<caption class="text-muted" style="padding-bottom: 20px">
						Filtered for: <strong>Dept: </strong><?php echo ucfirst($dept_name) ?>
					</caption>
					<thead  style="background-color: #EBEBEB;">
						<th><i class="fa fa-circle-o "></i></th>
						<th><abbr title="License Plate">LP</abbr></th>
						<th>State</th>
						<?php if(isset($org)){?>
						<th>DNT</th>
						<th>VIN #</th>
						<?php } else{?>
						<th>Unit #</th>
						<?php }?>
						<th>Color</th>
						<th>Make</th>
						<th>Model</th>
						<th>Start Date</th>
						<th>End Date</th>
						<?php if($can_update){ ?>
						<th>Edit</th>
						<?php } ?>
					</thead>
					<tbody>
						<?php foreach ($vehicles as $vehicle) {?>
						<tr>
							<td id="<?php echo $vehicle->vehicle_id;?>">
								<?php
									switch ($vehicle->vehicle_status) {
										case '-1': echo "<i class='fa fa-circle ' style='color: red' data-toggle='tooltip' data-placement='right | auto' title='Status: Inactive'></i>"; break;		

										case '0': echo "<i class='fa fa-circle ' style='color: orange' data-toggle='tooltip' data-placement='right | auto' title='Status: Maintenance'></i>"; break;										
										case '1': echo "<i class='fa fa-circle ' style='color: #95e084' data-toggle='tooltip' data-placement='right | auto' title='Status: Active'></i>"; break;										
																				
										case '2': echo "<i class='fa fa-circle ' style='color: #0d5405' data-toggle='tooltip' data-placement='right | auto' title='Status: Start'></i>"; break;										
																				
										case '3': echo "<i class='fa fa-circle ' style='color: #a8a3a3' data-toggle='tooltip' data-placement='right | auto' title='Status: End'></i>"; break;
									}
								?>
							</td>
							<td><?php echo ($vehicle->license_plate) ? $vehicle->license_plate : '<center>-</center>';?></td>
							<td><?php echo ($vehicle->location) ? ucwords($vehicle->location) : '<center>-</center>';?></td>
						<?php if(isset($org)){?>
							<td><?php echo ($vehicle->tolltag) ? ucwords($vehicle->tolltag) : '<center>-</center>';?></td>
							<td><?php echo ($vehicle->vin_no) ? ucwords($vehicle->vin_no) : '<center>-</center>';?></td>
						<?php }else{?>
							<td><?php echo ($vehicle->unit) ? ucwords($vehicle->unit) : '<center>-</center>';?></td>
						<?php }?>
							<td><?php echo ($vehicle->color) ? ucwords($vehicle->color) : '<center>-</center>';?></td>
							<td><?php echo ($vehicle->make) ? ucwords($vehicle->make) : '<center>-</center>';?></td>
							<td><?php echo ($vehicle->model) ? ucwords($vehicle->model) : '<center>-</center>';?></td>
							<td><center><?php echo nice_date($vehicle->start_date, 'Y-m-d');?></center></td>
							<td><center><?php echo ($vehicle->end_date == '0000-00-00 00:00:00') ? '<center>-</center> ' : nice_date($vehicle->end_date, 'Y-m-d');?></center></td>
							<?php if($can_update){ ?>
							<td class="text-center"><button class="update_vhl btn btn-xs btn-default btn-outline <?php echo ($vehicle->vehicle_status == '-1') ? 'disabled' : '' ?>" title="Edit"><i class="fa fa-pencil"></i></button></td>
							<?php } ?>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<th><i class="fa fa-circle-o "></i></th>
						<th><abbr title="License Plate">LP</abbr></th>
						<th>State</th>
						<?php if(isset($org)){?>
						<th>DNT</th>
						<th>VIN #</th>
						<?php }else{?>
						<th>Unit #</th>
						<?php }?>
						<th>Color</th>
						<th>Make</th>
						<th>Model</th>
						<th>Start Date</th>
						<th>End Date</th>
						<?php if($can_update){ ?>
						<th>Edit</th>
						<?php } ?>				
					</tfoot>
				</table>
			</div>
			</div>
		</div>
	</div>
</div>

<!-- Update vehicle modal -->
<div class="modal fade" id="vehicle_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-muted">Update Vehicle</h4>
			</div>
			<div class="modal-body">
				<form action="" method="POST" class="form-horizontal" role="form" id="vehicle_form">
					<input type="hidden" name="vehicle_id" />
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label"><abbr title="License Plate">LP</abbr>:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="license_plate" class="form-control input-sm" />
						</div>
						<div class="col-md-2">
							<label class="control-label">Color:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="color" class="form-control input-sm" />
						</div>							
					</div>	
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">State:</label>
						</div>
						<div class="col-md-4">
							<select name="state" class="form-control">
								<option disabled="disabled" selected="selected" value="">-- Select state --</option>
								<?php foreach ($states as $s) { ?>
									<option value="<?php echo $s->state_code ?>"><?php echo ucwords(strtolower($s->state_name)).' ('.strtoupper($s->state_code).')' ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2">
							<label class="control-label">Make:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="make" class="form-control input-sm" />
						</div>							
					</div>	
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">VIN #:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="vin_no" class="form-control input-sm" />
						</div>
						<div class="col-md-2">
							<label class="control-label">Model:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="model" class="form-control input-sm" />
						</div>							
					</div>	
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">DNT #:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="tolltag" class="form-control input-sm" />
						</div>							
					</div>
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">Status:</label>
						</div>
						<div class="col-md-10">
							<label class="radio-inline"><input type="radio" name="status" value="1">Active</label>
					        <label class="radio-inline"><input type="radio" name="status" value="2">Start</label>
					        <label class="radio-inline"><input type="radio" name="status" value="0">Maintenance</label>
					        <label class="radio-inline"><input type="radio" name="status" value="3">End</label>
					        <label class="radio-inline"><input type="radio" name="status" value="-1">Inactive</label>
						</div>							
					</div>										
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-outline btn-warning" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" class="btn btn-xs btn-outline btn-success" onclick="update_vehicle()"><i class="fa fa-save"></i> Save changes</button>
			</div>
		</div>
	</div>
</div>

<!-- Upload vehicle modal -->
<div class="modal fade" id="vehicle_upload_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-muted">Upload Vehicles</h4>
			</div>
			<div class="modal-body">
				<div class="text-danger text-center" id="upload_msg"></div>
				<form action="" method="POST" class="form-horizontal" role="form" id="vehicles_upload_form">
					<div class="form-group">
						<div class="col-md-4">
							<label class="control-label"><i class="fa fa-file-excel-o text-success"></i> Vehicles file:</label>
						</div>
						<div class="col-md-8">
							<input type="file" name="vehicles_file" />
							<span class="help-block" style="padding-top: 10px; font-size: 12px; font-style: oblique;"><i class="fa fa-info-circle text-info"></i> Allowed types: .xls &amp; .xlsx</span>
						</div>							
					</div>											
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-outline btn-warning" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" class="btn btn-xs btn-outline btn-success" onclick="upload_vehicles()"><i class="fa fa-cloud-upload"></i> Upload vehicles file</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
});
$('.update_vhl').click(function(){
	var id = $(this).closest('tr').children('td:first').attr('id');
	$.ajax({
		url: '<?php echo base_url()?>member/edit_vehicle/'+id,
		type: 'GET',
		dataType: 'JSON',
		success: function(data){
			if (data.status) {
				console.log(data);
				$('#vehicle_form')[0].reset();
				$('input[name="vehicle_id"]').val(data.vehicle.vehicle_id);
				$('input[name="license_plate"]').val(data.vehicle.license_plate);
				$('input[name="tolltag"]').val(data.vehicle.tolltag);
				$('input[name="vin_no"]').val(data.vehicle.vin_no);
				$('input[name="make"]').val(data.vehicle.make);
				$('input[name="model"]').val(data.vehicle.model);
				$('input[name="color"]').val(data.vehicle.color);
				$('select[name="state"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.location;
                }).prop('selected', true);
				$('input:radio[name="status"][value="' + data.vehicle.vehicle_status + '"]').prop("checked", true);
				$('#vehicle_modal').modal('show');
			} else{
				alert(data.msg);
			}
		}
	});
});

function update_vehicle(){
	$.ajax({
		url: '<?php echo base_url()?>member/update_vehicle',
		type: 'POST',
		dataType: 'JSON',
		data: $('#vehicle_form').serialize(),
		success: function(data){
			if (data.status) {
				alert(data.msg);
				location.reload();
			} else{
				alert(data.msg);
			}
		}
	});
}

function upload_vehicles(){
	var vehicles_data = new FormData($('#vehicles_upload_form')[0]);
    $.ajax({
        url: '<?php echo base_url()?>member/upload_vehicles',
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: vehicles_data,
        success: function(data){
            if (data.status) {
				alert(data.msg);
				location.reload();
			} else{
				$('#upload_msg').empty().html(data.msg+'<hr>');
			}
        }
    });   
}
</script>