<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
    <div class="content">
        <div class="page-inner">
            <div class="row">
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-header">
                          <div class="card-head-row">
                              <div class="card-title"><i class="fa fa-edit"></i> Edit <span class="text-muted"><?php echo ucwords(str_replace('_', ' ', $client->organization));?>'s</span> Information</div>
                              <div class="card-tools">
                                  <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                              </div>
                          </div>
                      </div>
				<div class="card-body">
				<div id="msg"><?php echo $this->session->flashdata('message'); ?></div>
				    <?php echo form_open_multipart('backend/admin/update_client_dept', ['class' => 'form-horizontal', 'id' => 'new_client', 'method' => 'POST']);?>
				    <input type="hidden" name="id" value="<?php echo $client->dept_id ?>" />
			    	<div class="col-md-12">
					    <div class="col-md-6">
					    	<div class="form-group">
					    		<div class="row">
					    		<div class="col-md-4"><label class="control-label">Department Name:</label></div>
					    		<div class="col-md-8">
					    			<input name="company" value="<?php echo (isset($client->organization) && $client->organization != NULL) ? ucwords(str_replace('_', ' ', $client->organization)) : '';?>" placeholder="Depertment Name" class="form-control input-sm" type="text" />
					    		<span class="help_block"><?php echo form_error('company')?></span>
						    	</div>
						    </div>
					    	</div>
					    </div>
					    <div class="col-md-6">
					    	<div class="form-group">
					    		<div class="row">
					    		<div class="col-md-4"><label class="control-label">Address:</label></div>
					    		<div class="col-md-8">
						    		<input name="address" value="<?php echo (isset($client->address) && $client->address != NULL) ? $client->address : ''?>" placeholder="Postal Address" class="form-control input-sm" type="text" />
						    		<span class="help_block"><?php echo form_error('address')?></span>
						    	</div>
						    </div>
					    	</div>
					    </div>
					    <div class="col-md-6">
					    	<div class="form-group">
					    		<div class="row">
						    		<div class="col-md-4"><label class="control-label">Department Phone:</label></div>
						    		<div class="col-md-8">
							    		<input name="company_phone" value="<?php echo (isset($client->dept_id) && $client->dept_id != NULL) ? $client->company_phone : ''?>" placeholder="Department Phone" class="form-control input-sm" type="text" />
							    		<span class="help_block"><?php echo form_error('company_phone')?></span>
							    	</div>
							    </div>
					    	</div>
					    </div>
					    <div class="col-md-6">
					    	<div class="form-group">
					    		<div class="row">
					    		<div class="col-md-4"><label class="control-label">Company Email:</label></div>
					    		<div class="col-md-8">
						    		<input name="email"  value="<?php echo (isset($client->org_email) && $client->org_email != NULL) ? $client->org_email : ''?>" placeholder="Email Address" class="form-control input-sm" type="text" />
						    		<span class="help_block"><?php echo form_error('email')?></span>
						    	</div>
						    </div>
					    	</div>
					    </div>
					    <div class="col-md-6">
					    	<div class="form-group">
					    		<div class="row">
					    		<div class="col-md-4"><label class="control-label">Category:</label></div>
					    		<div class="col-md-8">
						    		<select name="category" class="form-control input-sm">
						    			<option value="" selected="selected" disabled="disabled">--Select category--</option>
						    			<?php foreach ($categories as $c) {?>
						    			<option value="<?php echo $c->category_id;?>" <?php echo (isset($client->category_id) && $client->category_id != NULL) ? set_select('category', "$c->category_id")  : '';?>><?php echo ucwords($c->category_name);?></option>
						    			<?php }?>
						    		</select>
						    		<span class="help_block"><?php echo form_error('category')?></span>
						    	</div>
						    </div>
					    	</div>
					    </div>
					    <div class="col-md-6">		    	
					    	<div class="form-group">
					    		<div class="row">
						    		<div class="col-md-2"><label class="control-label">Logo:</label></div>
						    		<div class="col-md-2"><input name="logo"  type="file" /></div>
						    		<div class="col-md-8"><span class="pull-right"><img src="<?php echo base_url('assets/images/client_logo/')?><?php echo (isset($client->logo) && $client->logo != NULL) ? $client->logo : ''?>" class="img-responsive" style="width: 100px; height: 50px; border: solid #999 1px;" /></span></div>
						    	</div>
					    	</div>
					    </div>
					    </div>
					    <div class="col-md-6 ml-auto mr-auto text-center">
			    			<div class="form-group" style="margin-top: 10px;">
			    				<button type="submit" class="btn btn-sm btn-block btn-secondary"><strong><i class="fa fa-refresh"></i> Update Depertment</strong></button>
			    			</div>
			    		</div>
				    <?php echo form_close()?>
			    </div>
			    </div><!-- card end-primary -->
                </div>
              </div>
          </div>
        </div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<?php $this->load->view('templates/includes/footer_end'); ?>