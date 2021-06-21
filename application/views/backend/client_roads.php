<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
    <div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                             <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-road"></i> Tollway Spending per Client</div>
                                    <div class="card-tools">
                                         <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
					<?php echo $this->session->flashdata('message'); ?>
					<?php echo form_open(base_url('admin/roads')); ?>
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">Select client:</label>
						</div>
						<div class="col-md-3">
							<select name="client" class="form-control input-sm">
								<option value="" selected="selected"  disabled="disabled">--Select client--</option>
								<?php foreach ($clients as $client) {?>
								<option value="<?php echo $client->organization;?>"><?php echo ucwords(str_replace('_', ' ', $client->organization));?></option>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2">
							<button type="submit" name="show_roads" class="btn btn-sm btn-default"><i class="fa fa-list"></i> Show Tollway Spending</button>
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