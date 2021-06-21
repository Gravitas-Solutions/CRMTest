</head>
<body>
	<div class="wrapper overlay-sidebar">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="white" >
				
				<a href="<?php echo base_url('home') ?>" class="img-responsive">
					<img src="<?php echo base_url()?>assets/images/client_logo/<?php echo $logo;?>" alt="navbar brand"  style="height: 60px" class="navbar-brand">
				</a>
				<!-- <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation" style="pointer-events: none;cursor: not-allowed;">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div> -->
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="white">
				
				<div class="container-fluid">
					<div class="collapse" id="search-nav">
						<form action="<?php echo base_url()?><?php echo $action?>" method="POST" name='dept_form' class="navbar-form navbar-left mr-md-3" role="search">
						<div class="row">
							<?php if(count($departments) > 1){ ?>
					      <div class="col-md-2"><label class="control-label">Dept: </label></div> 
					       <?php } ?>
					      <div class="col-md-10" >
					        
					      <!-- does not have sub-department -->
					        <?php if(!$has_sub_depts){ ?>
					        	<?php if(count($departments) > 1){ ?>
						        <select name="member_dept" id="member_dept" class="form-control dept_form" onchange="this.form.submit()">
						          <?php if ($default_user) { ?>
						            <option value="0" <?php if($client_dept == 0){echo 'selected';}?>>All departments</option>
						             <?php foreach ($departments as $dept) { ?>
						             <?php if ((strpos($dept->dept_name, 'overview') !== false)) {continue;}?>
						            <option value="<?php echo $dept->dept_id?>" <?php if($client_dept == $dept->dept_id){echo 'selected';}?>><?php echo word_limiter($dept->dept_name, 4);?></option>
						            <?php } ?>
						          <?php }else{?>
						              <!-- Not default user but attached to group -->
						              <?php if ($has_group !== 0) { ?>
						                <option value="0" <?php if($client_dept == 0){echo 'selected';}?>>All departments</option>
						                 <?php foreach ($departments as $dept) { ?>
						                 <?php if ((strpos($dept->dept_name, 'overview') !== false)) {continue;}?>
						                <option value="<?php echo $dept->dept_id?>" <?php if($client_dept == $dept->dept_id){echo 'selected';}?>><?php echo word_limiter($dept->dept_name, 4);?></option>
						                <?php } ?>
						             <?php } else{?>
						              <?php foreach ($departments as $dept) { ?>
						                  <?php if ((strpos($dept->dept_name, 'overview') !== false)) {continue;}?>
						                    <option value="<?php echo $dept->dept_id?>" <?php if($client_dept == $dept->dept_id){echo 'selected';}?>><?php echo word_limiter($dept->dept_name, 4);?></option>
						                  <?php } ?>
						             <?php } ?>
						        <?php } ?>
						          </select>
						          <?php } ?>
					        <?php }

					        // has sub dept
					        elseif($has_sub_depts && $default_user && $client_sub->department_id == $overview_dept){ ?>
					          <select name="member_dept" class="form-control dept_form" onchange="this.form.submit()">
					            <option value="0" <?php if($client_dept == 0){echo 'selected';}?>>All departments</option>
					          <?php foreach ($departments as $dept) { ?>
					             <?php if ((strpos($dept->dept_name, 'overview') !== false)) {continue;}?>
					            <option value="<?php echo $dept->dept_id?>" <?php if($client_dept == $dept->dept_id){echo 'selected';}?>><?php echo word_limiter($dept->dept_name, 4);?></option>
					          <?php } ?>
					          </select>
					        <?php }
					        //attached to client not default user
					        elseif($has_sub_depts && !$default_user && $client_sub->department_id == $overview_dept){ ?>
					          <select name="member_dept" class="form-control dept_form" onchange="this.form.submit()">
					            <option value="0" <?php if($client_dept == 0){echo 'selected';}?>>All departments</option>
					          <?php foreach ($departments as $dept) { ?>
					             <?php if ((strpos($dept->dept_name, 'overview') !== false)) {continue;}?>
					            <option value="<?php echo $dept->dept_id?>" <?php if($client_dept == $dept->dept_id){echo 'selected';}?>><?php echo word_limiter($dept->dept_name, 4);?></option>
					          <?php } ?>
					          </select>
					        <?php }
					        //attached to client not default user and has group
					        elseif($has_sub_depts && !$default_user && $client_sub->department_id == $overview_dept && $has_group !== 0 ){?>
					          <select name="member_dept" class="form-control dept_form" onchange="this.form.submit()">
					            <option value="0" <?php if($client_dept == 0){echo 'selected';}?>>All departments</option>
					          <?php foreach ($departments as $dept) { ?>
					             <?php if ((strpos($dept->dept_name, 'overview') !== false)) {continue;}?>
					            <option value="<?php echo $dept->dept_id?>" <?php if($client_dept == $dept->dept_id){echo 'selected';}?>><?php echo word_limiter($dept->dept_name, 4);?></option>
					          <?php } ?>
					          </select>
					        <?php }
					        //attached to department, has sub-dept and  default user
					        elseif($has_sub_depts && $default_user && $client_sub->department_id !== $overview_dept){ ?>
					          <select name="member_sub_dept" class="form-control dept_form" onchange="this.form.submit()">
					            <option value="0" <?php if($client_sub_dept == 0){echo 'selected';}?>>All sub-departments</option>
					          <?php foreach ($sub_departments as $sub_dept) { ?>
					             <?php if ((strpos($sub_dept->sub_dept_name, 'overview') !== false)) {continue;}?>
					            <option value="<?php echo $sub_dept->sub_dept_id?>" <?php if($client_sub_dept == $sub_dept->sub_dept_id){echo 'selected';}?>><?php echo word_limiter($sub_dept->sub_dept_name, 4);?></option>
					          <?php } ?>
					          </select>
					        <?php }
					        //attached to department, has sub-dept but not default user
					        elseif($has_sub_depts && !$default_user && $client_sub->department_id !== $overview_dept){ ?>
					          <select name="member_sub_dept" class="form-control dept_form" onchange="this.form.submit()">
					            <option value="0" <?php if($client_sub_dept == 0){echo 'selected';}?>>All sub-departments</option>
					          <?php foreach ($sub_departments as $sub_dept) { ?>
					             <?php if ((strpos($sub_dept->sub_dept_name, 'overview') !== false)) {continue;}?>
					            <option value="<?php echo $sub_dept->sub_dept_id?>" <?php if($client_sub_dept == $sub_dept->sub_dept_id){echo 'selected';}?>><?php echo word_limiter($sub_dept->sub_dept_name, 4);?></option>
					          <?php } ?>
					          </select>
					        <?php }
					        //attached to sub_department
					        elseif($has_sub_depts && !$default_user && $client_sub->sub_dept_id !== NULL){ ?>
					          <select name="member_sub_dept" class="form-control dept_form" onchange="this.form.submit()" disabled="disabled">
					            <?php foreach ($sub_departments as $sub_dept) { ?>
					               <?php if ($sub_dept->sub_dept_id !== $client_sub->sub_dept_id) {continue;}?>
					              <option value="<?php echo $sub_dept->sub_dept_id?>"><?php echo word_limiter($sub_dept->sub_dept_name, 4);?></option>
					            <?php } ?>
					          </select>
					        <?php } ?>        
					      </div>
					      </div>
					      </form>
					</div>
					<div class="ml-md-auto py-2 py-md-0">
						<form action="<?php echo base_url()?><?php echo $action?>" method="POST" role="search">
								<?php if($client == 'protech_as'){ ?>
										<button type="button" class="btn btn-info btn-link" id="your-id" onclick="this.form.submit()"><i class="fas fa-grip-horizontal"></i> Caliber Auto Glass</button>
										<input type="hidden" value="caliber_auto_glass"  id="client_name" name="client_name"/> 
							       <?php } ?>
							      <?php if($client == 'caliber_auto_glass'){ ?>
							      	<input type="hidden" value="protech_as" name="client_name" id="client_name"/> 
							      	<button type="button" class="btn btn-info btn-link"  id="your-id1" onclick="this.form.submit()"><i class="fas fa-grip-horizontal"></i> Protech AS</button>
							       <?php } ?>
					       </form>
					</div>
							<div class="ml-md-auto py-2 py-md-0">
								<ul class="nav nav-tabs">
										<li class="nav-item">
											<a class="nav-link" href="<?php echo base_url('home') ?>" onclick="document.getElementById('member_dept'); document.getElementById('dept_form').submit()">
												<i class="fas fa-home"></i> Home</a>
										</li>
										<?php $icon = ''; foreach ($user_modules as $key => $module) {
									        switch ($module) {
									          case 'vehicles': $icon = '<i class="fas fa-car"></i>'; break;
									          case 'transactions': $icon = '<i class="fas fa-list-alt"></i>'; break;
									          case 'invoices': $icon = '<i class="fas fa-file-invoice-dollar"></i>'; break;
									          case 'citations': $icon = '<i class="fas fa-dollar-sign"></i>'; break;
									          case 'transponder': $icon = '<i class="fas fa-truck-pickup"></i>'; break;
									        }
									      ?>
									      <li><a class="nav-link" href="<?php echo base_url("$module");?>" onclick="document.getElementById('member_dept'); document.getElementById('dept_form').submit()"><?php echo $icon.' '.ucfirst($module) ?></a></li>
									        <?php } ?>
									      <?php if($demo_acc) : ?>
									        <li><a onclick="calculator()" href='javascript:void(0)'><i class="fa fa-calculator"></i> ROI</a></li>
									      <?php endif; ?>
																				<!-- <li class="nav-item">
											<a class="nav-link" href="<?php echo base_url('vehicles') ?>"><i class="fas fa-file-invoice-dollar"></i> Vehicles</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="<?php echo base_url('transactions') ?>"><i class="fas fa-file-invoice-dollar"></i> Transactions</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="<?php echo base_url('citations') ?>"><i class="fas fa-file-invoice-dollar"></i> Citations</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="<?php echo base_url('invoices') ?>"><i class="fas fa-file-invoice-dollar"></i> Invoices</a>
										</li>
										<?php if($client !== 'clay_cooley_dealerships' || $client == 'huffines_plano'){ ?>
										<li class="nav-item">
											<a class="nav-link" href="<?php echo base_url('transponder-order') ?>"><i class="fas fa-truck-pickup"></i> Transponders</a>
										</li>
										<?php } ?> -->
									</ul>
							</div>
							
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item toggle-nav-search hidden-caret">
							<a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
								<i class="fa fa-search"></i>
							</a>
						</li>
						<!-- <li class="nav-item dropdown">
							<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
								<i class="fas fa-layer-group"></i>
							</a>
							<div class="dropdown-menu quick-actions quick-actions-info animated fadeIn">
								<div class="quick-actions-header">
									<span class="title mb-1">Quick Actions</span>
									<span class="subtitle op-8">Shortcuts</span>
								</div>
								<div class="quick-actions-scroll scrollbar-outer">
									<div class="quick-actions-items">
										<div class="row m-0">
											<a class="col-6 col-md-4 p-0" href="<?php echo base_url('vehicles') ?>">
												<div class="quick-actions-item">
													<i class="flaticon-file-1"></i>
													<span class="text">Upload Fleets</span>
												</div>
											</a>
											<a class="col-6 col-md-4 p-0" href="<?php echo base_url('transponder') ?>">
												<div class="quick-actions-item">
													<i class="flaticon-database"></i>
													<span class="text">Order transponder</span>
												</div>
											</a>
											<a class="col-6 col-md-4 p-0" href="<?php echo base_url('transponder-fulfilment') ?>">
												<div class="quick-actions-item">
													<i class="flaticon-pen"></i>
													<span class="text">Transponder orders</span>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</li> -->
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="<?php echo base_url() ?>assets/images/profile.jpg" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<div class="dropdown-user-scroll scrollbar-outer">
									<li>
										<div class="user-box">
											<div class="avatar-lg"><img src="<?php echo base_url() ?>assets/images/profile.jpg" alt="image profile" class="avatar-img rounded"></div>
											<div class="u-text">
												<h4>Welcome</h4>
												<p class="text-muted"><?php echo $this->ion_auth->user()->row()->email;?></p>
											</div>
										</div>
									</li>
									<li>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="<?php echo base_url('logout') ?>">Logout</a>
									</li>
								</div>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
			<!-- ROI modal -->
			<div class="modal fade" id="roi_calculator">
			  <div class="modal-dialog">
			      <div class="modal-content">
			          <div class="modal-header">
			              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			              <h4 class="modal-title text-muted">ROI Calculator</h4>
			          </div>
			          <div class="modal-body">
			              <?php echo form_open('', '', ['id' => 'roi_form', 'class' => 'form-horizontal']); ?>
			              <div class="form-group">
			                  <div class="col-md-3"><label class="control-label">Cars: </label></div>
			                  <div class="col-md-3">
			                      <input type="text" class="form-control input-sm cars" name="total_cars" value="" placeholder="Total cars" >
			                  </div>
			                  <div class="col-md-3"><label class="control-label">Cost/car: </label></div>
			                  <div class="col-md-3">
			                      <input type="text" class="form-control input-sm cars" name="cost_per_car" value="" placeholder="Cost per car" >
			                  </div>
			              </div>
			              <div class="clearfix"><hr></div>
			              <div class="form-group">
			                  <div class="col-md-3"><label class="control-label">Monthly cost: </label></div>
			                  <div class="col-md-3">
			                      <input type="text" class="form-control input-sm" name="monthly_cost" value="" placeholder="Monthly cost" disabled="disabled" >
			                  </div>
			                  <div class="col-md-3"><label class="control-label">Current spend: </label></div>
			                  <div class="col-md-3">
			                      <input type="text" class="form-control input-sm" name="current_spend" value="" placeholder="Current spend" >
			                  </div>
			              </div> 
			              <div class="clearfix" style="padding: 10px 0"></div>
			              <div class="form-group store_location">
			                  <div class="col-md-3"><label class="control-label">Store location: </label></div>
			                  <div class="col-md-9">
			                      <select name="store_location" class="form-control input-sm store">
			                          <option value="" selected disabled>-- Select location --</option>
			                          <?php foreach ($states as $s): ?>
			                             <option value="<?php echo $s->state_code ?>"><?php echo $s->state_code." &raquo ".$s->state_name ?></option> 
			                          <?php endforeach ?>
			                      </select>
			                      <span class="help-block" id="store"><i class="fa fa-cogs fa-spin text-info"></i> Store location is required!</span>
			                  </div>
			              </div>  
			              <div class="clearfix"></div>
			              <button type="reset" style="display: none;"></button>             
			              <?php echo form_close(); ?>
			          </div>
			          <div class="modal-footer">
			              <h4>ROI: <span id="roi" class="text-success" style="font-weight: bolder"></span></h4>
			          </div>
			      </div>
			  </div>
			</div>
		</div>
	<div class="main-panel">