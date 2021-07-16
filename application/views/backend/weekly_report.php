<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-institution"></i>Weekly Reports</div>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-xs" onclick="add_weekly_report()"><i class="fa fa-plus-circle"></i> Add Report</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
        				<div class="table-responsive">
            				<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
            					<thead>
            						<th>#</th>
                                    <th>Client</th>
            						<th>Type</th>
            						<th>Week</th>
                                    <th>Posted Date</th>
                                    <th>Records</th>
                                    <th>Amount</th>
            						<th class="noExport">Actions</th>
            					</thead>
            					<tbody>
            						<?php $i = 0; foreach ($reports as $report) {?>
            						<tr>
            							<td><?php echo ++$i; ?></td>
            							<td><?php echo strtoupper(ucwords(str_replace('_', ' ', $report->organization))) ?></td>
                                        <td><?php echo strtoupper(ucwords( $report->report_type)) ?></td>
                                        <td><?php echo $report->week_name ?></td>
                                        <td><?php echo nice_date($report->end_week_date, 'm-d-Y');?></td>
                                        <td><?php echo $report->records ?></td>
                                        <td><?php echo '$'.number_format($report->amount, 2);?></td>
            							<td>
            								<a href="<?php echo base_url()?>uploads/weekly_report/<?php echo $report->file_name ?>" class="btn btn-lg"><i class="fa fa-file-excel text-success" title = "Download Weekly Report"></i></a>|
                                            <button class="btn btn-warning btn-xs" onclick="edit_weekly_report(<?php echo $report->weekly_report_id;?>)" title = "Edit report details"><i class="fa fa-edit"></i></button> |
            								<button class="btn btn-danger btn-xs" onclick="delete_weekly_report(<?php echo $report->weekly_report_id;?>)" title = "Delete report"><i class="fa fa-trash"></i></button>
            							</td>
            						</tr>
            						<?php } ?>
            					</tbody>
            				</table>
        				</div>
		             </div><!-- panel-body -->
	            </div><!-- card body end -->
	        </div>
       </div>
    </div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">

function add_weekly_report(){
	save_method = 'add';
    $('#new_weekly_report')[0].reset();
    $('#download').hide();
    $('#msg').html(''); 
    $('#reportModel').modal('show'); 
    $('#weeklyReportSave').text('Save Weekly Report');
    $('.modal-title').text('Add Weekly Report'); 
}

function edit_weekly_report(id){
    save_method = 'update';
    $('#new_weekly_report')[0].reset(); 
    $('#msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_weekly_report')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.report_data.weekly_report_id);
                $('[name="amount"]').val(data.report_data.amount);
                $('[name="records"]').val(data.report_data.records);
                $('select[name="week"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.report_data.week_id;
                }).prop('selected', true);

                $('select[name="report_type"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.report_data.report_type_id;
                }).prop('selected', true);

                $('select[name="client_id"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.report_data.client_id;
                }).prop('selected', true);
                $('[name="end_week_date"]').val(data.report_data.end_week_date);

                $('#download').attr('href','<?php echo base_url();?>uploads/weekly_report/' + data.report_data.file_name);
                $('#download').show();
                $('#reportModel').modal('show'); 
                $('#weeklyReportSave').text('Update Weekly Report');
                $('.modal-title').text('Edit Weekly Report');
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

function save(){
    $('#weeklyReportSave').text('Saving...'); 
    $('#weeklyReportSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_weekly_report')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_weekly_report')?>";
    }
    
    var report_data = new FormData($('#new_weekly_report')[0]);
        $.ajax({
            url : url,
            type: "POST",
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,
            data: report_data,
            success: function(data)
            {
                if(data.status){
                    $('#reportModel').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#weeklyReportSave').text('Save Weekly Report'); 
                $('#weeklyReportSave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#weeklyReportSave').text('Save Weekly Report'); 
                $('#weeklyReportSave').attr('disabled',false);  
            }
        });
}

function delete_weekly_report(id){
    if(confirm('Are you sure you want to delete this Weekly Report?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_weekly_report')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                alert('Record deleted successfully');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
    }
}
</script>

<!-- New Weekly Report Modal-->
    <div class="modal fade" id="reportModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Weekly Report</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<div id="msg"></div>
          	<?php echo form_open('#', ['class' => 'form-horizontal', 'id' => 'new_weekly_report', 'method' => 'POST']);?>
          	<input type="hidden" value="" name="id"/> 
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                        <select name="week" class="form-control">
                            <option selected="selected" disabled="disabled" value="">--Select Week--</option>
                            <?php foreach ($weeks as $week) {?>
                                <option value="<?php echo $week->week_id ;?>"><?php echo ucwords($week->week_name);?></option>
                            <?php }?>
                        </select>
                        <?php echo form_error('week'); ?>
                        </div>
                        <div class="col-md-6">
                        <select name="report_type" class="form-control">
                            <option selected="selected" disabled="disabled" value="">--Select report Type--</option>
                            <?php foreach ($types as $type) {?>
                                <option value="<?php echo $type->report_type_id ;?>"><?php echo ucwords($type->report_type);?></option>
                            <?php }?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2"><label class="control-label"> Post date:</label></div>
                        <div class="col-md-4">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type='text' name="end_week_date" id='start_date' class="form-control input-sm"  placeholder="Post/End Week Date"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <select name="client_id" class="form-control">
                            <option selected="selected" disabled="disabled" value="">--Select Client--</option>
                            <?php foreach ($clients as $client) {?>
                                <option value="<?php echo $client->id;?>"><?php echo ucwords($client->organization);?></option>
                            <?php }?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2"><label class="control-label"> Records:</label></div>
                        <div class="col-md-4">
                           <input name="records" placeholder="Number of records" class="form-control" type="text" />
                        </div>
                         <div class="col-md-2"><label class="control-label"> Amount:</label></div>
                        <div class="col-md-4">
                           <input name="amount" placeholder="Total Amount" class="form-control" type="text" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label"><i class="fa fa-file-excel-o text-success"></i> Report file:</label>
                            </div>
                            <div class="col-md-4">
                                <input type="file" name="report_file" />
                            </div>
                            <div class="col-md-3">
                                <span class="help-block" style="padding-top: 10px; font-size: 12px; font-style: oblique;"><i class="fa fa-info-circle text-info"></i> Allowed types: .xls &amp; .xlsx</span>
                            </div>
                            <div class="col-md-3" type="hidden">
                                <a href="" id="download"><i class="fa fa-file-excel fa-2x text-success" title="Download Excel"></i></a>
                            </div>
                        </div>                          
                    </div>
		    </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button class="btn btn-success btn-sm" id="weeklyReportSave" onclick="save()"><i class="fa fa-save"></i> Save Weekly Report</button>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>