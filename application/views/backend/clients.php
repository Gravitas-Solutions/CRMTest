<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
	<div class="main-panel">
		<div class="content">
			<div class="page-inner">					
				<!-- Card -->
				<h4 class="page-title">Manage Clients</h4>
				<div class="row">
					<div class="col-md-3">
						<div class="card card-profile">
							<div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
								<div class="profile-picture">
									<div class="avatar avatar-xl">
										<img src="../assets/img/logo-footer.png" alt="..." class="avatar-img rounded-circle">
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="user-profile text-center">
									<div class="name">Huffines Plano</div>
								</div>
							</div>
							<div class="card-footer">
								<div align="center"><a href="#" class="btn btn-primary btn-rounded btn-sm">Manage Profile</a>
								<a href="#" class="btn btn-primary btn-rounded btn-sm">Departments</a></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer">
			<div class="container-fluid">
				<div class="copyright ml-auto">
					2021 <a href="https://www.innovativetoll.com">Innovativetoll</a>
				</div>				
			</div>
		</footer>
	</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
	$(function() {
		
	});
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>