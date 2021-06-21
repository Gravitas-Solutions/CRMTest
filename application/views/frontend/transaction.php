<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
<style type="text/css">
	caption, .panel-title{
		padding-bottom: 10px;
	}
	<?php if(!$filtered || $client != 'clay_cooley_dealerships') : ?>
	#transaction-datatables th:first-child, #transaction-datatables td:first-child, #process-tolls {
	  display: none;
	}
	<?php endif ?>
</style>
<div class="content">
	<div class="page-inner">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
							<div class="card-header">
								<div class="card-head-row">
								<div class="card-title"><h5><caption class="text-muted">
									Filtered for: <strong>Dept: </strong><?php echo $dept_name; ?>
									<?php echo isset($last_dump_date) ? ' | <strong> Month:</strong> '. nice_date($last_dump_date, 'F (1-j), Y') : '' ?>
									<?php echo isset($post_month) ? ' | <strong> Post Month:</strong> '. nice_date($post_month, 'F , Y') : '' ?>
									<?php echo isset($start_date) ? ' | <strong> '.$date_type.'Dates:</strong> '. nice_date($start_date, 'F j, Y') : '' ?><?php echo isset($end_date) ? "<strong> to </strong>". nice_date($end_date, 'F j, Y') : '' ?>
									</caption></h5></div>
									<div class="card-tools">
										<button data-toggle="modal" onclick="filter_post_month()" class="btn btn-outline btn-xs btn-info"><i class="fa fa-download"></i> Download</button> | <button data-toggle="modal" onclick="filter_range()" class="btn btn-outline btn-xs btn-info"><i class="fa fa-calendar"></i> View by Date range</button> |
	                                	<a class="btn btn-info btn-xs" href="<?php echo base_url()?>frontend/member/disputes"><i class="fa fa-exclamation-circle"></i> View Disputes</a>
	                                	<?php if ($default_user): ?> | 
										<button data-toggle="modal" href='#search_logs_modal' class="btn btn-outline btn-xs btn-primary"><i class="fa fa-search-plus"></i> Logs Search</button>
										<?php endif ?>
						            </div>
	                        	</div>
							</div>
					</div>
				</div>					
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
								<div class="table-responsive">
								<form id="tolls-form" action="#" method="POST">
								<input type="hidden" name="c" value="<?php echo $client ?>">
								<table id="transaction-datatables" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
									<caption class="text-muted">
										Filtered for: <strong>Dept: </strong><?php echo $dept_name; ?>
									<?php echo isset($last_dump_date) ? ' | <strong>Month:</strong> '. nice_date($last_dump_date, 'F (1-j), Y') : '' ?>
									<?php echo isset($start_date) ? ' | <strong>Dates:</strong> '. nice_date($start_date, 'F j, Y') : '' ?><?php echo isset($end_date) ? "<strong> to </strong>". nice_date($end_date, 'F j, Y') : '' ?>
									</caption>
									<thead  style="background-color: #EBEBEB;">
										<th><input name="select_all" value="1" id="select-all" type="checkbox" /></th>
										<th><abbr title="License Plate">LP</abbr></th>
										<?php if(isset($org)){?>
				                        <th>DNT</th>
				                        <th>VIN</th>
				                        <?php }?>
										<th>State</th>
										<th>Agency</th>
										<th>Exit Date/Time</th>
										<th>Exit Lane</th>
										<th>Exit Location</th>
										<th>Amount</th>
										<th>Action</th>
									</thead>
									<tbody>
										<?php $total = 0; foreach (array_unique($transactions, SORT_REGULAR) as $transaction) {
										/*<?php $total = 0; foreach ($transactions as $transaction) {*/
											$total += number_format($transaction->toll, 2); ?>
										<tr style="color: <?php echo ($client == 'clay_cooley_dealerships' && ($client == 'clay_cooley_dealerships' && $transaction->processed)) ? "#c0c0c0" : "" ?>"
											<?php echo ($client == 'clay_cooley_dealerships' && $transaction->processed) ? "data-toggle='tooltip' title='Processed' data-placement='top'" : "" ?>
										>
										<?php if(isset($transaction->exit_name)){?>
											<td><input type="checkbox" name="id[]" value="<?php echo $filtered ? $transaction->invoice_id : '' ?>" <?php echo ($client == 'clay_cooley_dealerships' && $transaction->processed) ? "disabled checked title='Already charged back'" : "" ?>></td>
											<td><?php echo $transaction->license_plate;?></td>
											<td><?php echo $transaction->state_code;?></td>
											<td><?php echo ucwords($transaction->agency_name);?></td>
											<td><?php echo nice_date($transaction->exit_date_time, 'Y-m-d H:i');?></td>
											<td><center> - </center></td>
											<td><?php echo ucwords($transaction->exit_name);?></td>
											<td><?php echo '$'.number_format($transaction->toll, 2);?></td>
											<td><button type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Dispute transaction" onclick="dispute_status(<?php echo $transaction->invoice_id;?>, '<?php echo $client;?>')"><i class="fa fa-exclamation-circle"></i></button></td>
										<?php }else{?>
											<td><input type="checkbox" name="id[]" value="<?php echo $filtered ? $transaction->invoice_id : '' ?>" <?php echo ($client == 'clay_cooley_dealerships' && $transaction->processed) ? "disabled checked title='Already charged back'" : "" ?>></td>
											<td><?php echo $transaction->license_plate;?></td>
											<?php if(isset($org)){?>
					                        <td><?php echo $transaction->tolltag;?></td>
					                        <td><?php echo ($transaction->vin_no) ? ucwords($transaction->vin_no) : '<center>-</center>';?></td>
					                        <?php }?>
											<td><?php echo $transaction->state_code;?></td>
											<td><?php echo ucwords($transaction->agency_name);?></td>
											<td><?php echo nice_date($transaction->exit_date_time, 'Y-m-d H:i');?></td>
											<td><?php echo ucwords($transaction->exit_lane);?></td>
											<td><?php echo ucwords($transaction->exit_location);?></td>
											<td><?php echo '$'.number_format($transaction->toll, 2);?></td>
											<td><button type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Dispute transaction" onclick="dispute_status(<?php echo $transaction->invoice_id;?>, '<?php echo $client;?>')"><i class="fa fa-exclamation-circle"></i></button></td>
										<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<th>&nbsp;</th>
										<th><abbr title="License Plate">LP</abbr></th>
										<?php if(isset($org)){?>
				                        <th>DNT</th>
				                        <th>VIN</th>
				                        <?php }?>
										<th>State</th>
										<th>Agency</th>
										<th>Exit Date/Time</th>
										<th>Exit Lane</th>
										<th>Exit Location</th>
										<th>Amount</th>				
										<th>Action</th>				
									</tfoot>
								</table>
								<p><button id="process-tolls" class="btn btn-outline btn-xs btn-primary"><i class="fa fa-filter"></i> Charge back selected transactions</button></p>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Date range modal selector -->
<div class="modal fade" id="date_range_modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Filter Transactions</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
        		</button>
			</div>
			<div class="modal-body">
				<?php echo form_open('frontend/member/date_range_transactions', ['class' => 'form-horizontal', 'id' => 'date_range_modal_form', 'method' => 'POST']);?>
	           		<div class="form-group" style="padding: 10px">
	           			<div class="row">			        
	           			<div class="col-md-2"><label class="control-label">From: </label></div>
	           			<div class="col-md-4">
		                    <div class='input-group date' id="transaction_start">
		                        <input type='text' name="start_date" class="form-control input-sm" placeholder="Start date" />
		                         <div class="input-group-append">
		                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
		                        </div>
		                    </div>
		                </div>
	           			<div class="col-md-2"><label class="control-label">To: </label></div>
	           			<div class="col-md-4">
		                    <div class='input-group date' id="transaction_end">
		                        <input type='text' name="end_date" class="form-control input-sm" placeholder="End date" />
		                        <div class="input-group-append">
		                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
		                        </div>
		                    </div>
		                </div>
		                </div>
	           		</div>
	           		<div class="form-group">
	           			<div class="row">
	           			<div class="col-md-2"><label class="control-label">Department:</label></div>
				          <div class="col-md-4">
				            <select name="member_dept" class="form-control input-sm">
				              <?php if ($default_user) { ?>
				                <option value="0" <?php if($client_dept == 0){echo 'selected';}?>>All departments</option>
				              <?php } ?>
				              <?php foreach ($departments as $dept) {?>
				                 <?php if (strpos($dept->dept_name, 'overview') !== false) {
				                  continue;
				                }?>
				                <option value="<?php echo $dept->dept_id?>" <?php if($client_dept == $dept->dept_id){echo 'selected';}?>><?php echo $dept->dept_name;?></option>
				              <?php } ?>
				            </select>
				          </div>
					          <div class="col-md-2"><label class="control-label">Date Tpye:</label></div>
					          <div class="col-md-4">
					          	<div class="form-check">
									<label class="form-radio-label">
										<input class="form-radio-input" type="radio"  name="date_type" value="0" checked="checked">
										<span class="form-radio-sign">Transaction</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio"  name="date_type" value="1">
										<span class="form-radio-sign">posted</span>
									</label>
								</div>
							</div>
		           		</div>
		           	</div>
	           		<div class="form-group" style="padding: 10px">
	           			<div class="row">
	           			<div class="col-md-12 text-muted">
	           				<i class="fa fa-info-circle"></i> <em>for specific-day transactions, use the same date for both start &amp; end date fields.</em>
	           			</div>
	           			</div>
	           		</div>
	           </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-outline btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" id="submit_btn" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-eye"></i> View</button>
			</div>
		</div>
	</div>
</div>

<!-- Download Modal -->
<div class="modal fade" id="month_transactions">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Select Post Date Month</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
			<?php echo form_open('frontend/member/date_post_transactions', ['class' => 'form-horizontal', 'id' => 'post_transaction_form', 'method' => 'POST']);?>

			 		<div class="form-group">
			 			<input type="hidden" value="<?php echo $client;?>" name="client_name"/>
			 			<input type="hidden" value="<?php echo $client_dept;?>" name="member_dept"/>
						<div class="input-group date mb-3" id="filter_month">
							<div class="input-group-prepend">
								<span class="input-group-text">Post Month</span>
							</div>
							<input type="text" name="post_month" class="form-control" id="post_month" value="<?php echo set_value('post_month'); ?>" placeholder="yyyy-mm"/>
							<div class="input-group-append">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
                </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-outline btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" id="post_btn" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-eye"></i> Filter</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- Member search & export logs modal -->
<div class="modal fade" id="search_logs_modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Search & Export Logs</h4>
			</div>
			<div class="modal-body">
				<?php if (isset($search_logs) && !empty($search_logs)): ?>
					<div class="table-responsive">
						<input type="hidden" name="log_client" id="log_client" value="<?php echo $client_id ?>">
						<table id="transaction-datatables" class="table dispaly compact table-hover" style="font-size: .9em">
							<thead>
								<tr>
									<th>N<sup>o</sup></th>
									<th>User</th>
									<th>Activity</th>
									<th>Search phrase</th>
									<th>Log Datetime</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 0; foreach ($search_logs as $log) : ?>
								<tr id="">
									<td><?php echo ++$i ?></td>
									<td><?php echo ucwords($log->first_name.' '.$log->last_name) ?></td>
									<td><?php echo in_array($log->search_type, ['LP', 'VIN', 'DNT']) ? $log->search_type.' search' : $log->search_type ?></td>
									<td><?php echo in_array($log->search_phrase, ['PDF', 'Excel', 'Print']) ? "--" : $log->search_phrase ?></td>
									<td><?php echo nice_date($log->log_datetime, 'l, F j, Y @ H:i:s') ?></td>
								</tr>
							<?php endforeach ?>
							</tbody>
						</table>
					</div>
				<?php else : ?>
					<p class="text-warning text-center"><i class="fa fa-exclamation-triangle"></i> No search & export logs yet!</p>
				<?php endif ?>
			</div>
			<div class="modal-footer">
				<?php if(count($search_logs) > 0) : ?>
					<button type="button" class="btn btn-danger btn-xs" onclick="empty_logs()"><i class="fa fa-trash"></i> Delete All Logs</button>
				<?php endif ?>
				<button type="button" class="btn btn-success btn-xs" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('frontend/includes/footer_start'); ?>
<script src="<?php echo base_url()?>assets/js/bootstrap3-typeahead.js"></script>
<script >
	$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});

	//checboxes on date-range select
	var is_filtered = "<?php echo (bool)$filtered ?>";
	var client = "<?php echo $client; ?>";
	if(is_filtered && client == 'clay_cooley_dealerships'){
		var table = $('#transaction-datatables').DataTable({
			'destroy': true,
			'columnDefs': [{
		        'targets': [0],
		        'searchable':false,
		        'orderable':false,
		    }],
		    'order': [[1, 'asc']],
		});

		// Handle click on "Select all" control
		$('#select-all').on('click', function(){
		  // Check/uncheck all checkboxes in the table
		  var rows = table.rows({ 'search': 'applied' }).nodes();
		  $('input[type="checkbox"]:not(:disabled)', rows).prop('checked', this.checked);
		});

		 // Handle click on checkbox to set state of "Select all" control
		$('#transaction-datatables tbody').on('change', 'input[type="checkbox"]', function(){
		  // If checkbox is not checked
		  if(!this.checked){
		     var el = $('#select-all').get(0);
		     // If "Select all" control is checked and has 'indeterminate' property
		     if(el && el.checked && ('indeterminate' in el)){
		        // Set visual state of "Select all" control 
		        // as 'indeterminate'
		        el.indeterminate = true;
		     }
		  }
		});

		$('#process-tolls').on('click', function(e){
			e.preventDefault();
			if (confirm('Process all selected transactions... (action is irreversible) ?')) {
				$.ajax({
					url: '<?php echo base_url()?>frontend/member/charge_back',
					type: 'POST',
					data: $('#tolls-form').serialize(),//table.$('input[type="checkbox"]').serializeArray(),
					dataType: 'JSON',
					success: function(data){
						if(data.status){
							alert(data.msg);
							location.reload();
						}else{
							alert(data.msg);
						}
					}
				});
			}
		});
	}

	$('#submit_btn').on('click', function(){
		if ($('input[name="start_date"]').val() == '' || $('input[name="end_date"]').val() == '') {
			alert('Select start, end dates and specify type of search');
			return false;
		}else{
			if (Date.parse($('input[name="start_date"]').val()) > Date.parse($('input[name="end_date"]').val())) {
				alert('End date should be future of Start Date');
				return false;

			} else {
				$('#date_range_modal_form').submit();

			}
		}
	});

		$('#post_btn').on('click', function(){
		if ($('input[name="post_month"]').val() == '') {
			alert('Select Post Month to filter');
			return false;
		}else{
			$('#post_transaction_form').submit();
		}
	});
});

$(function() {
	var is_filtered = "<?php echo (bool)$filtered ?>";

	//totaling & search logs
	var dataSrc = [];
	var dt_table = $('#transaction-datatables').DataTable({
		'destroy': true,
	    'initComplete': function(){
			var api = this.api();
			var search_cols = is_filtered ? [1, 2, 3] : [0, 1, 2];
			api.cells('tr', search_cols).every(function(){
			var data = $('<div>').html(this.data()).text();           
			if(dataSrc.indexOf(data) === -1){ dataSrc.push(data); }
			});

			// Sort dataset alphabetically
			dataSrc.sort();

			// Initialize Typeahead plug-in
			$('.dataTables_filter input', api.table().container())
			.typeahead({
			   source: dataSrc,
			   afterSelect: function(value){
				api.search(value).draw();
				api.cells(function(idx, data, node){
					if (data == value) {
						var search_type = (idx.column == (is_filtered ? 1 : 0)) ? 'LP' : (idx.column == (is_filtered ? 2 : 1)) ? 'DNT' : (idx.column == (is_filtered ? 3 : 2)) ? 'VIN' : '';

						var currentdate = new Date();
						var datetime = currentdate.getFullYear()+'-'
										+ (currentdate.getMonth()+1)  + "-"
						                + currentdate.getDate() + " "  
						                + currentdate.getHours() + ":"  
						                + currentdate.getMinutes() + ":" 
						                + currentdate.getSeconds();
						var logs = {search_type: search_type, search_phrase: value, log_datetime: datetime}
						log_activity(logs);		
					}
			    });
			   }
			});
		},
		dom: 'Blfrtip',
		buttons: [
			{
				extend:    'excelHtml5',
				text:      '<i class="fa fa-file-excel"></i>',
				titleAttr: 'Export to Excel',
				exportOptions: {
					  columns: "thead th:not(.noExport)"
				  },
				footer: true
			},
			{
				extend:    'pdfHtml5',
				text:      '<i class="fa fa-file-pdf"></i>',
				titleAttr: 'Export to PDF',
				orientation: 'portrait', 
				pageSize: 'A4',
				exportOptions: {
					columns: "thead th:not(.noExport)"
				  },
				footer: true
			},
			{
			  extend:    'print', 
			  text:      '<i class="fa fa-print"></i>',
			  titleAttr: 'Print',
			  orientation: 'portrait', 
			  pageSize: 'A4',
			  exportOptions: {
				  columns: "thead th:not(.noExport)"
				},
			  footer: true
			}
		],
		lengthMenu: [10, 50, 100, 150, 200],
		pageLength: 10,
		"pagingType": "full",
		footerCallback: function ( row, data, start, end, display ) {
			var api = this.api(), data;
 
			// Remove the '$' formatting to get integer data for summation
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};
 
			var amountColumnIndex = $('th').filter(function(i){return $(this).text() == 'Amount';}).first().index();
	        var totalData= api.column(amountColumnIndex).data();
	        var total = totalData.reduce(function(a ,b){ return intVal(a) + intVal(b); }, 0) .toFixed(2);
	        var pageTotalData = api.column(amountColumnIndex, {page: 'current'}).data();
	        var pageTotal = pageTotalData.reduce(function(a,b){return intVal(a) + intVal(b);}, 0).toFixed(2);
	        var searchTotalData = api.column(amountColumnIndex, {'filter': 'applied'}).data();
	        var searchTotal = searchTotalData.reduce(function(a,b){return intVal(a) + intVal(b);}, 0).toFixed(2);
	        if (searchTotalData.length == totalData.length) {
	        	$( api.column(amountColumnIndex).footer() ).html('$'+ pageTotal +' of $'+ total);
	        } else{
	        	$( api.column(amountColumnIndex).footer() ).html('$'+ pageTotal +' of $'+ searchTotal);
	        }
		},
		language: {
			search: 'Search:',
	    	searchPlaceholder: 'Enter search term...'
	    }
	});
	$('.dataTables_filter .form-control').css('height', '28px');

	/*Log export activities*/
	$('button.dt-button').on('click', function(e){
		e.preventDefault();
		var btns = $(this).attr('class');			
		var currentdate = new Date();
		var datetime = currentdate.getFullYear()+'-'
						+ (currentdate.getMonth()+1)  + "-"
		                + currentdate.getDate() + " "  
		                + currentdate.getHours() + ":"  
		                + currentdate.getMinutes() + ":" 
		                + currentdate.getSeconds();
		var search_phrase = (btns.indexOf('print') !== -1) ? 'Print' : (btns.indexOf('pdf') !== -1) ? 'PDF' : (btns.indexOf('excel') !== -1) ? 'Excel' : ''; 
		var logs = {search_type: search_phrase+' Export', search_phrase: search_phrase, log_datetime: datetime}
		log_activity(logs);			
	});
});


function log_activity(logs){
	$.ajax({
		url: '<?php echo base_url()?>frontend/member/log_member_searches',
		type: 'POST',
		dataType: 'JSON',
		data: logs
	});
}

function empty_logs(){
	if (confirm('Are you sure emptying all search & export logs?')) {
		var client = $('#log_client').val();
		$.ajax({
			url: '<?php echo base_url()?>frontend/member/delete_logs/'+client,
			type: 'GET',
			dataType: 'JSON',
			success: function(data){
				if (data.status) {
					alert(data.msg);
					location.reload();
				} else{
					alert(data.msg);
				}
			}
		});
	}
}

function filter_post_month(){
    $('#date_range_modal_form')[0].reset();
    $('#month_transactions').modal('show'); 

}

function filter_range(){
    $('#post_transaction_form')[0].reset();
    $('#date_range_modal').modal('show'); 

}

function dispute_status(id, client){
	var r=confirm("Are you sure you want dispute the transaction?")
	 if (r==true)
        $.ajax({
            url : "<?php echo site_url('frontend/member/dispute_status')?>/"+id+"/"+client,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                alert('Changed dispute status successfully');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error updating status');
            }
        });
        else
          return false;
}
</script>
<?php $this->load->view('frontend/includes/footer_end'); ?>