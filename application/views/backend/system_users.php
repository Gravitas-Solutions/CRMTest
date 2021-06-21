<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-users"></i> System Users</div>
                                    <div class="card-tools">
                                        <a class="btn btn-primary btn-xs mr-2" onclick="add_system_user()"><i class="fa fa-plus-circle"></i> New User</a>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                                        <thead>
                                            <th>#</th>
                                            <th>Email</th>
                                            <th>Created On</th>
                                            <th>Last Login</th>
                                            <th>Status</th>
                                            <th class="noExport">Action</th>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0; foreach ($users as $user) { $i++; ?>
                                            <tr>
                                                <td><?php echo $i;?></td>
                                                <td><a href="mailto:<?php echo $user->email ?>"><?php echo $user->email ?></a></td>
                                                <td><?php echo nice_date(date('Y-m-d H:i:s', $user->created_on), 'F j, Y @ H:i:s') ?></td>
                                                <td><center><?php echo ($user->last_login == '') ? '-' : nice_date(date('Y-m-d H:i:s', $user->last_login), 'F j, Y @ H:i:s') ?></center></td>
                                                <td><button class="<?php echo ($user->active) ? 'btn btn-success btn-outline btn-xs':'btn btn-danger btn-outline btn-xs';?>" onclick="activate_client(<?php echo $user->id;?>)" title = "Change status"><span><i class="fa fa-refresh"></i> <?php echo ($user->active) ? 'Active':'Inactive';?></span></button></td>
                                                <td>
                                                    <button class="btn btn-info btn-xs" onclick="change_pass(<?php echo $user->id;?>)" title = "Chnage account password">&nbsp;<i class="fa fa-lock"></i>&nbsp;</button> | 
                                                    <button class="btn btn-warning btn-xs" onclick="edit_system_user(<?php echo $user->id;?>)" title = "Update system user email">&nbsp;<i class="fa fa-envelope"></i>&nbsp;</button></td>   
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- .panel-body -->
                        </div><!-- .panel-default -->
                    </div>
            </div><!-- #page-wrapper -->
    </div><!-- #wrapper -->
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">

$(document).keypress(function(e){
    if (e.which == 13){
        $("#systemUserSave").click();
        return false;
    }
});

function add_system_user(){
    save_method = 'add';
    $('#new_system_user')[0].reset();
    $('#systemUserSave').text('Create Account') 
    $('#user_msg').html(''); 
    $('.modal-title').text('New System User');
    $('#pass_info').css('display', 'block'); 
    $('#systemUserModal').modal('show'); 
}

function edit_system_user(id){
    save_method = 'update';
    $('#new_system_user')[0].reset();  
    $('#systemUserSave').text('Update Email Address')  
    $('#pass_info').css('display', 'none');
    $('#user_msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/system_user')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.status){
                $('input[name="system_user_id"]').val(data.msg.id);
                $('input[name="system_user_email"]').val(data.msg.email);
                $('#systemUserModal').modal('show'); 
                $('.modal-title').text('Update User Email');
            }else{
                alert(data.msg);
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
        }
    });
}

function save_system_user(){
    $('#systemUserSave').text('saving...').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_system_user')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_system_user')?>";
    }    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_system_user').serialize(),
            dataType: "JSON",
            success: function(data){
                if(data.status){
                    $('#systemUserModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#user_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                    $('#user_msg').append('<hr>');
                }
                $('#systemUserSave').text('Create User Account'); 
                $('#systemUserSave').attr('disabled', false); 
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error adding / update data');
                $('#systemUserSave').text('Create User Account'); 
                $('#systemUserSave').attr('disabled',false);  
            }
        });
}

function change_pass(id){
    $('#change_pass')[0].reset();
    $('input[name="id"]').val(id);
    $('#pass_msg').html(''); 
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
        success: function(data){
            alert('Status change success');
            location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error updating status');
        }
    });
}
</script>

<!-- New User Modal-->
<div class="modal fade" id="systemUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New system user</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
      </div>
      <div class="modal-body form">
        <div id="user_msg"></div>
        <?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_system_user', 'method' => 'POST']);?>
            <input type="hidden" value="" name="system_user_id"/>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">User email address: </label>
                    </div>
                    <div class="col-md-6">
                        <input name="system_user_email" placeholder="Email address" class="form-control input-sm" type="text" />
                    </div>
                </div>
            </div>
            <div class="form-group" id="pass_info">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Choose password: </label>
                    </div>
                    <div class="col-md-6">
                        <input name="user_password" placeholder="Choose your password" class="form-control input-sm" type="password" />
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning btn-xs" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
        <button class="btn btn-success btn-xs" onclick="save_system_user()"><i class="fa fa-save"></i> <span id="systemUserSave"></span></button>
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