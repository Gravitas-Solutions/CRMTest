<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
    <div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fas fa-user"></i> Manage Clients</div>
                                    <div class="card-tools">
                                        <a href="<?php echo base_url('add-client') ?>" class="btn btn-info btn-border btn-round btn-sm mr-2">
                                            New Client
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row col-md-12">
                                    <?php foreach($members as $member){ ?>
                                    <div class="col-md-4">
                                        <div class="card card-profile">
                                            <div class="card-header" style="background-image: url('<?php echo base_url("assets/images/blogpost.jpg")?>')">
                                                <div class="profile-picture">
                                                    <div class="avatar avatar-xl">
                                                        <img src="<?php echo base_url("assets/images/client_logo/$member->logo")?>" alt="Logo" class="avatar-img rounded-circle">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="user-profile text-center">
                                                    <div class="name"><?php echo strtoupper(ucwords(str_replace('_', ' ', $member->organization))) ?></div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div align="center"><a href="<?php echo base_url('backend/admin/client_profile/')?><?php echo $member->id;?>" class="btn btn-primary btn-rounded btn-sm">Manage Profile</a>
                                                <a href="<?php echo base_url('backend/admin/client_departments/')?><?php echo $member->id;?>" class="btn btn-primary btn-rounded btn-sm">Departments</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="row">
                                    <div class="text-center"><?php echo $this->pagination->create_links(); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

<?php $this->load->view('templates/includes/footer_start'); ?>
<?php $this->load->view('templates/includes/footer_end'); ?>