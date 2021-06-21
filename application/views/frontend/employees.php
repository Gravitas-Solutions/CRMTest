<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Page start -->
<div id="page-wrapper">
<!-- <div class="row">
<div class="col-md-12">
    <h3 class="page-header text-muted">Employees</h3>
</div>
</div> -->
<!-- /.row -->
<div class="row">
<div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-users"></i> Manage Employee <span class="text-right"><a class="btn btn-primary btn-outline btn-xs pull-right" onclick="add_employee()"><i class="fa fa-plus-circle"></i> Add Employee</a></span></div>
    <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-condensed table-bordered" id="table_id">
            <thead>
              <th>S.#</th>
              <th>Name</th>
              <th>Address</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Recruited on</th>
                    <th>Hours</th>
              <th>Hourly Rate</th>
              <th>Files</th>
              <th class="noExport">Actions</th>
            </thead>
            <tbody>
              <?php $i = 0; foreach($employees as $emp) {
                    if ($this->ion_auth->is_admin($emp->id)){continue;} ?>
              <tr>
                <td><?php echo ++$i;?></td>
                <td><?php echo ucwords($emp->first_name.' '.$emp->last_name)?></td>
                <td><?php echo ucwords($emp->address)?></td>
                <td><?php echo $emp->phone?></td>
                <td><a href="mailto:<?php echo $emp->email?>"><?php echo $emp->email?></a></td>
                <td><?php echo nice_date($emp->recruitment, 'F j, Y')?></td>
                        <td><?php echo $emp->working_hours?>hrs</td>
                <td>$<?php echo $emp->pay_rate?></td>
                <td><?php if ($emp->contractfile == null){ echo '-';}else{ ?>
                  <a href="<?php echo base_url()?>uploads/contracts/<?php echo $emp->contractfile?>" title="Contract"><i class="fa fa-file-pdf-o text-danger"></i></a><?php }?> 
                            &nbsp;&nbsp;| &nbsp;&nbsp;
                            <?php if ($emp->taxfile == null){ echo '-';}else{ ?>
                  <a href="<?php echo base_url()?>uploads/taxfiles/<?php echo $emp->taxfile?>" title="Tax file"><i class="fa fa-file-pdf-o text-warning"></i></a><?php }?>
                </td>
                <td>
                  <button class="btn btn-primary btn-outline btn-xs" onclick="edit_employee(<?php echo $emp->id;?>)" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-info btn-outline btn-xs" onclick="change_pass(<?php echo $emp->id;?>)" title="Update password"><i class="fa fa-lock"></i></button>
                  <button class="<?php echo ($emp->active) ? 'btn btn-success btn-outline btn-xs':'btn btn-danger btn-outline btn-xs';?>" onclick="employee_status(<?php echo $emp->id;?>)" title = "Change status"><span><i class="fa fa-refresh"></i> <?php echo ($emp->active) ? 'Active':'Inactive';?></span></button>
                </td>
              </tr>
              <?php }?>
            </tbody>
            <tfoot>
              <th>S.#</th>
              <th>Name</th>
              <th>Address</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Recruited on</th>
              <th>Hours</th>
                    <th>Hourly Rate</th>
              <th>Files</th>
              <th>Actions</th>
            </tfoot>
          </table>
        </div>
    </div>
</div>
</div>
<!-- /.row -->
</div><!-- /#page-wrapper -->

<script type="text/javascript">
$(document).keypress(function(e){
    if (e.which == 13){
        $("#employeeSave").click();
        return false;
    }
});

function add_employee(){
  save_method = 'add';
    $('#pass_mode').show();
    $('#new_employee')[0].reset();
    $('#emp_msg').html(''); 
    $('#employeeModal').modal('show'); 
    $('.modal-title').text('Add Employee'); 
}

function edit_employee(id){
    save_method = 'update';
    $('#new_employee')[0].reset(); 
    $('#emp_msg').html('');
    $('#pass_mode').hide();

    $.ajax({
        url : "<?php echo site_url('admin/edit_employee')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          console.log(data);
            if(data.status){
                $('[name="id"]').val(data.employee.id);
                $('[name="first_name"]').val(data.employee.first_name);
                $('[name="last_name"]').val(data.employee.last_name);
                $('[name="phone"]').val(data.employee.phone);
                $('[name="email"]').val(data.employee.email);
                $('[name="hours"]').val(data.employee.working_hours);
                $('[name="pay_rate"]').val(data.employee.pay_rate);
                $('[name="recruitment"]').val(data.employee.recruitment);
                $('[name="address"]').val(data.employee.address);
                $('input:radio[name="is_admin"][value="' + data.employee.is_admin + '"]').prop("checked", true);

                $('#employeeModal').modal('show'); 
                $('#employeeSave').text('Update employee');
                $('.modal-title').html('Update <span class="text-muted">'+ data.employee.first_name + '\'s </span>details');
            }else{
                Swal.fire({
                  position: 'top-end',
                  type: 'error',
                  title: data.msg,
                  showConfirmButton: false,
                  timer: 2000
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Swal.fire({
              position: 'top-end',
              type: 'error',
              title: 'Error getting employee data',
              showConfirmButton: false,
              timer: 2000
            });
        }
    });
}

function save(){
    $('#employeeSave').text('saving...'); 
    $('#employeeSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('admin/add_employee')?>";
    } else {
        url = "<?php echo site_url('admin/update_employee')?>";
    }

    var formData = new FormData($('#new_employee')[0]);  
        $.ajax({
          url : url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(data){
                if(data.status){
                    $('#employeeModal').modal('hide');
                    Swal.fire({
                      position: 'top-end',
                      type: 'success',
                      title: data.msg,
                      showConfirmButton: false,
                      timer: 2000
                    });
                    setTimeout(function(){location.reload();}, 2000);
                }else{
                    $('#emp_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#employeeSave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown){
                Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Error saving/updating employee data',
                  showConfirmButton: false,
                  timer: 2000
                });
                $('#employeeSave').attr('disabled',false);  
            }
        });
}

function employee_status(id){
        $.ajax({
            url : "<?php echo site_url('admin/employee_status')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Status change success',
                  showConfirmButton: false,
                  timer: 2000
                });
                setTimeout(function(){location.reload();}, 2000);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire({
                  position: 'top-end',
                  type: 'error',
                  title: 'Error updating employee status',
                  showConfirmButton: false,
                  timer: 2000
                });
            }
        });
}

function change_pass(id){
    $('#change_pass')[0].reset();
    $('#pass_msg').html(''); 
    $('#id').val(id);
    $('#passModal').modal('show');
}

function update_pass(){
  $.ajax({
      url : "<?php echo site_url('admin/update_password')?>",
      type: "POST",
      data: {id: $('#id').val(), password: $('input[name="change_password"]').val(), password_confirm: $('input[name="change_password_conf"]').val()},
      dataType: "JSON",
      success: function(data)
      {
        if(data.status){
            Swal.fire({
              position: 'top-end',
              type: 'success',
              title: data.msg,
              showConfirmButton: false,
              timer: 2000
            });
            setTimeout(function(){location.reload();}, 2000);
        }
        else{
            $('#pass_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          Swal.fire({
            position: 'top-end',
            type: 'error',
            title: 'Error adding/updating data',
            showConfirmButton: false,
            timer: 2000
          }); 
      }
  });
}
</script>

<!-- Employee Modal-->
<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Add Employee</h4>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body form">
        <div id="emp_msg"></div>
        <?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_employee', 'method' => 'POST']);?>
        <input type="hidden" value="" name="id"/> 
          <div class="form-group">
              <div class="col-md-6">
                  <input name="first_name" placeholder="First Name" class="form-control input-sm" type="text" />
              </div>
              <div class="col-md-6">
                  <input name="last_name" placeholder="Last Name" class="form-control input-sm" type="text" />
              </div>
          </div>
          <div class="form-group">
              <div class="col-md-6">
                  <input name="email" placeholder="Email" class="form-control input-sm" type="text" />
              </div>
              <div class="col-md-6">
                  <input name="phone" placeholder="Phone" class="form-control input-sm" type="text" />
              </div>
          </div>
          <div class="form-group">
              <div class="col-md-6">
                  <input name="recruitment" placeholder="Recruitment on..." class="form-control input-sm" type="text" id="recruitment" />
              </div>
              <div class="col-md-3">
                    <input name="hours" placeholder="Working Hours" class="form-control input-sm" type="text" />
                </div>
                <div class="col-md-3">
                  <input name="pay_rate" placeholder="Pay Rate" class="form-control input-sm" type="text" />
              </div>
          </div>
          <div class="form-group">
                <div class="col-md-12">
                    <textarea name="address" class="form-control input-sm" placeholder="Addressddress"></textarea>
                </div>
            </div>
          <div class="form-group" id="pass_mode">
          <div class="col-md-6">
                    <input name="password"  type="password" class="form-control input-sm" placeholder="Choose password" />
          </div>
          <div class="col-md-6">
                    <input name="password_conf"  type="password" class="form-control input-sm" placeholder="Re-type password" />
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12"><hr />
            <div class="radio-inline">Can perform administrative duties?</div>
            <div class="radio-inline">
                <input type="radio" name="is_admin" value="0" checked="checked"> No
            </div>                    
            <div class="radio-inline">
                <input type="radio" name="is_admin" value="1"> Yes
            </div>
          </div>        
        </div>        
      </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning btn-xs btn-outline" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
        <button class="btn btn-success btn-xs btn-outline" id="employeeSave" onclick="save()"><i class="fa fa-save"></i> Save Employee</button>
      </div>
    </div>
  </div>
</div>

<!-- Pass change modal -->
<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Change Employee Password</h4>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body form">
      <div id="pass_msg"></div>
        <?php echo form_open('#', ['id' => 'change_pass']);?>
        <input type="hidden" name="id" id="id" />
            <div class="form-group">
                <div class="col-md-4">
                    <input type="password" name="change_password" placeholder="New password" class="form-control input-sm" />
                </div>
                <div class="col-md-4">
                    <input type="password" name="change_password_conf" placeholder="Re-type new password" class="form-control input-sm" />
                </div>
                <div class="col-md-2">
                    <span class="btn btn-success btn-outline btn-xs" onclick="update_pass()"><i class="fa fa-refresh"></i> Update Password</span>
                </div>
            </div>
        </form>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
