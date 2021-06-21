<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Sidebar -->
<div class="sidebar sidebar-style-2">			
	<div class="sidebar-wrapper scrollbar scrollbar-inner">
		<div class="sidebar-content">
			<ul class="nav nav-primary">

				<li class="nav-item active">
					<a href="<?php echo base_url('dashboard') ?>">
						<i class="fas fa-home"></i>
						<p>Dashboard</p>
					</a>
				</li>
				<li class="nav-section">
					<span class="sidebar-mini-icon">
						<i class="fa fa-ellipsis-h"></i>
					</span>
					<h4 class="text-section">Services</h4>
				</li>

				<li class="nav-item">
					<a href="<?php echo base_url('clients') ?>">
						<i class="fas fa-building"></i>
						<p>Client Management</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo base_url('vehicles-managment') ?>">
						<i class="fas fa-bus"></i>
						<p>Vehicle Management</p>
					</a>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#base">
						<i class="fas fa-layer-group"></i>
						<p>Prepaid Transactions</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="base">
						<ul class="nav nav-collapse">
							<li>
								<a href="<?php echo base_url('transactions-manage') ?>">
									<span class="sub-item">Daily Dumps</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('range-transactions') ?>">
									<span class="sub-item">Ranged Dumps</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('toll-spending') ?>">
									<span class="sub-item">Toll Spending</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('account') ?>">
									<span class="sub-item">Accounts</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a href="<?php echo base_url('citations-management') ?>">
						<i class="fas fa-exclamation-triangle"></i>
						<p>Citations</p>
					</a>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#sidebarLayouts">
						<i class="fas fa-file-invoice-dollar"></i>
						<p>Invoices</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="sidebarLayouts">
						<ul class="nav nav-collapse">
							<li>
								<a href="<?php echo base_url('manage-invoices') ?>">
									<span class="sub-item">Manage Invoices</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('monthly-invoices') ?>">
									<span class="sub-item">Monthly Invoices</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a href="<?php echo base_url('fulfilment') ?>">
						<i class="fas fa-truck-pickup"></i>
						<p>Transponder fulfilment</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo base_url('users') ?>">
						<i class="fas fa-user-alt"></i>
						<p>system Users</p>
					</a>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#sidebarmis">
						<i class="fas fa-cubes"></i>
						<p>Miscellaneous</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="sidebarmis">
						<ul class="nav nav-collapse">
							<li>
								<a href="<?php echo base_url('states') ?>">
									<span class="sub-item">States</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('agencies') ?>">
									<span class="sub-item">Agencies</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('tag') ?>">
									<span class="sub-item">Tag Types</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('toll_tag') ?>">
									<span class="sub-item">Toll Tags</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('signups') ?>">
									<span class="sub-item">Signups</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
						
			</ul>
		</div>
	</div>
</div>
<!-- End Sidebar -->