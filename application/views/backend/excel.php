<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-file-excel"></i> New Transactions Dump</div>
                                    <div class="card-tools">
                                        <a href="<?php echo base_url()?>backend/admin/excel_listing" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-eye"></i> View Transactions Dumps</a>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
							<?php echo $this->session->flashdata('message'); ?>					
							<?php echo form_open_multipart(base_url('backend/admin/import_transactions'), ['id' => 'excel_form', 'class' => 'form-horizontal']); ?>
								<div class="form-group">
									<div class="row">
									<div class="col-md-2"><label class="control-label"><i class="fa fa-calendar"></i> Date For:</label></div>
									<div class="col-md-4">
										<div class='input-group date' id="date_for">
					                        <input type='text' id="date_for" name="date_for" class="form-control input-sm" value="<?php echo set_value('date_for')?>"  placeholder="YYYY-MM-DD" />
					                        <div class="input-group-append">
					                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
					                        </div>
					                    </div>
					                    <span class="help_block"><?php echo form_error('date_for')?></span>
									</div>
									<div class="col-md-2"><label class="control-label"><i class="fa fa-institution"></i> Client:</label></div>
									<div class="col-md-4">
										<select name="client" class="form-control input-sm">
											<option value="" selected="selected"  disabled="disabled">--Client--</option>
											<?php foreach ($clients as $client) {?>
											<option value="<?php echo $client->organization;?>"><?php echo ucwords(str_replace('_', ' ', $client->organization));?></option>
											<?php }?>
										</select>
					                    <span class="help_block"><?php echo form_error('client')?></span>
									</div>
									</div>
								</div>
								<div class="form-group">
										<div class="row">
											<div class="col-md-2"><label class="control-label"><i class="fa fa-file-excel-o"></i> File:</label></div>
											<div class="col-md-4">
												<input type="file" name="excel_data" id="excel_data" />
											</div>
												<div class="col-md-2" style="display: none">
													<label id="divl" class="control-label"><i class="fa fa-sitemap"></i> Dept:</label>
												</div>
												<div class="col-md-4" style="display: none">
							                        <select name="dept" class="form-control input-sm">
							                        	<option value="" selected="selected"  disabled="disabled">--Select Client First--</option>
							                        </select>
						                    	</div>
										</div>
								</div>
								<div class="form-group">
						        	<div class="col-md-6 ml-auto mr-auto">
						        	<button type="submit" name="upload" class="btn btn-sm btn-block btn-primary"><strong><i class="fa fa-save"></i> Upload Transactions</strong></button>
						        	</div>
						        </div>
							</form>
							<hr>
					Sample Formatting Excel File: <a href="<?php echo base_url()?>uploads/templates/general.xlsx" class="btn btn-xs btn-primary"><i class="fa fa-file-excel-o"></i> General template <i class="fa fa-download"></i></a>
					<a href="<?php echo base_url()?>uploads/templates/amazon.xlsx" class="btn btn-xs btn-info"><i class="fa fa-file-excel-o"></i> Amazon template <i class="fa fa-download"></i></a>
					</div>
				</div> <!-- End of div panel body -->
			</div> <!-- End of panel panel default -->
		</div>
	</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
    $('#excel_form').on('submit', function(){
    	if ($('#excel_data').val() == '') {
    		alert('Select file to upload');
    		return false;
    	}
    });

    $('select[name="client"]').on('change', function() {
        $('select[name="dept"]').parent().css('display', 'none');
        $('#divl').parent().css('display', 'none');
        var client = $(this).val();

        if(client) {
            $.ajax({
	            url: "<?php echo base_url('backend/admin/has_sub_depts')?>/"+client,
	            type: "GET",
	            dataType: "json",
	            success:function(data) {
	               if (data) {
	               		$.ajax({
			                url: "<?php echo base_url('backend/admin/org_departments')?>/"+client,
			                type: "GET",
			                dataType: "json",
			                success:function(data) {
			                   if (data.length > 0) {
				                    $('select[name="dept"]').empty();
				                    $.each(data, function(key, value) {
				                        $('select[name="dept"]').append('<option value="'+ value.dept_id +'">'+ value.dept_name +'</option>');
				                        $('#divl').parent().css('display', 'block');  
				                        $('select[name="dept"]').parent().css('display', 'block');                      
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
	                }else{

	                }
	            }
	        });
        }
    });
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>