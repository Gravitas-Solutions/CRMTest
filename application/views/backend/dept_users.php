<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-users"></i> <?php echo ucwords(str_replace('_', ' ', $organization)) ?>'s</em> System Users</div>
                                    <div class="card-tools">
                                        <a  onclick="add_client_user()" class="btn btn-info btn-border btn-round btn-sm mr-2"><i class="fa fa-plus-circle"></i> New user                                        </a>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                                    <thead>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Sub-Department</th>
                                        <th>Designation</th>
                                        <th>Modules</th>
                                        <th>Edit Vehicle?</th>
                                        <th>Status</th>
                                        <th class="noExport">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; foreach ($dept_users as $user) { $i++; ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo ucwords($user->first_name. ' '.$user->last_name);?></td>
                                            <td><?php echo $user->phone ?></td>
                                            <td><a href="mailto:<?php echo $user->email ?>"><?php echo $user->email ?></a></td>
                                            <td><?php echo ($user->sub_dept_name) ? ucwords($user->sub_dept_name) : '<center>-</center> '?></td>
                                            <td><?php echo ucwords($user->title) ?></td>
                                            <td><?php echo str_replace(',', ' | ', $user->modules) ?></td>
                                            <td class="text-center"><?php echo ($user->can_update) ? 'Yes' : 'No'; ?></td>
                                            <td><button class="<?php echo ($user->active) ? 'btn btn-success btn-outline btn-xs':'btn btn-danger btn-outline btn-xs';?>" onclick="activate_client(<?php echo $user->id;?>)" title = "Change status"><span><i class="fa fa-refresh"></i> <?php echo ($user->active) ? 'Active':'Inactive';?></span></button></td>
                                            <td>
                                                <button class="btn btn-info btn-xs" onclick="change_pass(<?php echo $user->id;?>)" title = "Update account password">&nbsp;<i class="fa fa-lock"></i>&nbsp;</button> | 
                                                <button class="btn btn-warning btn-xs" <?php echo ($user->default_user) ? 'disabled':'';?> onclick="edit_client_user(<?php echo $user->id;?>)" title = "Update user details"><i class="fa fa-edit"></i></button></td>   
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
            
<?php $this->load->view('templates/includes/footer_start'); ?>

<script type="text/javascript">

$(document).keypress(function(e){
    if (e.which == 13){
        $("#deptUserSave").click();
        return false;
    }
});

function add_client_user(){
    save_method = 'add';
    $('#new_dept_user')[0].reset();
    $('#passwords').css('display', 'block');
    $('#user_msg').html(''); 
    $('#clientUserModal').modal('show'); 
    $("select[name='department'] option:contains('departments')").remove();
}

function edit_client_user(id){
    save_method = 'update';
new_dept_user
    $('#new_dept_user')[0].reset(); 
    $('#passwords').css('display', 'none');
    $('#user_msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_client_user')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('input[name="user_id"]').val(data.msg.user_id);
                $('input[name="first_name"]').val(data.msg.first_name);
                $('input[name="last_name"]').val(data.msg.last_name);
                $('input[name="user_email"]').val(data.msg.user_email);
                $('input[name="user_phone"]').val(data.msg.user_phone);
                $('input[name="designation"]').val(data.msg.title);
                $('select[name="department"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.msg.department_id;
                }).prop('selected', true);
                $("select[name='department'] option:contains('departments')").remove();
                $.each(data.msg.modules.split(','), function(i, val){
                   $('input:checkbox[name="module[]"][value="' + val + '"]').prop('checked',true);
                });
                if(data.msg.default_user){
                    $('select[name="department"]').prop("disabled", true);
                    $('input:checkbox[name="module[]"]').prop("disabled", true);
                }
                $('input:radio[name="vehicle_updater"][value="' + data.msg.can_update + '"]').prop("checked", true);
                $('#clientUserModal').modal('show'); 
                $('.modal-title').text('Update User Details');
            }else{
                alert(data.msg);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save_client_user(){
    $('#deptUserSave').text('saving...').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_dept_user')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_dept_user')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_dept_user').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#clientUserModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#user_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#deptUserSave').text('Save Account'); 
                $('#deptUserSave').attr('disabled', false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#deptUserSave').text('Save Account'); 
                $('#deptUserSave').attr('disabled',false);  
            }
        });
}

function change_pass(id){
    $('#change_pass')[0].reset();
    $('input[name="id"]').val(id);
    $('#pass_msg').html(''); 
    $('#passModal').modal('show');
}

function update_pass(){
    $('#change_password').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url : "<?php echo site_url('backend/admin/update_password')?>/",
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#passModal').modal('hide');
                    alert(data.msg);
                }
                else{
                    $('#pass_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');  
            }
        });
    });
}

function activate_client(id){
        $.ajax({
            url : "<?php echo site_url('backend/admin/activate_client')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                alert('Status change success');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error updating status');
            }
        });
}

</script>

<!-- New User Modal-->
<div class="modal fade" id="clientUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">New <em class="text-muted"><?php echo ucwords(str_replace('_', ' ', $organization)) ?>'s</em> system user</h4>
      </div>
      <div class="modal-body form">
        <div id="user_msg"></div>
        <?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_dept_user', 'method' => 'POST']);?>
            <input type="hidden" value="" name="user_id"/> 
            <input type="hidden" value="<?php echo $this->uri->segment(3) ?>" name="org"/> 
            <div class="form-group">
                <div class="col-md-6">
                    <input name="first_name" placeholder="First name" class="form-control input-sm" type="text" />
                </div>
                <div class="col-md-6">
                    <input name="last_name" placeholder="Last name" class="form-control input-sm" type="text" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <input name="user_phone" placeholder="Phone number" class="form-control input-sm" type="text" />
                </div>
                <div class="col-md-6">
                    <input name="user_email" placeholder="Email address" class="form-control input-sm" type="text" />
                </div>
            </div>                
            <div class="form-group">
                <div class="col-md-6">
                    <input name="designation" placeholder="Designation" class="form-control input-sm" type="text" />
                </div> 
                <?php if (!empty($has_sub_depts)) { ?>
                <div class="col-md-6">
                    <select name="department" class="form-control input-sm">
                        <option value="" selected="selected"  disabled="disabled">--Sub-department--</option>
                        <?php foreach ($client_sub_departments as $csd) {?>
                        <option value="<?php echo $csd->sub_dept_id;?>"><?php echo ucwords($csd->sub_dept_name);?></option>
                        <?php }?>
                    </select>                     
                </div> 
	            <?php } ?>
            </div>
            <div class="form-group">
                <div class="col-md-12 text-muted">
                    <h6 class="text-muted"><em>Can access the following modules:</em></h6>
                    <div class="checkbox-inline">
                        <label><input type="checkbox" name="module[]" value="dashboard" checked="true" disabled="disabled">Dashboard</label>
                    </div>
                    <div class="checkbox-inline">
                        <label><input type="checkbox" name="module[]" value="vehicles">Vehicles</label>
                    </div>
                    <div class="checkbox-inline">
                        <label><input type="checkbox" name="module[]" value="transactions">Transactions</label>
                    </div>
                    <div class="checkbox-inline">
                        <label><input type="checkbox" name="module[]" value="invoices">Invoices</label>
                    </div>
                    <div class="checkbox-inline">
                        <label><input type="checkbox" name="module[]" value="citations">Citations</label>
                    </div>                        
                </div>
            </div>            
            <div class="form-group">
                <div class="col-md-12 text-muted">
                    <div class="radio-inline">
                        <label><em>User can update vehicles?</em></label>
                    </div>
                    <div class="radio-inline">
                        <input type="radio" name="vehicle_updater" value="0" checked="checked"> No
                    </div>                    
                    <div class="radio-inline">
                        <input type="radio" name="vehicle_updater" value="1"> Yes
                    </div>
                                         
                </div>
            </div> 
            <div class="form-group" id="passwords">
                <div class="col-md-12">
                    <small class="text-info"><i class="fa fa-info-circle"></i> NOTE: A default password (<span style="font-weight: 800; text-decoration: underline;">password</span>) will be set on this account; changeable hereafter</small>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning btn-xs" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
        <button class="btn btn-success btn-xs" id="deptUserSave" onclick="save_client_user()"><i class="fa fa-save"></i> Save Account</button>
      </div>
    </div>
  </div>
</div>

<!-- Pass Change Modal -->
<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Change User Password</h4>
      </div>
      <div class="modal-body form">
      <div id="pass_msg"></div>
        <?php echo form_open('#', ['method' => 'POST', 'id' => 'change_password']);?>
        <input type="hidden" name="id" value="" />
            <div class="form-group">
                <div class="col-md-4">
                    <input type="password" name="pass" placeholder="New password" class="form-control input-sm" />
                </div>
                <div class="col-md-4">
                    <input type="password" name="conf_pass" placeholder="Re-type new password" class="form-control input-xs" />
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success btn-xs" onclick="update_pass()"><i class="fa fa-refresh"></i> Update Password</button>
                </div>
            </div>
        </form>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
<?php $this->load->view('templates/includes/footer_end'); ?>