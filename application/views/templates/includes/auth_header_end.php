<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</head>
<body>
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="blue">
				
				<a href="<?php echo base_url('dashboard') ?>" class="logo">
					<img src="<?php echo base_url() ?>logo-footer.png" alt="navbar brand" class="navbar-brand">
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->
			<?php $this->load->view('templates/includes/auth_top_menu'); ?>
			<!-- Navbar Header -->
			
			<!-- End Navbar -->
		</div>

		<?php $this->load->view('templates/includes/auth_sidebar'); ?>
		<div class="main-panel">