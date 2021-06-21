<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
	                    <div class="card-header">
	                        <div class="card-head-row">
	                            <div class="card-title"><i class="fa fa-file-excel"></i> Edit Client-specific Invoice</div>
	                            <div class="card-tools">                                       </a>
	                                <a href="<?php echo base_url('manage-invoices') ?>"><i class="fas fa-step-backward fa-1x"></i> </a>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="card-body">
							<?php echo $this->session->flashdata('message');?>
							<?php echo form_open_multipart('backend/admin/edit_client_invoice', ['class' => 'form-horizontal', 'method' => 'POST']);?>
					      		<input type="hidden" value="<?php echo $invoice->invoice_id?>" name="id"/> 
						        <div class="form-group">
						        	<div class="row">
							            <div class="col-md-4">
							            	<div class="row">
								            	<div class="col-md-4"><label class="control-label">Client:</label></div>
								            	<div class="col-md-8">
							                    <select name="client" class="form-control input-sm" value="<?php echo set_value('client')?>">
								                    <option selected="selected" disabled="disabled" value="">--Select client--</option>
								                    <?php foreach ($clients as $client) {?>
								                    <option value="<?php echo $client->organization;?>"  <?php echo  set_select('client', $client->organization);?> <?php if ($client->organization == $invoice->client_name) echo 'selected' ; ?>><?php echo ucwords($client->organization);?></option>
								                    <?php }?>
								                </select>
								                <span class="help_block"><?php echo form_error('client')?></span>
								                </div>
							            	</div>
							            </div>
							            <div class="col-md-4">
							            	<div class="row">
								            	<div class="col-md-4"><label class="control-label">Department:</label></div>
							                    <div class="col-md-8">
							                     <select name="dept" class="form-control input-sm">
														<?php foreach ($client_depts as $cd) {?>
									                    <option value="<?php echo $cd->dept_id;?>"  <?php echo  set_select('dept', $cd->dept_id);?> <?php if ($cd->dept_id == $invoice->dept) echo 'selected' ; ?>><?php echo ucwords($cd->dept_name);?></option>
									                    <?php }?>
														</select>
								                <span class="help_block"><?php echo form_error('dept')?></span>
								            	</div>
								            </div>
							            </div>

							            <div class="col-md-4">
							            	<div class="row">
								            	<div class="col-md-4"><label class="control-label">Invoice Date:</label></div>
							                    <div class="col-md-8">
							                    <div class='input-group date' id="invoice_date">
							                        <input type='text' name="invoice_date" class="form-control input-sm" value="<?php echo $invoice->invoice_date?>"  placeholder="Invoice date" />
							                        <div class="input-group-append">
							                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
							                        </div>
							                    </div>
							                    <span class="help_block"><?php echo form_error('invoice_date')?></span>
							                    </div>
								            </div>
							            </div>
							        </div>
						    	</div>
						        <div class="form-group">
						        	<div class="row">
						        	<div class="col-md-4">
							            <div class="row">
							        		<div class="col-md-4"><label class="control-label">Pay date:</label></div>
						                    <div class="col-md-8">
						                    <div class='input-group date' id="pay_date">
						                        <input type='text' name="pay_date" class="form-control input-sm" value="<?php echo $invoice->pay_date?>"  placeholder="Paid date" />
						                        <div class="input-group-append">
					                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
					                        </div>
						                    </div>
						                    <span class="help_block"><?php echo form_error('pay_date')?></span>
						                </div>
						            </div>
						            </div>
						            <div class="col-md-4">
							            <div class="row">
							            	<div class="col-md-4"><label class="control-label">Invoice Amount:</label></div>
						                    <div class="col-md-8">
							            		<input name="invoice_amount" placeholder="Invoice amount" class="form-control input-sm"  value="<?php echo $invoice->invoice_amount?>" type="text" />
							            	<span class="help_block"><?php echo form_error('invoice_amount')?></span>
							            	</div>
							            </div>
							        </div>
						            <div class="col-md-4">
						            	<div class="row">
							            	<div class="col-md-4"><label class="control-label">Toll Amount:</label></div>
						                    <div class="col-md-8">
							            	<input name="toll_amount" placeholder="Toll amount" class="form-control input-sm"  value="<?php echo $invoice->toll_amount?>" type="text" />
							            	<span class="help_block"><?php echo form_error('toll_amount')?></span>
							            	</div>
							            </div>
							        </div>
							        </div>
						        </div>
						        <div class="form-group">
						        	<div class="row">
						       		<div class="col-md-4">
						       			<div class="row">
							       			<div class="col-md-4"><label class="control-label">Fee Type:</label></div>
						                    <div class="col-md-8">
						                   <select name="fee_type" class="form-control input-sm" value="<?php echo set_value('fee_type')?>">
							                    <option selected="selected" disabled="disabled" value="">--Select Fee Type--</option>
							                    <option value="toll_fees" <?php echo  set_select('fee_type', 'toll_fees');?> <?php if ($invoice->fee_type == 'toll_fees') echo 'selected' ; ?>>Toll Fees</option>
							                    <option value="admin_fees" <?php if ($invoice->fee_type == 'admin_fees') echo 'selected' ; ?> >Admin Fees</option>
							                    <option value="service_fees" <?php if ($invoice->fee_type == 'service_fees') echo 'selected' ; ?> >Service Fees</option>
							                </select>
							                <span class="help_block"><?php echo form_error('fee_type')?></span>
							            	</div>
							            </div>
						            </div>
						       		<div class="col-md-4">
						       			<div class="row">
							       			<div class="col-md-4"><label class="control-label">Fees Amount:</label></div>
						                    <div class="col-md-8">
						                    <input name="toll_fees" placeholder="Fees amount" class="form-control input-sm"  value="<?php echo $invoice->toll_fee?>" />
						                    <span class="help_block"><?php echo form_error('toll_fees')?></span>
						                	</div>
						                </div>
						            </div>
						            <div class="col-md-4">
						            	<div class="row">
							            	<div class="col-md-4"><label class="control-label">Toll Paid:</label></div>
						                    <div class="col-md-8">
						                    <input name="total_paid" placeholder="Total paid" class="form-control input-sm"  value="<?php echo $invoice->paid_amount?>" type="text" />
						                    <span class="help_block"><?php echo form_error('total_paid')?></span>
						                 </div>
						             </div>
						            </div>
						        </div>
						        </div>
						        <div class="form-group">
						        	<div class="row">
							            <div class="col-md-6">
							            	<div class="row">
								            	<div class="col-md-3"><label class="control-label">Excel file:</label></div>
								            	<div class="col-md-1"><a href="<?php echo base_url();?>uploads/client_invoices/<?php echo $invoice->excel;?>" ><i class="fa fa-file-excel fa-2x text-success" title="Download Excel"></i></a></div>
								            	<div class="col-md-2"><input name="client_excel" type="file" /></div>
								            </div> 	
							            </div>
							            <div class="col-md-6">
							            	<div class="row">
								            	<div class="col-md-3"><label class="control-label">PDF file:</label></div>
								            	<div class="col-md-1"><a href="<?php echo base_url();?>uploads/client_invoices/<?php echo $invoice->pdf;?>" ><i class="fa fa-file-pdf fa-2x text-danger" title="Download PDF"></i> </a></div>
								            	<div class="col-md-2"><input name="client_pdf" type="file" /></div>
								            </div>
							            </div>
						        	</div>
						        </div>
						        <div class="form-group">
						        	<div class="col-md-8 col-md-offset-2 ml-auto mr-auto">
						        	<button type="submit" class="btn btn-sm btn-block btn-primary"><strong><i class="fa fa-save"></i> Update Invoice Data</strong></button>
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
        var url = "<?php echo base_url('backend/admin/departments')?>/"+client;

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