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
		<?php echo form_open_multipart('admin/update_citations', ['class' => 'form-horizontal', 'id' => 'new_agency', 'method' => 'POST']);?>
			<input type="hidden" name="citation_id" value="<?php echo $citation->citation_id;?>" />
	        <div class="form-group">
	        	<div class="row">
	            <div class="col-md-4">
                    <select name="client" class="form-control input-sm">
	                    <option selected="selected" disabled="disabled" value="">--Select client--</option>
	                    <?php foreach ($clients as $client) {?>
	                    <option value="<?php echo $client->organization;?>" <?php if ($citation->organization == $client->organization) echo 'selected' ; ?>><?php echo ucwords($client->organization);?></option>
	                    <?php }?>
	                </select>
	                <span class="help_block"><?php echo form_error('client')?></span>
	            </div>
	            <div class="col-md-4">
	            	<select name="vehicle" class="form-control input-sm">
	                    <option value="<?php echo $citation->license_plate;?>"><?php echo $citation->license_plate;?></option>
	                </select>
                    <span class="help_block"><?php echo form_error('vehicle')?></span>
	            </div>
	            <div class="col-md-4">
                    <div class='input-group date' id="citation_date">
                        <input type='text' name="citation_date" class="form-control input-sm" value="<?php echo $citation->paid_date?>"  placeholder="Paid date" />
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                    <span class="help_block"><?php echo form_error('citation_date')?></span>
	            </div>
	        </div>
	        </div>
	        <div class="form-group">
	        	<div class="row">
	            <div class="col-md-4">
                    <input type="text" name="payable_to" class="form-control input-sm" value="<?php echo $citation->payable_to?>" />
	            </div>
	            <div class="col-md-4">
	            	<input name="fees_amount"  value="<?php echo $citation->citation_fee?>" placeholder="Fees amount" class="form-control input-sm"  value="<?php echo set_value('fees_amount')?>" type="text" />
	            	<span class="help_block"><?php echo form_error('fees_amount')?></span>
	            </div>
	            <div class="col-md-4">
                    <input name="paid_amount"  value="<?php echo $citation->paid_amount?>" placeholder="Paid amount" class="form-control input-sm"  value="<?php echo set_value('paid_amount')?>" type="text" />
                    <span class="help_block"><?php echo form_error('paid_amount')?></span>
	            </div>
	        </div>
	        </div>
	        <div class="form-group">
	        	<div class="row">
	            <div class="col-md-4">
                    <input type="text" name="citation_status" class="form-control input-sm" value="<?php echo $citation->citation_status?>" />
	            </div>
	            <div class="col-md-4">
                    <select name="type" class="form-control input-sm">
	                    <option value="ST" <?php if ($citation->citation_type == 'ST') echo 'selected' ; ?>>Speed Ticket</option>
	                    <option value="RL" <?php if ($citation->citation_type == 'RL') echo 'selected' ; ?>>Red Light</option>
	                    <option value="PK" <?php if ($citation->citation_type == 'PK') echo 'selected' ; ?>>Parking</option>
	                </select>
	                <span class="help_block"><?php echo form_error('type')?></span>
	            </div>
	            <div class="col-md-4">
                    <input name="citation_amount" placeholder="Citation amount" class="form-control input-sm"  value="<?php echo $citation->citation_amount?>" type="text" />
                    <span class="help_block"><?php echo form_error('citation_amount')?></span>
	            </div>
	        </div>
	        </div>
	        <div class="form-group">
	        	<div class="col-md-8 ml-auto mr-auto">
	        	<button type="submit" class="btn btn-sm btn-block btn-primary"><strong><i class="fa fa-save"></i> Update Citation</strong></button>
	        	</div>
	        </div>
	    </form>
		</div>
	</div>
</div>
</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<?php $this->load->view('templates/includes/footer_end'); ?>