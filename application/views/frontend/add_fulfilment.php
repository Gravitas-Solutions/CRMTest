<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fas fa-truck-pickup"></i> Make transponder order</div>
                                    <div class="card-tools">                                       </a>
                                        <a href="<?php echo base_url('transponder-fulfilment') ?>"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
								<?php echo $this->session->flashdata('message');?>
								<?php echo form_open_multipart('frontend/member/transponder_fulfilment', ['class' => 'form-horizontal', 'id' => 'new_agency', 'method' => 'POST']);?>
							        <div class="form-group">
						                <div class="row">
						                <div class="col-md-2"><label class="control-label">Quantity: </label></div>
						                <div class="col-md-4">
						                    <input name="quantity" placeholder="Transponder Quantity" class="form-control input-sm" type="number" step="1" />
						                </div>
						                <div class="col-md-2"><label class="control-label">Extra velcro: </label></div>
						                <div class="col-md-4">
						                    <select name="dept" class="form-control input-sm" style="width: 100%">
						                        <option value="" selected="selected"  disabled="disabled">--Select client first--</option>
						                    </select>
						                </div>
						                </div>
						            </div>
						            <div class="form-group">
						                <div class="row">
						                    <div class="col-md-2"><label class="control-label"><abbr title="License Plate">Asset</abbr>: </label></div>
						                    <div class="col-md-4">
						                        <select name="vehicle" class="form-control input-sm" style="width: 100%">
						                            <option value="">--Select client/dept first--</option>
						                        </select>
						                    </div>
						                    <div class="col-md-2"><label class="control-label"><abbr title="License Plate">Shipping address</abbr> State: </label></div>
						                    <div class="col-md-4">
						                        <select name="license_plate_state" class="form-control input-sm" style="width: 100%">
						                            <option value="" selected disabled>--License Plate state--</option>
						                            <?php foreach ($states as $state) {?>
						                            <option value="<?php echo $state->state_code;?>"><?php echo ucwords($state->state_name);?></option>
						                            <?php }?>
						                        </select>
						                    </div>
						                </div>
						            </div>
						            <div class="form-group">
						                <div class="row">
						                <div class="col-md-2"><label class="control-label">Domicile Terminal: </label></div>
						                <div class="col-md-4">
						                    <select name="type" class="form-control input-sm">
						                        <option value="" selected disabled>--Select citation type--</option>
						                        <option value="ST">Speed Ticket</option>
						                        <option value="RL">Red Light</option>
						                        <option value="PK">Parking</option>
						                    </select>
						                </div>
						                <div class="col-md-2"><label class="control-label">Citation Status: </label></div>
						                <div class="col-md-4">
						                    <select name="citation_status" class="form-control input-sm">
						                        <option value="">-- Select citation status</option>
						                        <option value="0">Open</option>
						                        <option value="1">Closed</option>
						                    </select>
						                </div>
						            </div>
						            </div>
						            <div class="form-group">
						                <div class="row">
						                <div class="col-md-2"><label class="control-label">Citation Date: </label></div>
						                <div class="col-md-4">
						                    <div class='input-group date' id="citation_date">
						                        <input type='text' name="violation_date" class="form-control input-sm" placeholder="Violation date" />
						                        <span class="input-group-addon">
						                            <span class="fa fa-calendar"></span>
						                        </span>
						                    </div>
						                </div>
						                <div class="col-md-2"><label class="control-label">Citation Amount: </label></div>
						                <div class="col-md-4">
						                    <input name="citation_amount" placeholder="Citation amount" class="form-control input-sm" type="number" step="0.01" />
						                </div>
						            </div>
						            </div>
						            <div class="form-group">
						                <div class="row">
						                <div class="col-md-2"><label class="control-label">Fees Amount: </label></div>
						                <div class="col-md-4">
						                    <input name="fees_amount" placeholder="Fees amount" class="form-control input-sm" type="number" step="0.01" />
						                </div>
						                <div class="col-md-2"><label class="control-label">Paid Amount: </label></div>
						                <div class="col-md-4">
						                    <input name="paid_amount" placeholder="Paid amount" class="form-control input-sm" type="number" step="0.01" />
						                </div>
						            </div>
						            </div>
						            <div class="form-group">
						                <div class="row">
						                <div class="col-md-2"><label class="control-label"> Asset Details: </label></div>
						                <div class="col-md-4">
						                    <input name="payable_to" placeholder="Payable to" class="form-control input-sm" type="text" />
						                </div>
						                <div class="col-md-2"><label class="control-label">Shipping list:: </label></div>
						                <div class="col-md-4">
						                    <div class='input-group date' id="paid_date">
						                        <input type='text' name="paid_date" class="form-control input-sm" placeholder="Paid date" />
						                        <span class="input-group-addon">
						                            <span class="fa fa-calendar"></span>
						                        </span>
						                    </div>
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