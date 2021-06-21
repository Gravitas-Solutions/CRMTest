<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-map-marker"></i> Transactions Dumps</div>
                                    <div class="card-tools">
                                        <!-- <a href="<?php echo base_url()?>backend/admin/dump_stats" class="btn btn-info btn-xs"><i class="fa fa-bar-chart-o"></i> Dump Stats</a> -->
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
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
                                <th>Dept</th>
                                <th>Uploader </th>
                                 <th>Uploaded On </th>
                                 <th>For Month</th>
                                <th>Records</th>
                                <th>File</th>
                                <th><i class="fa fa-file-excel"></i></th>
                                <th><i class="fa fa-undo"></i></th>
                            </thead>
                            <tbody>
                                <?php $i = 0; foreach ($excel_dumps as $dump) {?>
                                <tr>
                                    <td><?php echo ++$i; ?></td>
                                    <td><?php echo ucwords(str_replace('_', ' ', $dump->client_name));?></td>
                                    <td><?php echo ($dump->dept_name) ? ucwords(str_replace('_', ' ', $dump->dept_name)) : '<center>-</center>';?>
                                    </td>
                                    <td><a href="mailto:<?php echo $this->ion_auth->user($dump->uploaded_by)->row()->email;?>"><?php echo $this->ion_auth->user($dump->uploaded_by)->row()->email;?></a></td>
                                    <td><?php echo nice_date($dump->uploaded_date, 'Y-m-d h:i:s');?></td>
                                    <td><?php echo nice_date($dump->date_for, 'Y-m-d');?></td>
                                    <td class="text-right"><?php echo $dump->total_row;?></td>
                                    <td><?php echo $dump->filename;?></td>
                                    <td class=text-center>
                                        <?php if($dump->account_id == -1){ ?>
                                            <?php echo "<center>-</center>";?>
                                           
                                        <?php }else{ ?>
                                            <a href="<?php echo base_url();?>uploads/agency_invoices/<?php echo $dump->filename;?>"> <i class="fa fa-download text-success"></i></a>
                                        <?php } ?> 
                                        </td>
                                    <td class=text-center>
                                     <?php if($dump->is_deleted == 1){ ?>
                                            <?php echo "<center>-</center>";?>
                                           
                                        <?php }else{ ?>                            
                                            <a onclick="undo(<?php echo $dump->excel_dump_id;?>)" title = "Undo"><span><i class="fa fa-undo text-info"></i></span></a>
                                         <?php } ?> 
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                     </div>
                </div>
            </div><!-- panel-body -->
        </div><!-- panel panel-primary -->
        </div>
    </div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
    var url="<?php echo base_url();?>";
    function undo(id){
       var r=confirm("Do you want to undo the upload?")
        if (r==true)
          window.location = url+"backend/admin/delete_excel/"+id;
        else
          return false;
    }
</script>

<?php $this->load->view('templates/includes/footer_end'); ?>