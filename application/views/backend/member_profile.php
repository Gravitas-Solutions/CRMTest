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
                            <div class=" row col-md-12" style="margin: 5px auto;">
                                <div class="col-md-4">
                                    <img src='<?php echo base_url("assets/images/client_logo/$client->logo")?>' style="width: 300px; height: 200px; margin-right: 20px; border: solid #999999 2px;" class="thumbnail img-responsive" />
                                </div>
                                <div class="col-md-4">
                                    <h3 class="text-muted">Client Details</h3>
                                    <p><i class="fa fa-institution"></i> <strong>Client Name:</strong> <span class="right"><?php echo ucwords(str_replace('_', ' ', $client->organization));?></span></p>
                                    <p><i class="fa fa-map-marker"></i> <strong>Address:</strong> <span class="right"><?php echo ucwords($client->address);?></span></p>
                                    <p><i class="fa fa-phone"></i> <strong>Phone Number:</strong> <span class="right"><?php echo $client->company_phone;?></span></p>
                                    <p><i class="fa fa-envelope-o"></i> <strong>Email Address:</strong> <span class="right"><a href="mailto:<?php echo $client->org_email;?>"><?php echo $client->org_email;?></a></span></p>
                                    <p><i class="fa fa-smile-o"></i> <strong>Account Status:</strong>
                                        <span><button class="<?php echo ($client->status) ? 'btn btn-success btn-outline btn-xs':'btn btn-danger btn-outline btn-xs';?>" onclick="update_client_account(<?php echo $client->id;?>)" title = "Change status"><i class="fa fa-refresh"></i> <?php echo ($client->status) ? 'Active':'Inactive';?></button></span>
                                </div>
                                <div class="col-md-4">
                                    <h3 class="text-muted">Contact Person [<small>system admin</small>]</h3>
                                    <p><i class="fa fa-user"></i> <strong>Contact Name:</strong> <span class="right"><?php echo ucwords($client->first_name).' '.ucwords($client->last_name);?></span></p>
                                    <p><i class="fa fa-envelope"></i> <strong>Email Address:</strong> <span class="right"><a href="mailto:<?php echo $client->email;?>"><?php echo $client->email;?></a></span></p>
                                    <p><i class="fa fa-phone"></i> <strong>Phone Number:</strong> <span class="right"><?php echo $client->contact_phone;?></span></p>
                                    <p><i class="fa fa-certificate"></i> <strong>Designation:</strong> <span class="right"><?php echo ucwords($client->title);?></span></p>
                                    <p><i class="fa fa-smile-o"></i> <strong>Status:</strong>
                                        <span style="font-weight: 800;" class="<?php echo ($client->active) ? 'text-success':'text-danger';?>"><?php echo ($client->active) ? 'Active':'Inactive';?></span>
                                    </p>
                                </div> 
                            </div>
                            <div class="col-md-6 ml-auto mr-auto text-center"><hr>
                                <a href="<?php echo base_url('backend/admin/edit_client/')?><?php echo $client->id;?>" class="btn btn-warning btn-xs" title = "Edit client details"><i class="fa fa-edit"></i> Update Client</a> | 
                                <a href="<?php echo base_url('backend/admin/client_users/')?><?php echo $client->id;?>" class="btn btn-default btn-xs" title = "View <?php echo ucwords(str_replace('_', ' ', $client->organization));?>'s Users"><i class="fa fa-users"></i> System Users</a> | 
                                <a href="<?php echo base_url('backend/admin/client_departments/')?><?php echo $client->id;?>" class="btn btn-secondary btn-xs" title = "Edit client department"><i class="fa fa-sitemap"></i> Departments</a> | 
                                <a href="<?php echo base_url('backend/admin/client_dept_grouping/')?><?php echo $client->id;?>" class="btn btn-secondary btn-xs" title = "Manage department grouping"><i class="fa fa-sitemap"></i> Dept Grouping</a>
                            </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
function update_client_account(id){
    $.ajax({
        url : "<?php echo site_url('backend/admin/update_client_account')?>/"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data){
            if (data.status) {
                alert(data.msg);
                location.reload();
            } else{
                alert(data.msg);
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error updating status');
        }
    });
}

</script>
<?php $this->load->view('templates/includes/footer_end'); ?>
