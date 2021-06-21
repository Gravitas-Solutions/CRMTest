<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
	caption, .panel-title{
		padding-bottom: 10px;
	}
	<?php if(!$filtered || $client != 'clay_cooley_dealerships') : ?>
	#table_x th:first-child, #table_x td:first-child, #process-tolls {
	  display: none;
	}
	<?php endif ?>
</style>
<div id="wrapper">
	<div id="page-wrapper-client">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-list-alt"></i> Transactions
					<span class="pull-right">
					<button data-toggle="modal" href='#date_range_modal' class="btn btn-outline btn-xs btn-info"><i class="fa fa-calendar"></i> View by Date range</button>
					<?php if ($default_user): ?> | 
					<button data-toggle="modal" href='#search_logs_modal' class="btn btn-outline btn-xs btn-primary"><i class="fa fa-search-plus"></i> Search &amp; Export Logs</button>
					<?php endif ?>
					</span>
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<form id="tolls-form" action="#" method="POST">
					<input type="hidden" name="c" value="<?php echo $client ?>">
					<table id="table_x" class="table selected table-condensed table-hover table-bordered">
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
								<td><?php echo nice_date($transaction->date_for, 'Y-m-d');?></td>
								<td><?php echo '$'.number_format($transaction->toll, 2);?></td>
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
						</tfoot>
					</table>
					<p><button id="process-tolls" class="btn btn-outline btn-xs btn-primary"><i class="fa fa-filter"></i> Charge back selected transactions</button></p>
					</form>
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
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-muted">Select department &amp; date range</h4>
			</div>
			<div class="modal-body">	
				<?php echo form_open('member/date_range_transactions'); ?> 
	           		<div class="form-group" style="padding: 10px">
		           		<div class="col-md-1"><label class="control-label">Dept:</label></div> 
				          <div class="col-md-3">
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
	           			<div class="col-md-1"><label class="control-label">From: </label></div>
	           			<div class="col-md-3">
		                    <div class='input-group date' id="transaction_start">
		                        <input type='text' name="start_date" class="form-control input-sm" placeholder="Start date" />
		                        <span class="input-group-addon">
		                            <span class="fa fa-calendar"></span>
		                        </span>
		                    </div>
		                </div>
	           			<div class="col-md-1"><label class="control-label">To: </label></div>
	           			<div class="col-md-3">
		                    <div class='input-group date' id="transaction_end">
		                        <input type='text' name="end_date" class="form-control input-sm" placeholder="End date" />
		                        <span class="input-group-addon">
		                            <span class="fa fa-calendar"></span>
		                        </span>
		                    </div>
		                </div>
	           		</div>
	           		<div class="form-group" style="padding: 10px">
	           			<div class="col-md-12 text-muted">
	           				<i class="fa fa-info-circle"></i> <em>for specific-day transactions, use the same date for both start &amp; end date fields.</em>
	           			</div>
	           		</div>
	           </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-outline btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" id="submit_btn" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-eye"></i> View transactions</button>
			</div>
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
						<table id="table_x" class="table dispaly compact table-hover" style="font-size: .9em">
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
<script src="<?php echo base_url()?>assets/js/bootstrap3-typeahead.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});

	//checboxes on date-range select
	var is_filtered = "<?php echo (bool)$filtered ?>";
	var client = "<?php echo $client; ?>";
	if(is_filtered && client == 'clay_cooley_dealerships'){
		var table = $('#table_x').DataTable({
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
		$('#table_x tbody').on('change', 'input[type="checkbox"]', function(){
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
					url: '<?php echo base_url()?>member/charge_back',
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
			alert('Select start and end dates');
			return false;
		}else{
			$('form').submit();
		}
	});
});

$(function() {
	var is_filtered = "<?php echo (bool)$filtered ?>";

	//totaling & search logs
	var dataSrc = [];
	var dt_table = $('#table_x').DataTable({
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
				text:      '<i class="fa fa-file-excel-o fa-2x"></i>',
				titleAttr: 'Export to Excel',
				exportOptions: {
					  columns: "thead th:not(.noExport)"
				  },
				footer: true
			},
			{
				extend:    'pdfHtml5',
				text:      '<i class="fa fa-file-pdf-o fa-2x"></i>',
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
			  text:      '<i class="fa fa-print fa-2x"></i>',
			  titleAttr: 'Print',
			  orientation: 'portrait', 
			  pageSize: 'A4',
			  exportOptions: {
				  columns: "thead th:not(.noExport)"
				},
			  footer: true
			}
		],
		lengthMenu: [50, 100, 150, 200],
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
		url: '<?php echo base_url()?>member/log_member_searches',
		type: 'POST',
		dataType: 'JSON',
		data: logs
	});
}

function empty_logs(){
	if (confirm('Are you sure emptying all search & export logs?')) {
		var client = $('#log_client').val();
		$.ajax({
			url: '<?php echo base_url()?>member/delete_logs/'+client,
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
</script>