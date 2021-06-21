<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fas fa-file-excel"></i> Multiple Vehicle Upload </div>
                                    <div class="card-tools">
                                        <a href="<?php echo base_url('vehicles-managment') ?>"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
								<?php echo $this->session->flashdata('message'); ?>		
								<?php echo form_open_multipart(base_url('backend/admin/vehicle_import'), ['id' => 'excel_form', 'class' => 'form-horizontal']); ?>						
										<div class="form-group">
											<div class="row">
												<div class="col-md-1">
													<label class="control-label">Client:</label>
												</div>
												<div class="col-md-3">
													<select name="client" class="form-control input-sm">
														<option value="" selected="selected"  disabled="disabled">--Client--</option>
														<?php foreach ($clients as $client) {?>
														<option value="<?php echo $client->organization;?>"><?php echo ucwords(str_replace('_', ' ', $client->organization));?></option>
														<?php }?>
													</select>
												</div>
												<div class="col-md-1">
													<label class="control-label">Dept:</label>
												</div>
												<div class="col-md-3">
													<select name="dept" class="form-control input-sm">
														<option value="" selected="selected"  disabled="disabled">--Select Client First--</option>
													</select>
												</div>
							                    <div class="col-md-3" style="display: none">
							                        <select name="sub_dept" class="form-control input-sm">
							                        	<option value="" selected="selected"  disabled="disabled">--Select Dept First--</option>
							                        </select>
							                    </div>
											</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class=" col-md-4 form-check">
								                <label class="ml-3"><em>Draft/Missing?</em></label>
								                <label class="form-radio-label ml-3">
								                    <input class="form-radio-input" type="radio" name="draft" value="0" checked="checked">
								                    <span class="form-radio-sign">No</span>
								                </label>
								                <label class="form-radio-label ml-3">
								                    <input class="form-radio-input" type="radio" name="draft" value="1">
								                    <span class="form-radio-sign">Yes</span>
								                </label>
											</div>
							                <div class="col-md-1">
												<label class="control-label">File:</label>
											</div>
											<div class="col-md-3">
												<input type="file" name="excel_data" id="excel_data" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6 ml-auto mr-auto">
												<input type="submit" name="upload" class="btn btn-sm btn-primary btn-block" value="Upload" />
											</div>
										</div>
									</div>
								</form>
								<hr>
								<div class="table-responsive">
					                <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary">
					                    <thead>
					                        <th>#</th>
					                        <th>Client</th>
					                        <th>Department</th>
					                        <th>Sub-Department</th>
					                        <th>Agent</th>
					                         <th>Uploaded On </th>
					                        <th># of Records</th>
					                        <th>File Type</th>
					                        <th>File</th>
					                        <th><i class="fa fa-file-excel-o"></i></th>
					                        <th><i class="fa fa-undo"></i></th>
					                    </thead>
					                    <tbody>
					                        <?php $i = 0; foreach ($excel_dumps as $dump) {?>
					                        	<?php $uploader = $this->ion_auth->user($dump->uploaded_by)->row()->email ?>
					                        <tr>
					                            <td><?php echo ++$i; ?></td>
					                            <td><?php echo ucwords(str_replace('_', ' ', $dump->client_name));?></td>
					                            <td><?php echo ($dump->dept_name) ? ucwords(str_replace('_', ' ', $dump->dept_name)): "<center>--</center>";?></td>
					                            <td align="center"><?php echo ($dump->sub_dept_name) ? ucwords(str_replace('_', ' ', $dump->sub_dept_name)) : "<center>--</center>";?></td>
					                            <td><a href="<?php echo $uploader ?>"><?php echo $uploader ?></a></td>
					                            <td><?php echo nice_date($dump->uploaded_date, 'Y-m-d h:i:s');?></td>
					                            <td><?php echo $dump->total_row;?></td>
					                            <td><?php echo ($dump->draft == 1) ? 'Draft' : 'Active' ;?> </td>
					                            <td><?php echo $dump->filename;?></td>
					                            <td class="text-center"><a href="<?php echo base_url();?>uploads/vehicles/<?php echo $dump->filename;?>" > <i class="fa fa-download text-success"></i></a></td>
					                            <td class="text-center">
					                            <a onclick="undo(<?php echo $dump->vehicle_excel_dump_id;?>)" title = "Undo"><span><i class="fa fa-undo text-info"></i></span></a>
					                            </td>
					                        </tr>
					                        <?php } ?>
					                    </tbody>
					                </table>
					                </div>
					               </div> <!-- End of div table -->
							</div> <!-- End of card body -->
						</div> 
					</div>
				</div> 
		</div>  
</div> 
<?php $this->load->view('templates/includes/footer_start'); ?>

<script type="text/javascript">
	$('select[name="client"]').on('change', function() {
        $('select[name="sub_dept"]').parent().css('display', 'none');
        var client = $(this).val();
        var url = "<?php echo base_url('backend/admin/org_departments')?>/"+client;

        if(client) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data) {
                   if (data.length > 0) {
	                    $('select[name="dept"]').empty();
	                    $.each(data, function(key, value) {
	                        $('select[name="dept"]').append('<option value="'+ value.dept_id +'">'+ value.dept_name +'</option>');                        
	                    });
	                    if(data.length > 1){
	                    	$("select[name='dept'] option:contains('overview')").remove();
	                    }
                    }else{
                        $('select[name="dept"]').empty();
                        $('select[name="dept"]').append('<option value="">No department(s) found for selected client </option>');
                    }
                }
            });
        }
    });

    $('select[name="dept"]').on('change', function() {
        $('select[name="sub_dept"]').parent().css('display', 'none');
        var dept_id = $(this).val(); 
        var url = "<?php echo base_url('backend/admin/dept_sub_departments')?>/"+dept_id;
        if(dept_id) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    console.log(data);
                   if (data.length > 0) {
                        $('select[name="sub_dept"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="sub_dept"]').append('<option value="'+ value.sub_dept_id +'">'+ value.sub_dept_name +'</option>');
                            $('select[name="sub_dept"]').parent().css('display', 'block');
                            //$('select[name="sub_dept"]').show();
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

   	$('#excel_form').on('submit', function(){
	if ($('#excel_data').val() == '') {
		alert('Select file to upload');
		return false;
	}
    }); 

    var url="<?php echo base_url();?>";
    function undo(id){
       var r=confirm("Do you want to undo the upload?")
        if (r==true)
          window.location = url+"backend/admin/delete_vehicle_excel/"+id;
        else
          return false;
        }

</script>
<?php $this->load->view('templates/includes/footer_end'); ?>