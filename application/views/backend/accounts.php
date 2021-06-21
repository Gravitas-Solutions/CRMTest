<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
    <div class="content">
        <div class="page-inner">
            <div class="row">
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-header">
                          <div class="card-head-row">
                              <div class="card-title"><i class="fa fa-map-marker"></i> Accounts Balance Management</div>
                              <div class="card-tools">
                                  <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                              </div>
                          </div>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                            <thead> 
                                  <th>#</th>
                                  <th>Client</th>
                                  <th>Department</th>
                                  <th>Balance</th>
                                  <th  class="noExport">Action</th>
                            </thead>
                            <tbody>
                              <?php $i = 0; foreach ($account_balance as $balance) {?>
                              <tr>
                                  <td><?php echo ++$i; ?></td>
                                  <td><?php echo ucwords(str_replace('_', ' ', $balance->organization));?></td>
                                  <td><?php echo ucwords(str_replace('_', ' ', $balance->dept_name));?></td>
                                  <td><?php echo '$'.$balance->balance;?></td>
                                  <td><button class="btn btn-primary btn-outline btn-xs" onclick= "get_client_balance(<?php echo $balance->dept_id;?>)"><i class="fa fa-plus-circle"></i></button> 
                                    <button class="btn btn-primary btn-outline btn-xs" onclick="edit_account(<?php echo $balance->dept_id;?>)" title="Edit"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-info btn-outline btn-xs" onclick="view_accounts_details(<?php echo $balance->dept_id;?>)" title="View Account Details"><i class="fa fa-eye"></i></button>
                                  </td>
                              </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div><!-- panel-body -->
                  </div><!-- panel panel-primary -->
                </div>
              </div>
          </div>
        </div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
  
function add_balance(){
  $('#add_msg').html('');
    $('#add_balance').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url : "<?php echo site_url('backend/admin/add_balance')?>/",
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#updateBalance').modal('hide');
                    alert(data.msg);
                    location.reload();

                }
                else{
                    $('#add_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / Adding Amount');  
            }
        });
    });
}

function edit_current_balance(){
  $('#edit_msg').html('');
    $('#edit_balance_form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url : "<?php echo site_url('backend/admin/edit_balance')?>/",
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(data) {
                if(data.status){
                    $('#edit_balance').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error Updating Amount');  
            }
        });
    });
}

  //view clock_in/out day
  function get_client_balance(id){
      $.ajax({
      url: "<?php echo base_url() ?>backend/admin/get_client_balance/"+id,
      type: "GET",
      dataType: 'JSON',
      success: function(data){
        console.log(data);
        $('#account_balance_id').val(data.account_balance_id);
        $('#acc_balance').val(data.balance);
        $('#dept_id').val(data.dept_id);
        $('#selected_dept').val(data.dept_name);
        $('#selected_client').val(data.organization);
        $('#updateBalance').modal('show');
      }
    });
  }

    //view account transactions per day
  function view_accounts_details(dept_id){
      $.ajax({
      url: "<?php echo base_url() ?>backend/admin/view_accounts_details/"+dept_id,
      type: "GET",
      dataType: 'JSON',
      success: function(data){
        console.log(data);
        $('#transaction_row').empty();
        if (data.length != 0) {
        
          $.each(data, function(index, val) {
            $('#transaction_row').append(
              '<tr>'+
                '<td>'+val.amount+'</td>'+
                '<td>'+val.transaction_date+'</td>'+
                '<td>'+val.source+'</td>'+
              '</tr>'
            );
          });   
        } else {
          $('#transaction_row').append(
              '<tr>'+
                '<td colspan="3"><span class="text-danger" style="font-weight: bolder">No transaction records for the specified client</span></td>'+
              '</tr>'
          );
        } 
        $('#view_accountModal').modal('show');
      }
    });
  }

  function edit_account(id){
      $('#edit_msg').html('');
        $.ajax({
        url: "<?php echo base_url() ?>backend/admin/get_client_balance/"+id,
        type: "GET",
        dataType: 'JSON',
        success: function(data){         
            $('#account_id').val(data.account_balance_id);
            $('#client_id').val(data.client_id);
            $('#balance').val(data.balance);
            $('#dept').val(data.dept_name);
            $('#edit_dept_id').val(data.dept_id);
            $('#organization').val(data.organization);
            $('#edit_balance').modal('show');
        }
      });
    }

</script>


<!-- update  account balance Modal-->
   <div class="modal fade" id="updateBalance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
       <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Account Balance</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <div class="clearfix"></div>
          </div>
          <div class="modal-body form-horizontal">
          <div id="msg"></div>
           <?php echo form_open('#', ['method' => 'POST', 'id' => 'add_balance']);?>
            <input type="hidden" name = "account_balance_id" id="account_balance_id" />
            <input type="hidden" name = "dept_id" id="dept_id" />
              <div class="form-group">
                    <div class="row">
                      <div class="col-md-6"><i class="fa fa-user"></i>  Client: </div>
                      <div class="col-md-6">
                        <input name="selected_client" type="text" id="selected_client" readonly="readonly"  class="form-control input-sm" value="">
                      </div>
                  </div>    
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-md-6"><i class="fa fa-user"></i>  Dept: </div>
                      <div class="col-md-6">
                          <input name="selected_dept" type="text" id="selected_dept" readonly="readonly"  class="form-control input-sm" value="">
                      </div>    
                </div>
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-md-6"><i class="fa fa-money"></i>  Balance: </div>
                    <div class="col-md-6">
                      <input name="acc_balance" type="text" step="0.01" class="form-control input-sm" readonly="readonly" id="acc_balance" value="">
                    </div>            
                  </div>
                </div>
              <div class="form-group">
                  <div class="row"><div class="col-md-6"><i class="fa fa-money"></i>  Amount to Add: </div>
                    <div class="col-md-6">
                      <input name="amount" type="number" step="0.01" class="form-control input-sm" value="">
                    </div>
                  </div> 
              </div>
            <div class="modal-footer">
              <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
              <button class="btn btn-success btn-sm" onclick="add_balance()"><i class="fa fa-add"></i> Add Amount</button>
            </div>
        </form>
        </div>
        </div>
      </div>
    </div>

    <!--View modal-->
<div class="modal fade" id="view_accountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="text-muted" id="exampleModalLabel">Monthly Transaction Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
      <div class="row">
        <table id="table_id" class="table table-condensed table-hover">
          <thead>
            <th>AMOUNT</th>
            <th>TRANSACTION DATE</th>
            <th>SOURCE</th>
          </thead>
          <tbody id="transaction_row">
            
          </tbody>
      </table>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-outline btn-xs" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
      </div>
    </div>
  </div>
</div>

<!-- update  account balance Modal-->
   <div class="modal fade" id="edit_balance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
       <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Account Balance</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <div class="clearfix"></div>
          </div>
          <div class="modal-body form-horizontal">
          <div id="msg"></div>
           <?php echo form_open('#', ['method' => 'POST', 'id' => 'edit_balance_form']);?>
            <div class="form-group">
                  <input type="hidden" name = "account_id" id="account_id" />
                  <input type="hidden" name = "edit_dept_id" id="edit_dept_id" />
                  <div class="row"><div class="col-md-6"><i class="fa fa-user"></i>  Client: </div><div class="col-md-6">
                      <input name="organization" type="text" id="organization" readonly="readonly"   class="form-control input-sm" value=""></label></div>
                  </div>    
            </div>
            <div class="form-group">
                  <input type="hidden" name = "account_id" id="account_id" />
                  <div class="row"><div class="col-md-6"><i class="fa fa-user"></i>  Dept: </div><div class="col-md-6">
                      <input name="organization" type="text" id="dept" readonly="readonly"   class="form-control input-sm" value=""></label></div>
                  </div>    
            </div>
            

            <div class="form-group">
                    <div class="row"><div class="col-md-6"><i class="fa fa-money"></i>  Current Balance: </div><div class="col-md-6">
                      <input name="balance" type="text" step="0.01" class="form-control input-sm" readonly="readonly" id="balance" value=""></div>
                  </div> 
            </div>

          <div class="form-group">
                  <div class="row"><div class="col-md-6"><i class="fa fa-money"></i>  New Balance: </div><div class="col-md-6">
                    <input name="new_balance" type="number" step="0.01" class="form-control input-sm" value=""></div>
                </div> 
          </div>

        <div class="clearfix"></div>
        <div class="modal-footer">
              <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
              <button class="btn btn-success btn-sm" onclick="edit_current_balance()"><i class="fa fa-add"></i> Edit Balance</button>      
          <!-- <div class="form-group col-md-3 pull-right">
            <div><button class="btn btn-success btn-sm" onclick="edit_current_balance()"><i class="fa fa-add"></i> Edit Balance</button></div> 
          </div>  -->
        </div>
        </form>
        </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>