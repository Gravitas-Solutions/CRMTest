<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-file-excel"></i> Client-specific Invoice</div>
                                    <div class="card-tools">                                       </a>
                                        <a href="<?php echo base_url('manage-invoices') ?>"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
								<?php echo $this->session->flashdata('message');?>
								<?php echo form_open_multipart('backend/admin/client_invoices', ['class' => 'form-horizontal', 'id' => 'new_agency', 'method' => 'POST']);?>
						      	<input type="hidden" value="" name="id"/> 
							        <div class="form-group">
							        	<div class="row">
								            <div class="col-md-4">
							                    <select name="client" class="form-control input-sm">
								                    <option selected="selected" disabled="disabled" value="">--Select client--</option>
								                    <?php foreach ($clients as $client) {?>
								                    <option value="<?php echo $client->organization;?>"   <?php echo  set_select('client', "$client->organization");?>><?php echo ucwords($client->organization);?></option>
								                    <?php }?>
								                </select>
								                <span class="help_block"><?php echo form_error('client')?></span>
								            </div>

								            <div class="col-md-4">
							                    <select name="dept" class="form-control input-sm">
															<option value="" selected="selected"  disabled="disabled">--Select Client First--</option>
														</select>
								                <span class="help_block"><?php echo form_error('dept')?></span>
								            </div>

								            <div class="col-md-4">
							                    <div class='input-group date' id="invoice_date">
							                        <input type='text' name="invoice_date" class="form-control input-sm" value="<?php echo set_value('invoice_date')?>"  placeholder="Invoice date" />
							                        <div class="input-group-append">
							                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
							                        </div>
							                    </div>
							                    <span class="help_block"><?php echo form_error('invoice_date')?></span>
								            </div>
							            </div>
							        </div>
							        <div class="form-group">
							        	<div class="row">
								        	<div class="col-md-4">
							                    <div class='input-group date' id="pay_date">
							                        <input type='text' name="pay_date" class="form-control input-sm" value="<?php echo set_value('pay_date')?>"  placeholder="Paid date" />
							                       <div class="input-group-append">
							                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
							                        </div>
							                    </div>
							                    <span class="help_block"><?php echo form_error('pay_date')?></span>
								            </div>
								            <div class="col-md-4">
								            	<input name="invoice_amount" placeholder="Invoice amount" class="form-control input-sm"  value="<?php echo set_value('invoice_amount')?>" type="text" />
								            	<span class="help_block"><?php echo form_error('invoice_amount')?></span>
								            </div>
								            <div class="col-md-4">
								            	<input name="toll_amount" placeholder="Toll amount" class="form-control input-sm"  value="<?php echo set_value('toll_amount')?>" type="text" />
								            	<span class="help_block"><?php echo form_error('toll_amount')?></span>
								            </div>
								        </div>
							        </div>
							        <div class="form-group">
							        	<div class="row">
								       		<div class="col-md-4">
							                    <select name="fee_type" class="form-control input-sm">
								                    <option selected="selected" disabled="disabled" value="">--Select Fee Type--</option>
								                    <option value="toll_fees">Toll Fees</option>
								                    <option value="admin_fees">Admin Fees</option>
								                    <option value="service_fees">Service Fees</option>
								                </select>
								                <span class="help_block"><?php echo form_error('fee_type')?></span>
								            </div>
								       		<div class="col-md-4">
							                    <input name="toll_fees" placeholder="Fees amount" class="form-control input-sm"  value="<?php echo set_value('toll_fees')?>" type="text" />
							                    <span class="help_block"><?php echo form_error('toll_fees')?></span>
								            </div>
								            <div class="col-md-4">
							                    <input name="total_paid" placeholder="Total paid" class="form-control input-sm"  value="<?php echo set_value('total_paid')?>" type="text" />
							                    <span class="help_block"><?php echo form_error('total_paid')?></span>
								            </div>
								        </div>
							        </div>
							        <div class="form-group">
							        	<div class="row">
								            <div class="col-md-6">
								            	<div class="row">
									            	<div class="col-md-3"><label class="control-label">Excel file:</label></div>
									            	<div class="col-md-3"><input name="client_excel" type="file" /></div>
									            </div>
								            </div>
								            <div class="col-md-6">
								            	<div class="row">
									            	<div class="col-md-3"><label class="control-label">PDF file:</label></div>
									            	<div class="col-md-3"><input name="client_pdf" type="file" /></div>
									            </div>
								            </div>
								        </div>
								        <div class="form-group">
								        	<div class="col-md-8 col-md-offset-2 ml-auto mr-auto">
								        	<button type="submit" class="btn btn-sm btn-block btn-primary"><strong><i class="fa fa-save"></i> Upload Client Data</strong></button>
								        	</div>
								        </div>
								    </div>
							    </form>
							</div>
					</div>
				</div>
			</div>
		</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
	$('select[name="client"]').on('change', function() {
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

</script>
<?php $this->load->view('templates/includes/footer_end'); ?>