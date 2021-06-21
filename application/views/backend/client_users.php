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
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Modules</th>
                                        <th>Edit Vehicle?</th>
                                        <th>Status</th>
                                        <th class="noExport">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; foreach ($client_users as $user) { $i++; ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo ucwords($user->first_name. ' '.$user->last_name);?></td>
                                            <td><?php echo $user->phone ?></td>
                                            <td><a href="mailto:<?php echo $user->email ?>"><?php echo $user->email ?></a></td>
                                            <td><?php echo (strpos($user->dept_name, 'Overview') === false) ?  ucwords($user->dept_name) : 'All departments' ?></td>
                                            <td><?php echo ucwords($user->title) ?></td>
                                            <td><?php echo str_replace(',', ' | ', $user->modules) ?></td>
                                            <td class="text-center"><?php echo ($user->can_update) ? 'Yes' : 'No'; ?></td>
                                            <td><button class="<?php echo ($user->active) ? 'btn btn-success btn-outline btn-xs':'btn btn-danger btn-outline btn-xs';?>" onclick="activate_client(<?php echo $user->id;?>)" title = "Change status"><span><i class="fa fa-refresh"></i> <?php echo ($user->active) ? 'Active':'Inactive';?></span></button></td>
                                            <td>
                                                <button class="btn btn-info btn-xs" onclick="change_pass(<?php echo $user->id;?>)" title = "Update account password">&nbsp;<i class="fa fa-lock"></i>&nbsp;</button> | 
                                                <button class="btn btn-warning btn-xs" onclick="edit_client_user(<?php echo $user->id;?>)" title = "Update user details"><i class="fa fa-edit"></i></button></td>   
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
        $("#clientUserSave").click();
        return false;
    }
});

function add_client_user(){
    save_method = 'add';
    $('#new_client_user')[0].reset();
    $('#passwords').css('display', 'block');
    $('#user_msg').html(''); 
    $('#clientUserModal').modal('show'); 
    $("select[name='department'] option:contains('departments')").remove();
}

function edit_client_user(id){
    save_method = 'update';
    $('#new_client_user')[0].reset(); 
    $('#passwords').css('display', 'none');
    $('#user_msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_client_user')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.status){
                $('input[name="user_id"]').val(data.msg.user_id);
                $('input[name="first_name"]').val(data.msg.first_name);
                $('input[name="last_name"]').val(data.msg.last_name);
                $('input[name="user_email"]').val(data.msg.user_email);
                $('input[name="user_phone"]').val(data.msg.user_phone);
                $('input[name="designation"]').val(data.msg.title);
                if(data.msg.group_id){
                    $('select[name="department"]').parent().css('display', 'none'); 
                    $('select[name="entity"] option').prop('selected', false).filter(function(){
                            return $(this).val() == 'group';
                        }).prop('selected', true);
                    $('select[name="group"] option').prop('selected', false).filter(function(){
                            return $(this).val() == data.msg.group_id;
                        }).prop('selected', true);
                    $('select[name="group"]').parent().css('display', 'block');
                }else{
                    $('select[name="group"]').parent().css('display', 'none');
                    var depts = JSON.parse( '<?php echo json_encode($client_departments) ?>' );
                    var departmental = false;
                    $.each(depts, function(index, val) {
                        if(data.msg.department_id == val.dept_id && val.dept_name.includes('overview')){
                            departmental = true;
                        }
                    });
                    if(departmental){
                        $('select[name="department"]').parent().css('display', 'none');
                        $('select[name="entity"] option').prop('selected', false).filter(function(){
                        return $(this).val() == 'overview';
                    }).prop('selected', true);
                    }else{
                         $('select[name="entity"] option').prop('selected', false).filter(function(){
                            return $(this).val() == 'dept';
                        }).prop('selected', true);
                         $('select[name="department"] option').prop('selected', false).filter(function(){
                            return $(this).val() == data.msg.department_id;
                        }).prop('selected', true);
                         $('select[name="department"]').parent().css('display', 'block'); 
                    }
                }

                /*$("select[name='department'] option:contains('departments')").remove();*/
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
            } else{
                alert(data.msg);
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
        }
    });
}

function save_client_user(){
    $('#clientUserSave').text('saving...').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_client_user')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_client_user')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_client_user').serialize(),
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
                $('#clientUserSave').text('Save Account'); 
                $('#clientUserSave').attr('disabled', false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#clientUserSave').text('Save Account'); 
                $('#clientUserSave').attr('disabled',false);  
            }
        });
}

function change_pass(id){
    $('#change_pass')[0].reset();
    $('#pass_msg').html(''); 
    $('#id').val(id);
    $('.modal-title').text('Change User Password'); 
    $('#passModal').modal('show');
}

function update_pass(){
        $.ajax({
            url : "<?php echo site_url('backend/admin/update_password')?>/",
            type: "POST",
            data: {id: $('#id').val(), pass: $('input[name="pass"]').val(), conf_pass: $('input[name="conf_pass"]').val()},
            dataType: "JSON",
            success: function(data){
                if(data.status){
                    $('#passModal').modal('hide');
                    alert(data.msg);
                }
                else{
                    $('#pass_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error adding / update data');  
            }
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
      <!-- <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">New <em class="text-muted"><?php echo ucwords(str_replace('_', ' ', $organization)) ?>'s</em> system user</h4>
      </div> -->
          <div class="modal-header">
                <h5 class="modal-title">New <?php echo ucwords(str_replace('_', ' ', $organization)) ?>'s</em> system user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
      <div class="modal-body form">
        <div id="user_msg"></div>
        <?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_client_user', 'method' => 'POST']);?>
            <input type="hidden" value="" name="user_id"/> 
            <input type="hidden" value="<?php echo $this->uri->segment(4) ?>" name="org"/> 
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <input name="first_name" placeholder="First name" class="form-control input-sm" type="text" />
                    </div>
                    <div class="col-md-6">
                        <input name="last_name" placeholder="Last name" class="form-control input-sm" type="text" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <input name="user_phone" placeholder="Phone number" class="form-control input-sm" type="text" />
                    </div>
                    <div class="col-md-6">
                        <input name="user_email" placeholder="Email address" class="form-control input-sm" type="text" />
                    </div>
                </div>
            </div>                
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <input name="designation" placeholder="Designation" class="form-control input-sm" type="text" />
                    </div> 
                    <div class="col-md-6">
                         <select name="entity" class="form-control input-sm">
                            <option value="" selected="selected"  disabled="disabled">Entity attached to</option>
                            <option value="overview">Client</option>
                            <option value="group">Group</option>
                            <option value="dept">Department</option>
                        </select>   
                    </div> 
                </div>

            </div>
            <div class="form-group">
                <div class="row">
                <div class="col-md-6" style="display: none">
                    <select name="group" class="form-control input-sm">
                        <option value="" selected="selected"  disabled="disabled">--Client Grouping--</option>
                        <?php foreach ($client_groups as $cg) {?>     
                            <option value="<?php echo $cg->group_id;?>"><?php echo ucwords($cg->group_name);?></option>
                        <?php }?>
                    </select>                     
                </div>  
                <div class="col-md-6"  style="display: none">
                    <select name="department" class="form-control input-sm">
                        <option value="" selected="selected"  disabled="disabled">--Department--</option>
                        <?php foreach ($client_departments as $cd) {?>
                            <?php if(strpos($cd->dept_name, "overview") === false){?>
                                <option value="<?php echo $cd->dept_id;?>"><?php echo ucwords($cd->dept_name);?></option>
                            <?php }?>
                        <?php }?>
                    </select>                     
                </div>
                </div>
            </div>
            <div class="form-group">
                
                <label class="form-label text-muted"><em>Can access the following modules:</em></label>
                <div class="row">
                    <div class="col-6 col-sm-3">
                        <div class="checkbox-inline">
                            <label><input type="checkbox" name="module[]" value="dashboard" checked="true" disabled="disabled"> Dashboard</label>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="checkbox-inline">
                            <label><input type="checkbox" name="module[]" value="vehicles"> Vehicles</label>
                        </div>
                     </div> 
                    <div class="col-6 col-sm-3">
                        <div class="checkbox-inline">
                            <label><input type="checkbox" name="module[]" value="transactions"> Transactions</label>
                        </div>
                    </div> 
                    <div class="col-6 col-sm-3">
                        <div class="checkbox-inline">
                            <label><input type="checkbox" name="module[]" value="invoices"> Invoices</label>
                        </div>
                    </div> 
                    <div class="col-6 col-sm-3">
                        <div class="checkbox-inline">
                            <label><input type="checkbox" name="module[]" value="citations"> Citations</label>
                        </div>
                    </div>  
                    <div class="col-6 col-sm-3">
                        <div class="checkbox-inline">
                            <label><input type="checkbox" name="module[]" value="transponder"> Transponder</label>
                        </div>
                    </div>                       
                </div>
            </div>
            <div class="form-check">
                <label><em>User can update vehicles?</em></label><br/>
                <label class="form-radio-label ml-3">
                    <input class="form-radio-input" type="radio" name="vehicle_updater" value="0" checked="checked">
                    <span class="form-radio-sign">No</span>
                </label>
                <label class="form-radio-label ml-3">
                    <input class="form-radio-input" type="radio" name="vehicle_updater" value="1">
                    <span class="form-radio-sign">Yes</span>
                </label>
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
        <button class="btn btn-success btn-xs" id="clientUserSave" onclick="save_client_user()"><i class="fa fa-save"></i> Save Account</button>
      </div>
    </div>
  </div>
</div>


<!-- Pass Change Modal -->
<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body form">
      <div id="pass_msg"></div>
        <?php echo form_open('#', ['id' => 'change_pass']);?>
        <input type="hidden" name="id" id="id" />
            <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                    <input type="password" name="pass" placeholder="New password" class="form-control input-sm" />
                </div>
                <div class="col-md-6">
                    <input type="password" name="conf_pass" placeholder="Re-type new password" class="form-control input-sm" />
                </div>
                </div>
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-warning btn-xs" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
        <button class="btn btn-success btn-xs" onclick="update_pass()"><i class="fa fa-refresh"></i> Update Password</button>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('templates/includes/footer_end'); ?>