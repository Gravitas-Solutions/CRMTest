<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
    <div class="content">
        <div class="page-inner">
            <div class="row">
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-header">
                          <div class="card-head-row">
                              <div class="card-title"><i class="fa fa-plus-circle"></i> New Department Registration</div>
                              <div class="card-tools">
                                  <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                              </div>
                          </div>
                      </div>

					<div class="card-body">
					<div id="msg"><?php echo $this->session->flashdata('message'); ?></div>
					    <?php echo form_open_multipart('backend/admin/create_dept/'.$this->uri->segment(4), ['class' => 'form-horizontal', 'id' => 'new_client', 'method' => 'POST']);?>
				    	<div class="row">
							<div class="col-md-6">
						    	<div class="text-center text-muted"><strong>Department Details</strong> <br /><hr /></div>
						    	<div class="form-group">
						    		<input name="company" value="<?php echo set_value('company')?>" placeholder="Department Name" class="form-control input-sm" type="text" />
						    		<span class="help_block"><?php echo form_error('company')?></span>
						    	</div>
						    	<div class="form-group">
						    		<input name="address" value="<?php echo set_value('address')?>" placeholder="Postal Address" class="form-control input-sm" type="text" />
						    		<span class="help_block"><?php echo form_error('address')?></span>
						    	</div>
						    	<div class="form-group">
						    		<input name="company_phone" value="<?php echo set_value('company_phone')?>" placeholder="Department Phone" class="form-control input-sm" type="text" />
						    		<span class="help_block"><?php echo form_error('company_phone')?></span>
						    	</div>
						    	<div class="form-group">
						    		<input name="org_email"  value="<?php echo set_value('org_email')?>" placeholder="Email Address" class="form-control input-sm" type="text" />
						    		<span class="help_block"><?php echo form_error('org_email')?></span>
						    	</div>
						    	<div class="form-group">
						    		<select name="category" class="form-control input-sm">
						    			<option value="" selected="selected" disabled="disabled">--Select category--</option>
						    			<?php foreach ($categories as $c) {?>
						    			<option value="<?php echo $c->category_id;?>" <?php echo  set_select('category', "$c->category_id");?>><?php echo ucwords($c->category_name);?></option>
						    			<?php }?>
						    		</select>
						    		<span class="help_block"><?php echo form_error('category')?></span>
						    	</div>
						    </div>
						    <div class="col-md-6">
						    	<div class="text-center text-muted"><strong>Contact Person [department system admin]</strong> <br /><hr ></div>
						    	<div class="form-group">
						    		<div class="row">
										<div class="col-md-6">
							    		<input name="firstname"  value="<?php echo set_value('firstname')?>" placeholder="First Name" class="form-control input-sm" type="text" />
							    		<span class="help_block"><?php echo form_error('firstname')?></span>
							    		</div>
							    		<div class="col-md-6">
							    		<input name="lastname"  value="<?php echo set_value('lastname')?>" placeholder="Last Name" class="form-control input-sm" type="text" />
							    		<span class="help_block"><?php echo form_error('lastname')?></span>
							    		</div>
							    	</div>
						    	</div>
						    	<div class="form-group">
						    		<div class="row">
										<div class="col-md-6">
								    		<input name="phone"  value="<?php echo set_value('phone')?>" placeholder="Phone number" class="form-control input-sm" type="text" />
								    		<span class="help_block"><?php echo form_error('phone')?></span>
								    		</div>
								    		<div class="col-md-6">
								    		<input name="email"  value="<?php echo set_value('email')?>" placeholder="Email address" class="form-control input-sm" type="text" />
								    		<span class="help_block"><?php echo form_error('email')?></span>
								    		</div>
								    	</div>
						    	</div>
						    	<div class="form-group">
						    		<div class="row">
						    		<div class="col-md-12">
						    		<input name="title"  value="<?php echo set_value('title')?>" placeholder="Designation" class="form-control input-sm" type="text" />
						    		<span class="help_block"><?php echo form_error('title')?></span>
						    		</div>
						    	</div>
						    	</div>		    	
						    	<div class="form-group">
						    		<div class="row">
										<div class="col-md-6">
								    		<input name="password" placeholder="Choose password" class="form-control input-sm" type="password" />
								    		<span class="help_block"><?php echo form_error('password')?></span>
								    		</div>
								    		<div class="col-md-6">
								    		<input name="conf_password" placeholder="Retype chosen password" class="form-control input-sm" type="password" />
								    		<span class="help_block"><?php echo form_error('conf_password')?></span>
								    		</div>
								    	</div>
						    	</div>
						    	<div class="form-group">
						    		<div class="row">
							    		<div class="col-md-2"><label class="control-label">Logo:</label></div>
							    		<div class="col-md-2"><input name="logo"  type="file" /></div>
							    		<div class="col-md-8"><span class="pull-right"><img src="<?php echo base_url('assets/images/client_logo/no_logo.png')?>" class="img-responsive" style="width: 100px; height: 50px; border: solid #999 1px;" /></span></div>
						    		</div>
						    	</div>
						    	</div>
						    </div>
						    <div class="col-md-6 ml-auto mr-auto text-center">
				    			<div class="form-group" style="margin-top: 10px;">
				    				<button type="submit" class="btn btn-sm btn-block btn-secondary"><strong><i class="fa fa-save"></i> Save Department &amp; User</strong></button>
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