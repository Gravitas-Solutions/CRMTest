<!-- Page start -->
<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
    <div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fas fa-code-branch"></i> Manage deprtments(Individual Clients)</div>
                                    <div class="card-tools">
                                        <a class="btn btn-primary btn-xs" href="<?php echo base_url()?>backend/admin/create_dept/<?php echo $this->uri->segment(4) ?>" class="btn btn-info btn-border btn-round btn-sm mr-2">
                                            New Depertment
                                        </a>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row col-md-10 col-md-offset-1">
                                <?php foreach($departments as $department){ ?>
                                   <?php if (strpos($department->dept_name, 'overview') !== false) {
                                                    continue;
                                                }?>
                                        <div class="col-md-4">
                                        <div class="card card-profile">
                                            <div class="card-header" style="background-image: url('<?php echo base_url("assets/images/blogpost.jpg")?>')">
                                                <div class="profile-picture">
                                                    <div class="avatar avatar-xl">
                                                        <?php echo ($department->logo) ? "<img src='".base_url()."assets/images/client_logo/".$department->logo."' alt='Logo' class='avatar-img rounded-circle' />" : "<img src='".base_url()."assets/images/client_logo/".$department->client_logo."' class='avatar-img rounded-circle' /> ";?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="user-profile text-center">
                                                    <div class="name"><?php echo strtoupper(ucwords(str_replace('_', ' ', $department->dept_name))) ?></div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div align="center"><a href="<?php echo base_url('backend/admin/dept_profile/')?><?php echo $department->dept_id;?>" class="btn btn-primary btn-rounded btn-sm">Manage Profile</a>
                                                <a href="<?php echo base_url('backend/admin/sub_depts/')?><?php echo $department->dept_id;?>" class="btn btn-primary btn-rounded btn-sm">Departments</a></div>
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