<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
	<div class="content">
				<div class="page-inner">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title"><i class="fas fa-car"></i> Vehicles</div>
										<div class="card-tools">
											<button onclick="add_vehicle()" class="btn btn-outline btn-xs btn-primary"><i class="fa fa-cloud-upload"></i> Add Vehicles</button>
											<button data-toggle="modal" href='#vehicle_upload_modal' class="btn btn-outline btn-xs btn-primary"><i class="fa fa-cloud-upload"></i> Fleet Upload</button>
										</div>
									</div>
								</div>
								<div class="card-body">
										<div class="col-md-12">
											<div class="table-responsive">
									               <table id="basic-datatables" class="display table table-striped table-sm  table-bordered table-hover table-head-bg-dark table-condensed" >
									                   <thead  style="background-color: #EBEBEB;">
														<th>Status</th>
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
														<th class='noExport'>Edit</th>
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
															<td><center><?php echo ($vehicle->end_date == '0000-00-00') ? '<center>-</center> ' : nice_date($vehicle->end_date, 'Y-m-d');?></center></td>
															<?php if($can_update){ ?>
															<td class="text-center"><button onclick="edit_vehicle(<?php echo ($vehicle->vehicle_id);?>)" class="btn btn-sm btn-icon btn-round btn-info <?php echo ($vehicle->vehicle_status == '-1') ? 'disabled' : '' ?>" title="Edit"><i class="flaticon-pen"></i></button></td>
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
														<th class='noExport'>Edit</th>
														<?php } ?>				
													</tfoot>				
									               </table>
									           </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php $this->load->view('frontend/includes/footer_start'); ?>
<!-- Upload vehicle modal -->
<div class="modal fade" id="vehicle_upload_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">				
				<h4 class="modal-title text-muted">Upload Vehicles</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="text-danger text-center" id="upload_msg"></div>
				<form action="" method="POST" class="form-horizontal" role="form" id="vehicles_upload_form">
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<label class="control-label"><i class="fa fa-file-excel-o text-success"></i> Vehicles file:</label>
							</div>
							<div class="col-md-8">
								<input type="file" name="vehicles_file" />
								<span class="help-block" style="padding-top: 10px; font-size: 12px; font-style: oblique;"><i class="fa fa-info-circle text-info"></i> Allowed types: .xls &amp; .xlsx</span>
							</div>
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

     <div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog  modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="vehicleModalLongTitle">Add Vehicle</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	       <div class="modal-body form">
            <div id="vehicle_msg"></div>
            <form action="#" class="form-horizontal" id="new_vehicle" method="POST" accept-charset="utf-8">
            <input type="hidden" value="" name="id"/> 
               <div class="form-group">
                <div class="row">
                	<div class="col-2 col-sm-2"><label class="control-label">License Plate: <span class="text-danger">*</span></label></div>
                    <div class="col-4 col-sm-4">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fas fa-car-alt"></i>
							</span>
							<input type="text" class="form-control" name="license_plate" placeholder="License Plate" oninput="this.value = this.value.toUpperCase()" />
						</div>
                    </div>
                    <div class="col-2 col-sm-2"><label class="control-label">Model: </label></div>
                    <div class="col-4 col-sm-4">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fab fa-monero"></i>
							</span>
							<input type="text" class="form-control" name="model" placeholder="Model" oninput="this.value = this.value.toUpperCase()"/>
						</div>
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <div class="row">
                    <div class="col-2 col-sm-2"><label class="control-label">Color: </label></div>
                    <div class="col-4 col-sm-4">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fas fa-brush"></i>
							</span>
							<input type="text" class="form-control"  name="color" placeholder="Color" oninput="this.value = this.value.toUpperCase()"/>
						</div>
                    </div>
                    <div class="col-2 col-sm-2"><label class="control-label">Make: </label></div>
                    <div class="col-4 col-sm-4">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fas fa-cogs"></i>
							</span>
							<input type="text" class="form-control" name="make" placeholder="Make" oninput="this.value = this.value.toUpperCase()"/>
						</div>
                    </div>
                </div>
                </div>
                <div class="form-group">
                   <div class="row">
                   	<div class="col-2 col-sm-2"><label class="control-label">Axles: <span class="text-danger">*</span></label></div>
                    <div class="col-4 col-sm-4">
                        <select name="axles" class="form-control input-sm">
                            <option selected="selected" disabled="disabled" value="">--Select Axles--</option>
                            <option value="2_axles">Two axles</option>
                            <option value="3_axles">Three axles</option>
                            <option value="4_axles">Four axles</option>
                            <option value="5_axles">Five axles</option>
                            <option value="6_axles">Six axles</option>
                            <option value="7_axles">Seven axles</option>
                            <option value="8_axles">Eight axles</option>
                        </select>
                    </div>
                    <div class="col-2 col-sm-2"><label class="control-label">Tag Type: <span class="text-danger">*</span></label></div>
                    <div class="col-4 col-sm-4">
                        <select name="tagtype" class="form-control input-sm">
                            <option selected="selected" disabled="disabled" value="">--Select Tag Type--</option>
                            <option value="1">Trailer</option>
                            <option value="2">Dealer</option>
                            <option value="3">Etag</option>
                            <option value="4">Cab</option>
                        </select>
                    </div>
                </div>
            </div>
                <div class="form-group">
                   <div class="row">
                   	<div class="col-2 col-sm-2"><label class="control-label">Start Date: <span class="text-danger">*</span></label></div>
                    <div class="col-4 col-sm-4">
                        <div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type='text' name="start_date" id='start_date' class="form-control input-sm"  placeholder="Start date"/>
						</div>
                    </div>
                    <div class="col-2 col-sm-2"><label class="control-label">End Date: </label></div>
                    <div class="col-4 col-sm-4">
                        <div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type='text' name="end_date" id='end_date' class="form-control input-sm" placeholder="End date"/>
						</div>
                    </div>
                </div>
                 </div>
                <div class="form-group">
                    <div class="row">
                    	<div class="col-2 col-sm-2"><label class="control-label">Store: </label></div>
	                    <div class="col-4 col-sm-4">
	                        <input name="store" placeholder="Store" class="form-control input-sm" type="text" oninput="this.value = this.value.toUpperCase()"/>
	                    </div>
	                    <div class="col-2 col-sm-2"><label class="control-label">State: <span class="text-danger">*</span></label></div>
	                   <div class="col-4 col-sm-4">
	                        <select name="location" class="form-control input-sm">
	                           <option value="" selected="selected"  disabled="disabled">--Store Location--</option>
                            <?php foreach ($states as $s) {?>
                            <option value="<?php echo $s->state_code;?>"><?php echo ucwords($s->state_name);?></option>
                            <?php }?>
	                        </select> 
	                    </div>
                 </div>
             </div>
                <div class="form-group">
                    <div class="row">
                    <div class="col-2 col-sm-2"><label class="control-label">Year: <span class="text-danger">*</span></label></div>
                    <div class="col-4 col-sm-4">
                        <input name="year" placeholder="Year" class="form-control input-sm" type="text"/>
                    </div>
                    <div class="col-2 col-sm-2"><label class="control-label">Unit: </label></div> 
                    <div class="col-4 col-sm-4">
                        <input name="unit" placeholder="Unit" class="form-control input-sm" type="text"/>
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <div class="row">
                    <div class="col-2 col-sm-2"><label class="control-label">Tolltag: </label></div>
                    <div class="col-4 col-sm-4">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-user"></i>
							</span>
							<input type="text" class="form-control" name="tolltag" placeholder="Tolltag ID"/>
						</div>                      
                    </div>
                    <div class="col-2 col-sm-2"><label class="control-label">Vin No: </label></div>   
                    <div class="col-4 col-sm-4">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-user"></i>
							</span>
							<input type="text" class="form-control" name="vin_no" placeholder="VIN Number" oninput="this.value = this.value.toUpperCase()"/>
						</div>                      
                    </div>
                </div>
                 </div>
                <div class="form-group">
                   <div class="row">	                    
	                    	<div class="col-2 col-sm-2"><label class="control-label">Dept: <span class="text-danger">*</span></label></div>
	                    	<div class="col-4 col-sm-4">
                            <select name="dept" class="form-control input-sm">
                                <option value="" selected="selected"  disabled="disabled">--Dept--</option>
                                <?php foreach ($departments as $department) {?>
                                	<?php if (strpos($department->dept_name, 'overview') !== false) {
                                		continue; }?>
                                		<option value="<?php echo $department->dept_id;?>"><?php echo ucwords(str_replace('_', ' ', $department->dept_name));?></option>
                                <?php }?>
                            </select>                     
                        </div>
                        <!-- <div class="col-2 col-sm-2"><label class="control-label">Sub Dept: <span class="text-danger">*</span></label></div> -->
	                    <div class="col-6 col-sm-6">
	                        <select name="sub_dept" class="form-control input-sm"></select>
	                    </div>
                	</div>
                </div>
                <div class="form-group">
			       <div class="row">
						<div class="col-2 col-sm-2"><label class="control-label">Status: <span class="text-danger">*</span></label></div>
						<div class="col-md-8">
					          	<div class="form-check">
									<label class="form-radio-label">
										<input class="form-radio-input" type="radio" name="status" value="1" checked="checked">
										<span class="form-radio-sign">Active</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="status" value="2">
										<span class="form-radio-sign">Start</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="status" value="0">
										<span class="form-radio-sign">Maintenance</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="status" value="3">
										<span class="form-radio-sign">End</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="status" value="-1">
										<span class="form-radio-sign">Inactive</span>
									</label>
								</div>
							</div>
			    	</div>
			    </div>
                <div class="col-md-12">
                    <small class="text-info"><i class="fa fa-info-circle"></i> NOTE: Fields with <span class="text-danger">*</span> are (<span style="font-weight: 800; text-decoration: underline;"> manadatory</span>) please ensure they are filled before submission.</small>
                </div>
            </form>
          </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary"  id="vehicleSave" onclick="save()">Save</button>
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
							<label class="control-label"><abbr title="License Plate">LP</abbr>: <span class="text-danger">*</span></label>
						</div>
						<div class="col-md-4">
							<input type="text" name="license_plate" class="form-control input-sm" oninput="this.value = this.value.toUpperCase()"/>
						</div>
						<div class="col-md-2">
							<label class="control-label">Color:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="color" class="form-control input-sm" oninput="this.value = this.value.toUpperCase()"/>
						</div>							
					</div>	
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">State: <span class="text-danger">*</span></label>
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
							<input type="text" name="make" class="form-control input-sm" oninput="this.value = this.value.toUpperCase()"/>
						</div>							
					</div>	
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">VIN #:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="vin_no" class="form-control input-sm" oninput="this.value = this.value.toUpperCase()"/>
						</div>
						<div class="col-md-2">
							<label class="control-label">Model:</label>
						</div>
						<div class="col-md-4">
							<input type="text" name="model" class="form-control input-sm" oninput="this.value = this.value.toUpperCase()"/>
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
					<div class="col-md-12">
                    <small class="text-info"><i class="fa fa-info-circle"></i> NOTE: Fields with <span class="text-danger">*</span> are (<span style="font-weight: 800; text-decoration: underline;"> manadatory</span>) please ensure they are filled before submission.</small>
                </div>									
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-outline btn-warning" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" class="btn btn-primary"  id="vehicleSave" onclick="save()">Save</button>
			</div>
		</div>
	</div>
</div>
<script >
$(function () {
  $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
});
$(document).ready(function(){

    $('select[name="dept"]').on('change', function() {
        $('select[name="sub_dept"]').empty().append('Select dept first').hide();
        var dept_id = $(this).val(); 
        var url = "<?php echo base_url('frontend/member/dept_sub_departments')?>/"+dept_id;
        if(dept_id) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    console.log(data);
                   if (data.length > 0) {
                        $('select[name="sub_dept"]').empty();
                        $('select[name="sub_dept"]').append('<option value="">-- Select sub-dept --</option>');
                        $.each(data, function(key, value) {
                            $('select[name="sub_dept"]').append('<option value="'+ value.sub_dept_id +'">'+ value.sub_dept_name +'</option>');
                            $('select[name="sub_dept"]').show();
                        });
                        if(data.length > 1){
                            $("select[name='sub_dept'] option:contains('overview')").remove();
                        }
                    }else{
                        $('select[name="sub_dept"]').empty();
                        $('select[name="sub_dept"]').append('<option value="">No sub-department(s) found for selected department </option>');
                    }
                }
            });
        }
    });  
});

$(document).keypress(function(e){
    if (e.which == 13){
        $("#vehicleSave").click();
        return false;
    }
});


function add_vehicle(){
    save_method = 'add';
    $('#new_vehicle')[0].reset();
    $('#vehicle_msg').html(''); 
    $('select[name="sub_dept"]').empty().hide(); 
    $('#vehicleModal').modal('show'); 
    $('#vehicleSave').text('Save Vehicle');
    $('.modal-title').text('Add Vehicle'); 

}

function edit_vehicle(id){
    save_method = 'update';
    $('#new_vehicle')[0].reset(); 
    $('select[name="sub_dept"]').empty().hide();
    $('#vehicle_msg').html('');

    $.ajax({
        url : "<?php echo site_url('frontend/member/edit_vehicle')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.vehicle.vehicle_id);
                $('[name="license_plate"]').val(data.vehicle.license_plate);
                $('[name="model"]').val(data.vehicle.model);
                $('[name="make"]').val(data.vehicle.make);
                $('[name="color"]').val(data.vehicle.color);
                $('select[name="axles"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.axles;
                }).prop('selected', true);
                $('select[name="tagtype"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.tag_id;
                }).prop('selected', true);
                $('[name="store"]').val(data.vehicle.store);
                $('select[name="location"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.location;
                }).prop('selected', true);
                $('[name="start_date"]').val(data.vehicle.start_date);
                (data.vehicle.end_date == '0000-00-00') ? $('[name="end_date"]').val(''): $('[name="end_date"]').val(data.vehicle.end_date);
                $('[name="unit"]').val(data.vehicle.unit);
                $('[name="year"]').val(data.vehicle.year);
                // $('select[name="dept"]').empty().append('<option value="'+ data.vehicle.dept_id +'">'+ data.vehicle.dept_name +'</option>');
                $('select[name="dept"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.dept_id;
                }).prop('selected', true);                
                $('[name="tolltag"]').val(data.vehicle.tolltag);
                $('[name="vin_no"]').val(data.vehicle.vin_no);
                $('input:radio[name="status"][value="' + data.vehicle.vehicle_status + '"]').prop("checked", true);
                if(data.vehicle.sub_dept_id !== null){
                	var dept = data.vehicle.dept_id;
               		var sub_dept = data.vehicle.sub_dept_id;
                   /* $('select[name="sub_dept"]').empty().append('<option value="'+ data.vehicle.sub_dept_id +'">'+ data.vehicle.sub_dept_name +'</option>').show();*/
                      $.ajax({
                        url: "<?php echo base_url('frontend/member/dept_sub_departments')?>/"+dept,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                                $.each(data, function(key, value) {
                                    $('select[name="sub_dept"]').append('<option value="'+ value.sub_dept_id +'">'+ value.sub_dept_name +'</option>');
                                });
                                if(data.length > 1){
                                    $("select[name='sub_dept'] option:contains('overview')").remove();
                                }
                                $('select[name="sub_dept"]').show(); 
                                $('select[name="sub_dept"] option').prop('selected', false).filter(function(){
                                    return $(this).val() == sub_dept; }).prop('selected', true);
                        }
                    });    
                }
                $('#vehicleModal').modal('show'); 
                $('#vehicleSave').text('Update Vehicle');
                $('.modal-title').text('Update Vehicle Details');
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
    $('#vehicleSave').text('saving...'); 
    $('#vehicleSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('frontend/member/add_vehicle')?>";
    } else {
        url = "<?php echo site_url('frontend/member/update_vehicle')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_vehicle').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#vehicleModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#vehicle_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#vehicleSave').text('Save Vehicle'); 
                $('#vehicleSave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#vehicleSave').text('Save Vehicle'); 
                $('#vehicleSave').attr('disabled',false);  
            }
        });
}

function activate_vehicle(id){
        $.ajax({
            url : "<?php echo site_url('frontend/member/activate_vehicle')?>/"+id,
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
                alert('Error changing vehhicle status');
            }
        });
}

function upload_vehicles(){
	var vehicles_data = new FormData($('#vehicles_upload_form')[0]);
    $.ajax({
        url: '<?php echo base_url()?>frontend/member/upload_vehicles',
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
<?php $this->load->view('frontend/includes/footer_end'); ?>