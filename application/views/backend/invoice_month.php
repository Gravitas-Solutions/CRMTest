<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title"><i class="fa fa-calendar"></i> Monthly Invoice Amount</div>
                                <div class="card-tools">                                       </a>
                                    <a href="<?php echo base_url()?>admin/dashboard"><i class="fas fa-step-backward fa-1x"></i> </a>
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
                              <th>Month Displayed</th>
                              <th>Amount Displayed</th>
                              <th class="noExport">Action</th>
                            </thead>
                            <tbody>
                              <?php $i = 0; foreach ($invoice_details as $invoice) {?>
                              <tr>
                                  <td><?php echo ++$i; ?></td>
                                  <td><?php echo ucwords(str_replace('_', ' ', $invoice->client_name));?></td>
                                  <td><?php echo ucwords($invoice->dept_name);?></td>
                                  <td><?php echo ucwords($invoice->month);?></td>
                                  <td><?php echo '$ '.$invoice->invoice_amount;?></td>
                                  <td>
                                    <button class="btn btn-primary btn-outline btn-xs" onclick="update_displayed_invoice_amount(<?php echo $invoice->invoice_month_id;?>)" title="Edit"><i class="fa fa-edit"></i></button>
                                  </td>
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
function edit_amount(){
    $('#edit_invoice_amount_form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url : "<?php echo site_url('backend/admin/edit_month_invoice_amount')?>/",
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#edit_displayed_amount').modal('hide');
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

  function update_displayed_invoice_amount(id){
        $.ajax({
        url: "<?php echo base_url() ?>backend/admin/update_displayed_invoice_amount/"+id,
        type: "GET",
        dataType: 'JSON',
        success: function(data){
            
            $('#invoice_id').val(data.invoice_month_id);
            $('#client').val(data.client_name);
            $('#displayed_invoice').val(data.invoice_amount);
            $('#invoice_month option[value="'+data.month+'"').attr('selected', 'selected');
            $('#edit_displayed_amount').modal('show');
        }
      });
    }

</script>

<!-- update  account balance Modal-->
<div class="modal fade" id="edit_displayed_amount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
       <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change Displayed Invoice Amount</h5>
            <button class="close pull-right" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <div class="clearfix"></div>
          </div>
          <div class="modal-body form-horizontal">
          <div id="msg"></div>
           <?php echo form_open('#', ['method' => 'POST', 'id' => 'edit_invoice_amount_form']);?>
            <div class="form-group">
              <div class="row">
                  <input type="hidden" name = "invoice_month_id" id="invoice_id" />
                    <div class="col-md-6">
                      <i class="fa fa-user"></i>  Client: 
                    </div>
                    <div class="col-md-6">
                      <input name="client_name" type="text" id="client"  class="form-control input-sm" readonly = "readonly" value="">
                    </div> 
                </div>   
            </div>
            <div class="form-group">
              <div class="row">
                    <div class="col-md-6">
                      <i class="fa fa-money">  </i>  Displayed Invoice Amount: 
                    </div>
                    <div class="col-md-6">
                    <input name="invoice_amount" type="number" step="0.01" class="form-control input-sm" id="displayed_invoice" value="">
                  </div> 
                </div>
            </div>
          <div class="form-group">
            <div class="row">
                    <div class="col-md-6"><i class="fa fa-calendar">  
                      </i>  Month: 
                    </div>
                  <div class="col-md-6">
                    <select name="month" class="form-control input-sm" id="invoice_month">
                        <option value="">Month</option>
                        <option value="Jan">Jan</option>
                        <option value="Feb">Feb</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>
                  </div>
              </div>
          </div>

        <div class="clearfix"></div>
        <div class="modal-footer">          
          <div class="form-group col-md-3 pull-right">
            <div><button class="btn btn-primary btn-sm" onclick="edit_amount()"><i class="fa fa-add"></i> Update</button></div> 
          </div> 
        </div>
        </form>
        </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>