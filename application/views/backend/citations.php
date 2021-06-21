<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-file-excel"></i> Red Light & Parking Citations</div>
                            <div class="card-tools">
                                <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
						<?php echo $this->session->flashdata('message');?>
						<?php echo form_open_multipart('backend/admin/save_citations', ['class' => 'form-horizontal', 'id' => 'new_agency', 'method' => 'POST']);?>
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
					            	<select name="vehicle" class="form-control input-sm">
					                    <option selected="selected" disabled="disabled" value="">--Select vehicle--</option>
					                </select>
				                    <span class="help_block"><?php echo form_error('vehicle')?></span>
					            </div>
					            <div class="col-md-4">
				                    <div class='input-group date' id="citation_date">
				                        <input type='text' name="citation_date" class="form-control input-sm" value="<?php echo set_value('citation_date')?>"  placeholder="Paid date" />
				                        <div class="input-group-append">
					                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
					                        </div>
				                    </div>
				                    <span class="help_block"><?php echo form_error('citation_date')?></span>
					            </div>
					        </div>
					        </div>
					        <div class="form-group">
					        	<div class="row">
					            <div class="col-md-4">
				                    <select name="citation_agency" class="form-control input-sm">
					                    <option selected="selected" disabled="disabled" value="">--Select agency--</option>
					                    <?php foreach ($agencies as $agency) {?>
					                    <option value="<?php echo $agency->agency_name;?>"   <?php echo  set_select('citation_agency', "$agency->agency_name");?>><?php echo ucwords($agency->agency_name);?></option>
					                    <?php }?>
					                </select>
					                <span class="help_block"><?php echo form_error('citation_agency')?></span>
					            </div>
					            <div class="col-md-4">
					            	<input name="fees_amount" placeholder="Fees amount" class="form-control input-sm"  value="<?php echo set_value('fees_amount')?>" type="text" />
					            	<span class="help_block"><?php echo form_error('fees_amount')?></span>
					            </div>
					            <div class="col-md-4">
				                    <input name="paid_amount" placeholder="Paid amount" class="form-control input-sm"  value="<?php echo set_value('paid_amount')?>" type="text" />
				                    <span class="help_block"><?php echo form_error('paid_amount')?></span>
					            </div>
					        </div>
					        </div>
					        <div class="form-group">
					        	<div class="row">
					            <div class="col-md-4">
				                    <select name="citation_status" class="form-control input-sm">
					                    <option value="1" <?php echo  set_select('citation_status', 1);?>>Open</option>
					                    <option value="0" <?php echo  set_select('citation_status', 0);?>>Resolved</option>
					                </select>
					                <span class="help_block"><?php echo form_error('citation_status')?></span>
					            </div>
					            <div class="col-md-4">
				                    <select name="type" class="form-control input-sm">
					                    <option value="rl" <?php echo  set_select('type', 'rl');?>>Red Light</option>
					                    <option value="pk" <?php echo  set_select('type', 'pk');?>>Parking</option>
					                </select>
					                <span class="help_block"><?php echo form_error('type')?></span>
					            </div>
					            <div class="col-md-4">
				                    <input name="citation_amount" placeholder="Citation amount" class="form-control input-sm"  value="<?php echo set_value('citation_amount')?>" type="text" />
				                    <span class="help_block"><?php echo form_error('citation_amount')?></span>
					            </div>
					        </div>
					        </div>
					        <div class="form-group">
					        	<div class="col-md-8 ml-auto mr-auto ">
					        	<button type="submit" class="btn btn-sm btn-block btn-primary"><strong><i class="fa fa-save"></i> Save Citation</strong></button>
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
        var url = "<?php echo base_url('backend/admin/client_citation_vehicles')?>/"+client;

        if(client) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data) {
                   if (data.length > 0) {
                     $('select[name="vehicle"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="vehicle"]').append('<option value="'+ value.license_plate +'">'+ value.license_plate +'</option>');
                    });
                    }else{
                        $('select[name="vehicle"]').empty();
                        $('select[name="vehicle"]').append('<option value="">No vehicle(s) found for selected client </option>');
                    }
                }
            });
        }
    });
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>