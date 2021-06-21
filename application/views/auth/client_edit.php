
<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
    <div class="content">
        <div class="page-inner">
			<div class="row" style="margin: 0 auto;">
				<div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-edit"></i> Edit <?php echo ucwords(str_replace('_', ' ', $client->organization));?>'s</span> Information
                                    </div>
                                    <div class="card-tools">
										<a onclick="javascript:history.go(-1);" class="fa-2x">
											<span class="btn-label">
												<i class="fa fa-arrow-circle-left"></i>
											</span>
											
										</a>
									</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="msg"><?php echo $this->session->flashdata('message'); ?></div>
						    <?php echo form_open_multipart('update-client', ['class' => 'form-horizontal', 'id' => 'new_client', 'method' => 'POST']);?>
						    <input type="hidden" name="id" value="<?php echo $client->id?>" />
					    	<div class="row">
							    <div class="col-md-6">
							    	<div class="form-group">
							    		<div class="row">
								    		<div class="col-md-4"><label class="control-label">Client Name:</label></div>
								    		<div class="col-md-8">
								    			<input name="company" value="<?php echo ucwords(str_replace('_', ' ', $client->organization));?>" placeholder="Company Name" class="form-control input-sm" type="text" readonly />
								    		<span class="help_block"><?php echo form_error('company')?></span>
									    	</div>
								    	</div>
							    	</div>
							    	<div class="form-group">
							    		<div class="row">
								    		<div class="col-md-4"><label class="control-label">Address:</label></div>
								    		<div class="col-md-8">
									    		<input name="address" value="<?php echo $client->address?>" placeholder="Postal Address" class="form-control input-sm" type="text" />
									    		<span class="help_block"><?php echo form_error('address')?></span>
									    	</div>
									    </div>
							    	</div>
							    	<div class="form-group">
							    		<div class="row">
								    		<div class="col-md-4"><label class="control-label">Company Phone:</label></div>
								    		<div class="col-md-8">
									    		<input name="company_phone" value="<?php echo $client->company_phone?>" placeholder="Company Phone" class="form-control input-sm" type="text" />
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
									    		<input name="email"  value="<?php echo $client->org_email?>" placeholder="Email Address" class="form-control input-sm" type="text" />
									    		<span class="help_block"><?php echo form_error('email')?></span>
									    	</div>
									    </div>
							    	</div>		    			    	
							    	<div class="form-group">
							    		<div class="row">
								    		<div class="col-md-4"><label class="control-label">A/c Type:</label></div>
								    		<div class="col-md-8">
								    		<select name="demo_acc" class="form-control input-sm">
								    			<option value="" selected="selected" disabled="disabled">--Select a/c type--</option>
								    			<option value="1" <?php if ($client->demo_acc == 1 ) echo 'selected' ; ?> >Demo account</option>
								    			<option value="0" <?php if ($client->demo_acc == 0 ) echo 'selected' ; ?> >Production account</option>
								    		</select>
								    		<span class="help_block"><?php echo form_error('demo_acc')?></span>
									    	</div>
									    </div>
							    	</div>
							    	<div class="form-group">
							    		<div class="row">
								    		<div class="col-md-4"><label class="control-label">Category:</label></div>
								    		<div class="col-md-8">
									    		<select name="category" class="form-control input-sm">
									    			<option value="" selected="selected" disabled="disabled">--Select category--</option>
									    			<?php foreach ($categories as $c) {?>
									    			<option value="<?php echo $c->category_id;?>" <?php echo  set_select('category', "$c->category_id");?> <?php if ($client->category_id == $c->category_id ) echo 'selected' ; ?> ><?php echo ucwords($c->category_name);?></option>
									    			<?php }?>
									    		</select>
									    		<span class="help_block"><?php echo form_error('category')?></span>
									    	</div>
									    </div>
							    	</div>		    	
							    	<div class="form-group">
							    		<div class="row">
								    		<div class="col-md-4"><label class="control-label">Logo:</label></div>
								    		<div class="col-md-5"><input name="logo"  type="file" /></div>
								    		<div class="col-md-3"><span class="pull-right"><img src="<?php echo base_url('assets/images/client_logo/').$client->logo?>" class="img-responsive" style="width: 100px; height: 50px; border: solid #999 1px;" /></span></div>
								    	</div>
							    	</div>
							    </div>
							    </div>
							    <div class="col-md-6 ml-auto mr-auto text-center">
					    			<div class="form-group" style="margin-top: 10px;">
					    				<button type="submit" class="btn btn-sm btn-block btn-primary"><strong><i class="fa fa-refresh"></i> Update Client</strong></button>
					    			</div>
					    		</div>
	       					<?php echo form_close()?>
                            </div>
                        </div>
                    </div>
			</div>       
		</div>
    </div>

<?php $this->load->view('templates/includes/footer_start'); ?>
<?php $this->load->view('templates/includes/footer_end'); ?>