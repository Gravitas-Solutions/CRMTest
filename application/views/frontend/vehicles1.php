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
									               <table id="server_side_table" class="display table table-striped table-sm  table-bordered table-hover table-head-bg-dark table-condensed" >
									                    <thead>
									                           	<th>LP</th>
									                           	<th>State</th>
									                           	<?php if($client == 'protech_as'){?>
									                           	<th>Cost Center</th>	
																<?php }else{?>
																 <th>Dept.</th> 
																<?php } ?>
																<th>DNT</th>
																<th>VIN #</th>
																<th>Unit #</th>
																<th>Color</th>
									                            <th>Make</th>
									                            <th>Model</th>
									                            <th>Start Date</th>
									                            <th>End Date</th>
																<th>Edit</th>
									                    </thead>				
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
            <input type="hidden" value="<?php echo $client ;?>" name="vehicle_client_name"/> 
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
							<input type="text" class="form-control" name="model" placeholder="Model" oninput="this.value = this.value.toUpperCase()">
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
	                    <div class="col-2 col-sm-2"><label class="control-label">Vin NO: </label></div>  
	                    <div class="col-4 col-sm-4">
	                    	<div class="input-icon">
								<span class="input-icon-addon">
									<i class="fa fa-user"></i>
								</span>
								<input type="text" class="form-control" name="vin_no" placeholder="VIN Number" oninput="this.value = this.value.toUpperCase()"/>
							</div>                      
	                    </div>
	                    <div class="col-2 col-sm-2"><label class="control-label">Dept: <span class="text-danger">*</span></label></div>
	                    <div class="col-md-4">
                            <select name="dept" id="dept" class="form-control input-sm">
                                <option value="" selected="selected"  disabled="disabled">--Cost Unit--</option>
                                <?php foreach ($departments as $department) {?>
                                <option value="<?php echo $department->dept_id;?>"><?php echo ucwords(str_replace('_', ' ', $department->dept_name));?></option>
                                <?php }?>
                                <option value="other">OTHER</option>
                            </select>                     
                        </div>
                	</div>
                 </div>
                 <div class="form-group" id="cost_center" style="display:none;">
                    <div class="row">
                    	<div class="col-md-6">
                            <small class="text-info">Provide a cost center if not in the list provided.</small>                     
                        </div> 
	                    <div class="col-2 col-sm-2"><label class="control-label">Custom Dept: <span class="text-danger">*</span></label></div>  
	                    <div class="col-4 col-sm-4">
	                    	<input type="text" class="form-control" name="dept2" placeholder="New Cost Center"></input>           
	                    </div>
                	</div>
                 </div>
                <div class="form-group">
                   <div class="row">
                        <div class="col-2 col-sm-2"><label class="control-label">Transponder: </label></div>
	                    <div class="col-4 col-sm-4">
	                    	<?php if($client == 'protech_as'){?>
	                    		<select name="tolltag" id="tolltag" class="form-control input-sm">
	                                <option value="" selected="selected"  disabled="disabled">--Select Tag--</option>
	                                <?php foreach ($tags as $tag) {?>
	                                	<?php if ($tag->organization !== 'protech_as') {
					                  continue;
					                }?>
	                                <option value="<?php echo $tag->tag;?>"><?php echo ucwords(str_replace('_', ' ', $tag->tag));?></option>
	                                <?php }?>
	                                <option value="other">OTHER</option>
	                            </select>
	                    	<?php }else{?>
	                    	<div class="input-icon">
								<span class="input-icon-addon">
									<i class="fa fa-user"></i>
								</span>
								<input type="text" class="form-control" name="tolltag" placeholder="Tolltag ID"/>
							</div>   
							<?php } ?>                   
	                    </div>
	                    <div class="col-6 col-sm-6">
	                        <input id="custom_input" style="display:none;" type="text" class="form-control" name="tolltag2" placeholder="Custom Transponder ID"></input> 
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
		  <div class="modal-dialog  modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="vehicleModalLongTitle">Update Vehicle</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	       <div class="modal-body form">
            <div id="vehicle_msg2"></div>
            <form action="#" class="form-horizontal" id="update_vehicle" method="POST" accept-charset="utf-8">
            <input type="hidden" value="" name="id"/> 
            <input type="hidden" value="" name="client_id"/> 
               <div class="form-group">
                <div class="row">
                	<div class="col-2 col-sm-2"><label class="control-label">License PLate: </label></div>
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
	                    <div class="col-2 col-sm-2"><label class="control-label">Year: <span class="text-danger">*</span> </label></div>
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
	                    <div class="col-2 col-sm-2"><label class="control-label">Vin No: </label></div>
	                    <div class="col-4 col-sm-4">
	                    	<div class="input-icon">
								<span class="input-icon-addon">
									<i class="fa fa-user"></i>
								</span>
								<input type="text" class="form-control" name="vin_no" placeholder="VIN Number" oninput="this.value = this.value.toUpperCase()"/>
							</div>                      
	                    </div>
	                    <div class="col-2 col-sm-2"><label class="control-label">Dept: <span class="text-danger">*</span></label></div>
	                    <div class="col-md-4">
                            <select name="dept" id="dept_edit" class="form-control input-sm">
                                <option value="" selected="selected"  disabled="disabled">--Cost Unit--</option>
                                <?php foreach ($departments as $department) {?>
                                	<?php if (strpos($department->dept_name, 'overview') !== false) {
                                                    continue; }?>
                                                    <!-- <?php if (strpos($department->dept_name, 'overview') !== false || strpos($department->dept_name, 'Unassigned') !== false) {
                                                    continue; }?> -->
                                <option value="<?php echo $department->dept_id;?>"><?php echo ucwords(str_replace('_', ' ', $department->dept_name));?></option>
                                <?php }?>
                                <option value="other">OTHER</option>
                            </select>                     
                        </div>
                    </div>
                 </div>

                <div class="form-group" id="cost_center_edit" style="display:none;">
                    <div class="row">
                    	<div class="col-md-6">
                            <small class="text-info">Provide a cost center if not in the list provided.</small>                     
                        </div> 
	                    <div class="col-2 col-sm-2"><label class="control-label">Custom Dept: <span class="text-danger">*</span></label></div>  
	                    <div class="col-4 col-sm-4">
	                    	<input type="text" class="form-control" name="dept2" placeholder="New Cost Center"></input>           
	                    </div>
                	</div>
                 </div>
                <div class="form-group">
                   <div class="row">
	                    <div class="col-2 col-sm-2"><label class="control-label">Transponder: </label></div>
	                    <div class="col-4 col-sm-4">
                    	<?php if($client == 'protech_as'){?>
                    		<select name="tolltag" id="tolltag_edit" class="form-control input-sm">
                                <option value="" selected="selected"  disabled="disabled">--Select Tag--</option>
                                <?php foreach ($tags as $tag) {?>
                                	<?php if ($tag->organization !== 'protech_as') {
				                  continue;
				                }?>
                                <option value="<?php echo $tag->tag;?>"><?php echo ucwords(str_replace('_', ' ', $tag->tag));?></option>
                                <?php }?>
                                <option value="other">OTHER</option>
                            </select> 
                    	<?php }else{?>
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-user"></i>
							</span>
							<input type="text" class="form-control" name="tolltag" placeholder="Tolltag ID"/>
						</div>   
						<?php } ?>                  
	                    </div>
	                    <div class="col-6 col-sm-6">
	                        <input id="custom_input_edit" style="display:none;" type="text" class="form-control" name="tolltag2" placeholder="Custom Transponder ID"></input> 
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
	        <button type="button" class="btn btn-primary"  id="vehicleupdate" onclick="save()">Save</button>
	      </div>
	    </div>
	  </div>
	</div>
<script >
$(function () {
  $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
});
$(document).ready(function(){

	$('#tolltag').change(function(){
		var selected = $(this).val(); 

		if(selected == "other"){
			$('#custom_input').show();

		}else{
			$('#custom_input').hide();
		}
	});  

	$('#tolltag_edit').change(function(){
		var selected = $(this).val(); 

		if(selected == "other"){
			$('#custom_input_edit').show();

		}else{
			$('#custom_input_edit').hide();
		}
	}); 

	$('#dept').change(function(){
		var selected = $(this).val(); 

		if(selected == "other"){
			$('#cost_center').show();

		}else{
			$('#cost_center').hide();
		}
	});  
	
	$('#dept_edit').change(function(){
		var selected = $(this).val(); 

		if(selected == "other"){
			$('#cost_center_edit').show();

		}else{
			$('#cost_center_edit').hide();
		}
	}); 

    	function newexportaction(e, dt, button, config) {
	    var self = this;
	    var oldStart = dt.settings()[0]._iDisplayStart;
	    dt.one('preXhr', function (e, s, data) {
	        // Just this once, load all data from the server...
	        data.start = 0;
	        data.length = 2147483647;
	        dt.one('preDraw', function (e, settings) {
	            // Call the original action function
	            if (button[0].className.indexOf('buttons-copy') >= 0) {
	                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
	            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
	                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
	                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
	                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
	            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
	                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
	                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
	                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
	            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
	                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
	                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
	                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
	            } else if (button[0].className.indexOf('buttons-print') >= 0) {
	                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
	            }
	            dt.one('preXhr', function (e, s, data) {
	                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
	                // Set the property to what it was before exporting.
	                settings._iDisplayStart = oldStart;
	                data.start = oldStart;
	            });
	            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
	            setTimeout(dt.ajax.reload, 0);
	            // Prevent rendering of the full data to the DOM
	            return false;
	        });
	    });
	    // Requery the server with the new one-time export settings
	    dt.ajax.reload();
	};

     var dataTable = $('#server_side_table').DataTable({
            "responsive": true,
            columnDefs: [
		        { responsivePriority: 1, targets: 0 },
		        { responsivePriority: 10001, targets: -2 },
		        { responsivePriority: 2, targets: -1 }
		    ],
            dom: 'Blfrtip',
				buttons: [
					{
						extend:    'excelHtml5',
						text:      '<i class="fa fa-file-excel"></i>',
						titleAttr: 'Export to Excel',
						exportOptions: {
							  columns: "thead th:not(.noExport)"
						  },
						footer: true,
				  		action: newexportaction
					},
					{
						extend:    'pdfHtml5',
						text:      '<i class="fa fa-file-pdf"></i>',
						titleAttr: 'Export to PDF',
						orientation: 'portrait', 
						pageSize: 'A4',
						exportOptions: {
							columns: "thead th:not(.noExport)"
						  },
						footer: true,
				  		action: newexportaction
					},
					{
					  extend:    'print', 
					  text:      '<i class="fa fa-print"></i>',
					  titleAttr: 'Print',
					  orientation: 'portrait', 
					  pageSize: 'A4',
					  exportOptions: {
						  columns: "thead th:not(.noExport)"
						},
					  footer: true,
				 	  action: newexportaction
					}
				],
            "processing": true,
            "serverSide": true,
            "ajax":{
		     "url": "<?php echo base_url('frontend/member/posts') ?>",
		     "dataType": "json",
		     "type": "POST",
		     "data": function(data){  
                            data.member_dept = $('#member_dept').val();
                            data.member_sub_dept = $('#member_sub_dept').val();
                            data.client_name = '<?php echo $client ;?>';
                            data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>"; }
		                   },
		    "columns": [
			          { "data": "license_plate"},
			          { "data": "location"},			          
			          { "data": "dept"},
			          { "data": "tolltag"},
			          { "data": "vin_no"},
			          { "data": "unit" },
			          { "data": "color" },
			          { "data": "make"},
			          { "data": "model"},
			          { "data": "start_date"},
			          { "data": "end_date" },
			          { "data": "action"},
			       ]


	    });
    $('#search-nav').on('click change', function (event) {
    	event.preventDefault();
	    $('#clientModal').modal('hide');
	    dataTable.draw();
    } );
});

$(document).keypress(function(e){
    if (e.which == 13){
        $("#vehicleSave").click();
        return false;
    }
});
$(document).keypress(function(e){
    if (e.which == 13){
        $("#vehicleupdate").click();
        return false;
    }
});


function add_vehicle(){
    save_method = 'add';
    $('#new_vehicle')[0].reset();
    $('#msg').html(''); 
    // $('select[name="sub_dept"]').empty().hide(); 
    $('#vehicleModal').modal('show'); 
    $('.modal-title').text('Add Vehicle'); 
}

function edit_vehicle(id){
    save_method = 'update';
    $('#new_vehicle')[0].reset(); 
    // $('select[name="sub_dept"]').empty().hide();
    $('#msg').html('');
    $.ajax({
        url : "<?php echo site_url('frontend/member/edit_vehicle')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.vehicle.vehicle_id);
                $('[name="client_id"]').val(data.vehicle.client_id);
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
                $('input:radio[name="status"][value="' + data.vehicle.vehicle_status + '"]').prop("checked", true);

                $('select[name="dept"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.dept_id;
                }).prop('selected', true);
                if (data.vehicle.organization == 'protech_as') {
                	 if (data.vehicle.tag !== null) {
						
							var exists = $('select[name="tolltag"] option[value='+data.vehicle.tag+']').length;
							if (exists != 0) {
								$('select[name="tolltag"] option').prop('selected', false).filter(function(){
			                    return $(this).val() == data.vehicle.tolltag;
			                	}).prop('selected', true);
							} else {
								$('select[name="tolltag"] option').prop('selected', false).filter(function(){
			                    return $(this).val() == "other";
			                	}).prop('selected', true);
								$('#custom_input_edit').show();
								$('[name="tolltag2"]').val(data.vehicle.tolltag);
							}
                	 	} else {
                	 		
                		}

                } else {
                	 $('[name="tolltag"]').val(data.vehicle.tolltag);
                }
                $('[name="vin_no"]').val(data.vehicle.vin_no);
                // if(data.vehicle.sub_dept_id !== null || data.vehicle.sub_dept_id !== 0){
                //     $('select[name="sub_dept"]').empty().append('<option value="'+ data.vehicle.sub_dept_id +'">'+ data.vehicle.sub_dept_name +'</option>').show();   
                // }
                $('#vehicle_modal').modal('show'); 
                $('.modal-title').text('Update Vehicle');
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
        d = $('#new_vehicle').serialize();
    } else {
        url = "<?php echo site_url('frontend/member/update_vehicle')?>";
        d = $('#update_vehicle').serialize();
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: d,
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#vehicleModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{

                	if(save_method == 'add') {
				        $('#vehicle_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
				    } else {
				      	$('#vehicle_msg2').removeClass('text-success').addClass('text-danger').html(data.msg);
				    }
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