<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-users"></i> Department Profile</div>
                            <div class="card-tools">                                       </a>
                                <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    	<div class=" row col-md-12" style="margin: 5px auto;">
                            <div class="col-md-4">
                                <img src='<?php echo base_url('assets/images/client_logo/')?><?php echo (isset($client->logo) && $client->logo != NULL) ? $client->logo : "" ?>' style="width: 300px; height: 200px; margin-right: 20px; border: solid #999999 2px;" class="thumbnail img-responsive" />
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-muted">Department Details</h3>
                                <p><i class="fa fa-institution"></i> Department Name: <span class="right"><?php echo (isset($client->organization) && $client->organization != NULL) ? ucwords(str_replace('_', ' ', $client->organization)) : '';?></span></p>
                                <p><i class="fa fa-map-marker"></i> Address: <span class="right"><?php echo (isset($client->address) && $client->address != NULL) ? ucwords($client->address) : '--';?></span></p>
                                <p><i class="fa fa-phone"></i> Phone Number: <span class="right"><?php echo (isset($client->company_phone) && $client->company_phone != NULL) ? $client->company_phone : '--' ;?></span></p>
                                <p><i class="fa fa-envelope-o"></i> Email Address: <span class="right"><?php echo (isset($client->org_email) && $client->org_email != NULL) ? "<a href='mailto:$client->org_email'>$client->org_email</a>" : '--' ?></span></p>
                                <p><i class="fa fa-smile-o"></i> Account Status:
                                	<?php if(isset($client->status) && $client->status != NULL){ ?>
                                    <span><button class="<?php echo ($client->status) ? 'btn btn-success btn-outline btn-xs':'btn btn-danger btn-outline btn-xs';?>" onclick="update_dept_status(<?php echo $client->dept_id;?>)" title = "Change status"><i class="fa fa-refresh"></i> <?php echo ($client->status) ? 'Active':'Inactive';?></button></span>
        	                        <?php }else{ echo "--"; } ?>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-muted">Contact Person [<small>dept admin</small>]</h3>
                                <p><i class="fa fa-user"></i> Contact Name: <span class="right"><?php echo (isset($client->first_name) && $client->first_name != NULL) ? ucwords($client->first_name).' '.ucwords($client->last_name) : '--';?></span></p>
                                <p><i class="fa fa-envelope"></i> Email Address: <span class="right"><?php echo (isset($client->email) && $client->email != NULL) ? "<a href='mailto:$client->email'>$client->email</a>" : '--' ?></span></p>
                                <p><i class="fa fa-phone"></i> Phone Number: <span class="right"><?php echo (isset($client->contact_phone) && $client->contact_phone != NULL) ? $client->contact_phone : '--';?></span></p>
                                <p><i class="fa fa-certificate"></i> Designation: <span class="right"><?php echo (isset($client->title) && $client->title != NULL) ? ucwords($client->title) : '--';?></span></p>
                                <p><i class="fa fa-smile-o"></i> Status:
                                	<?php if(isset($client->active) && $client->active != NULL){ ?>
                                    <span style="font-weight: 800;" class="<?php echo ($client->active) ? 'text-success':'text-danger';?>"><?php echo ($client->active) ? 'Active':'Inactive';?></span>
                                    <?php }else{ echo "--"; } ?>
                                </p>
                            </div> 
                        </div>
                        <div class="col-md-6 ml-auto mr-auto text-center"><hr>
                            <button onclick="update_client_dept(<?php echo $client->dept_id;?>)" class="btn btn-warning btn-xs" title = "Edit client details"><i class="fa fa-edit"></i> Update Client</button> | 
                            <a href="<?php echo base_url('backend/admin/dept_users/')?><?php echo $client->dept_id;?>" class="btn btn-default btn-xs" title = "View <?php echo ucwords(str_replace('_', ' ', $client->organization));?>'s Users"><i class="fa fa-users"></i> System Users</a> | 
                            <a href="<?php echo base_url('backend/admin/sub_depts/')?><?php echo $client->dept_id;?>" class="btn btn-secondary btn-xs" title = "View sub-departments details"><i class="fa fa-sitemap"></i> Sub-departments</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<!-- Dept modal -->
<div class="modal fade" id="deptModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update Client Dept</h4>
            </div>
            <div class="modal-body">
                <div id="dept_msg"></div>
                <?php echo form_open_multipart('', ['class' => 'form-horizontal', 'id' => 'client_dept']);?>
                <input type="hidden" name="id" value="" />
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6"><label class="control-label">Department Name:</label></div>
                            <div class="col-md-6">
                                <input name="company" placeholder="Depertment Name" class="form-control input-sm" type="text" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6"><label class="control-label">Address:</label></div>
                            <div class="col-md-6">
                                <input name="address" placeholder="Postal Address" class="form-control input-sm" type="text" />
                            </div>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6"><label class="control-label">Department Phone:</label></div>
                            <div class="col-md-6">
                                <input name="phone" placeholder="Department Phone" class="form-control input-sm" type="text" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6"><label class="control-label">Company Email:</label></div>
                            <div class="col-md-6">
                                <input name="email" placeholder="Email Address" class="form-control input-sm" type="text" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6"><label class="control-label">Category:</label></div>
                            <div class="col-md-6">
                                <select name="category" class="form-control input-sm">
                                    <option value="" selected="selected" disabled="disabled">--Select category--</option>
                                    <?php foreach ($categories as $c) {?>
                                    <option value="<?php echo $c->category_id;?>"><?php echo ucwords($c->category_name);?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4"><label class="control-label">Logo:</label></div>
                                <div class="col-md-4"><input name="logo"  type="file" /></div>
                                <div class="col-md-4"><span class="pull-right"><img class="img-responsive" style="width: 100px; height: 50px; border: solid #999 1px;" /></span>
                                </div>
                        </div>
                    </div>
                </div>
                </div>
                <?php echo form_close()?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="button" class="btn btn-primary btn-xs" onclick="save_client_dept()"><i class="fa fa-save"></i> Save changes</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function update_client_dept(id){
    $('#client_dept')[0].reset();
    $.ajax({
        url: '<?php echo base_url()?>backend/admin/edit_client_dept/'+id,
        type: 'GET',
        dataType: 'json',
        success: function(data){
            $('input[name="id"]').val(data.msg.dept_id);
            $('input[name="company"]').val(data.msg.dept_name);
            $('input[name="address"]').val(data.msg.dept_address);
            $('input[name="phone"]').val(data.msg.dept_phone);
            $('input[name="email"]').val(data.msg.dept_email);
            $('select[name="category"] option').prop('selected', false).filter(function(){
                return $(this).val() == data.msg.category_id;
            }).prop('selected', true);
            $('.img-responsive').attr('src', '<?php echo base_url()?>assets/images/client_logo/'+data.msg.logo);
            $('#deptModal').modal('show');
        }
    });
}

function save_client_dept(){
    var formdata = new FormData($('#client_dept')[0]);
    $.ajax({
        url: '<?php echo base_url()?>backend/admin/update_client_dept',
        type: 'POST',
        dataType: 'json',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data){
            if (data.status) {
                $('#dept_msg').removeClass('text-danger').addClass('text-success').html(data.msg);
                setTimeout(function(){
                    location.reload();
                }, 2000);
            } else {
                $('#dept_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
            }
        }
    });    
}

function update_dept_status(id){
    $.ajax({
        url : "<?php echo site_url('backend/admin/update_dept_status')?>/"+id,
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
