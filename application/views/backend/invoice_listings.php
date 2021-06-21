<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title"><i class="fa fa-file-excel"></i> Client Invoices</div>
                                <div class="card-tools">
                                     <a href="<?php echo base_url()?>backend/admin/client_invoices" class="btn btn-primary btn-xs"><i class="fa fa-plus-circle"></i> New Invoice</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php echo $this->session->flashdata('message'); ?> 
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                                    <thead>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Invoice Date</th>
                                        <th>Paid Date</th>
                                        <th>Toll Amount</th>
                                        <th>Type of Fees</th>
                                        <th>Fees Amount</th>
                                        <th>Invoice Amount</th>
                                        <th>Invoice Status</th>
                                        <th>Department</th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; foreach ($invoices as $invoice) {?>
                                        <tr>
                                            <td><?php echo ++$i; ?></td>
                                            <td><?php  echo ucwords(str_replace('_', ' ', $invoice->client_name));?></td>
                                            <td><?php echo nice_date($invoice->invoice_date, 'Y-m-d h:i:s');?></td>
                                            <td><center><?php echo ($invoice->pay_date == '0000-00-00 00:00:00') ? '<center>-</center> ' : nice_date($invoice->pay_date, 'Y-m-d h:i:s');?></center></td>
                                            <td><?php echo '$'.number_format($invoice->toll_amount, 2);?></td>
                                            <td><?php  echo ucwords(str_replace('_', ' ', $invoice->fee_type));?></td>
                                            <td><?php echo '$'.number_format($invoice->toll_fee, 2);?></td>
                                            <td><?php echo '$'.number_format($invoice->invoice_amount, 2);?></td>
                                            <td><?php echo ($invoice->invoice_status) ? 'Open': 'Closed';?></td>
                                            <td><?php echo $invoice->dept_name;?></td>
                                            <td>
                                                <a href="<?php echo base_url();?>uploads/client_invoices/<?php echo $invoice->excel;?>" class="btn btn-info btn-xs" > <i class="fa fa-file-excel text-success" title="Download Excel"></i></a> | 
                                                <a href="<?php echo base_url();?>uploads/client_invoices/<?php echo $invoice->pdf;?>" class="btn btn-info btn-xs" > <i class="fa fa-file-pdf text-danger" title="Download PDF"></i></a> | 
                                                <button class="btn btn-warning btn-xs " onclick="edit_invoice(<?php echo $invoice->invoice_id;?>)" title = "Edit Invoice details"><i class="fa fa-edit"></i></button> | 
                                                <button class="btn btn-danger btn-xs " onclick="undo(<?php echo $invoice->invoice_id;?>, <?php echo "'".$invoice->client_name."'";?>, <?php echo "'".$invoice->dept."'";?>)" title = "Delete Invoice"><span><i class="fa fa-trash"></i></span></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- panel panel-primary -->
        </div>
    </div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
    var url="<?php echo base_url();?>";
    function undo(id, client, dept){
       var r=confirm("Are you sure you want to delete the invoice?")
        if (r==true)
          window.location = url+"backend/admin/delete_invoice/"+id+"/"+client+"/"+dept;
        else
          return false;
        } 

     function edit_invoice(invoice_id){
          window.location = url+"backend/admin/edit_invoice/"+invoice_id;
        } 
</script>
 <?php $this->load->view('templates/includes/footer_end'); ?>