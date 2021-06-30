<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
<div class="content">
	<div class="page-inner">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
							<div class="card-header">
								<div class="card-head-row">
									<div class="card-title"><h5><caption class="text-muted">
										Filtered for| <strong>Dept: </strong>
										<span id="default"><?php echo isset($last_dump_date) ? ''.$dept_name.' | <strong>Month:</strong> '. nice_date($last_dump_date, 'F (1-j), Y') : '' ?>
										<?php echo isset($start_date) ? ''.$dept_name.' | <strong>Dates:</strong> '. nice_date($start_date, 'F j, Y') : '' ?><?php echo isset($end_date) ? "<strong> to </strong>". nice_date($end_date, 'F j, Y') : '' ?></span>
										<span id="server_filtered"></span>
										</caption></h5>
									</div>
									<div class="card-tools">
	                                	<button data-toggle="modal" onclick="filter_post_month()"  class="btn btn-outline btn-xs btn-info"><i class="fa fa-download"></i> Download</button> | <button data-toggle="modal" onclick="filter_range()"  class="btn btn-outline btn-xs btn-info"><i class="fa fa-calendar"></i> View by Date range</button> | <a class="btn btn-info btn-xs" href="<?php echo base_url()?>frontend/member/disputes"><i class="fa fa-exclamation-circle"></i> View Disputes</a>
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
								<table id="basic-datatables" class="display table table-sm table-striped table-bordered table-hover table-head-bg-dark" >
									<thead>
										<tr>
										<th><abbr title="License Plate">LP</abbr></th>
										<th>State</th>
										<?php if($client == 'protech_as'){?>
			                           	<th>Cost Center</th>	
										<?php }else{?>
										 <th>Dept.</th> 
										<?php } ?>
										<th>Unit</th>
										<th>Agency</th>
										<th>Exit Date/Time</th>
										<th>Exit Lane</th>
										<th>Exit Location</th>
										<th>Amount</th>
										<th>Action</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
										<th><abbr title="License Plate">LP</abbr></th>
										<th>State</th>
										<?php if($client == 'protech_as'){?>
			                           	<th>Cost Center</th>	
										<?php }else{?>
										 <th>Dept.</th> 
										<?php } ?>
										<th>Unit</th>
										<th>Agency</th>
										<th>Exit Date/Time</th>
										<th>Exit Lane</th>
										<th>Exit Location</th>
										<th>Amount</th>				
										<th>Action</th>
										</tr>			
									</tfoot>
								</table>
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
				<h4 class="modal-title text-muted">Select department &amp; date range</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">	
				<form class="form-horizontal"  id="date_range_modal_form" method="post" >
	           		<div class="form-group">
	           			<div class="row">			        
		           			<div class="col-md-2"><label class="control-label" for="fromdate">From: </label></div>
		           			<div class="col-md-4">
			                    <div class='input-group date' id="transaction_start">
			                        <input type='text' id="start_date" name="start_date" value="<?php echo set_value('fromdate'); ?>" class="form-control input-sm" placeholder="Start date" />
			                         <div class="input-group-append">
			                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
			                        </div>
			                    </div>
			                </div>
		           			<div class="col-md-2"><label class="control-label" for="todate">To: </label></div>
		           			<div class="col-md-4">
			                    <div class='input-group date' id="transaction_end">
			                        <input type='text' id="end_date" name="end_date" value="<?php echo set_value('todate'); ?>" class="form-control input-sm" placeholder="End date" />
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
					            <select name="client_dept" id="client_dept" class="form-control input-sm">
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
										<input class="form-radio-input" type="radio" name="date_type" value="0" checked="checked">
										<span class="form-radio-sign">Transaction</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="date_type" value="1">
										<span class="form-radio-sign">posted</span>
									</label>
								</div>
							</div>
		           		</div>
		           	</div>
	           		<div class="form-group">
	           			<div class="row">
	           			<div class="col-md-12 text-muted">
	           				<i class="fa fa-info-circle"></i> <em>for specific-day transactions, use the same date for both start &amp; end date fields.</em>
	           			</div>
	           		</div>
	           		</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-outline btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" id="submit_btn" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-eye"></i> View</button>
			</div>
			</form>
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
				<form class="form-horizontal"  method="post" method="POST" id="post_transaction_form">
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
<?php $this->load->view('frontend/includes/footer_start'); ?>
<script >
$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});

	$('#submit_btn').on('click', function(){
		if ($('input[name="start_date"]').val() == '' || $('input[name="end_date"]').val() == '') {
			alert('Select start and end dates');
			return false;
		}else{

			if (Date.parse($('input[name="start_date"]').val()) > Date.parse($('input[name="end_date"]').val())) {
				alert('End date should be future of Start Date');
				return false;

			} else {
				dt_table.ajax.reload();//$('form').submit();
				setTimeout(function(){
					$('#date_range_modal').modal('hide');
				}, 1000);
				
		     	$('#default').css('display', 'none');
		     	$('#server_filtered').html($('#client_dept option:selected').text()+" | <strong>Dates:</strong> "+$('#start_date').val()+"<strong> to </strong>"+$('#end_date').val());

			}
		}
	});

		$('#post_btn').on('click', function(){
		if ($('input[name="post_month"]').val() == '') {
			alert('Select Post date Month to Download');
			return false;
		}else{
			dt_table.ajax.reload();//$('form').submit();
			setTimeout(function(){
				$('#month_transactions').modal('hide');
			}, 1000);
			
	     	$('#default').css('display', 'none');
	     	$('#server_filtered').html($('#member_dept option:selected').text()+" | <strong>Post Date Month:</strong> "+$('#post_month').val());
		}
	});

	/* For Export Buttons available inside jquery-datatable "server side processing" - Start
- due to "server side processing" jquery datatble doesn't support all data to be exported
- below function makes the datatable to export all records when "server side processing" is on */

	function newexportaction(e, dt, button, config) {
	    var self = this;
	    var oldStart = dt.settings()[0]._iDisplayStart;
	    dt.one('preXhr', function (e, s, data) {
	        // Just this once, load all data from the server...
	        data.start = 0;
	        data.length = 2147483647;
	        dt.one('preDraw', function (e, settings) {
	            // Call the original action function
	            if (button[0].className.indexOf('buttons-copy') >= 0) {
	                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
	            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
	                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
	                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
	                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
	            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
	                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
	                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
	                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
	            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
	                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
	                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
	                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
	            } else if (button[0].className.indexOf('buttons-print') >= 0) {
	                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
	            }
	            dt.one('preXhr', function (e, s, data) {
	                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
	                // Set the property to what it was before exporting.
	                settings._iDisplayStart = oldStart;
	                data.start = oldStart;
	            });
	            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
	            setTimeout(dt.ajax.reload, 0);
	            // Prevent rendering of the full data to the DOM
	            return false;
	        });
	    });
	    // Requery the server with the new one-time export settings
	    dt.ajax.reload();
	};
//For Export Buttons available inside jquery-datatable "server side processing" - End


	//$(function() {

		var recordsTotal;
		var dt_table = $('#basic-datatables').DataTable({
			'destroy': true,
			dom: 'Blfrtip',
			buttons: [
				{
					extend:    'excelHtml5',
					text:      '<i class="fa fa-file-excel"></i>',
					titleAttr: 'Export to Excel',
					exportOptions: {
						  columns: "thead th:not(.noExport)"
					  },
					footer: false,
					action: newexportaction
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
					footer: false,
					action: newexportaction
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
				  footer: false,
				  action: newexportaction
				}
			],
			lengthMenu: [10, 50, 100, 150, 200],
			pageLength: 10,
			"pagingType": "full",
			"processing": true,
	        "serverSide": true,
	        "ajax":{
		     "url": "<?php echo base_url('frontend/member/transaction_server_side') ?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":function(data) {
		     		data.client_dept = $('#client_dept').val();
                    data.member_sub_dept = $('#member_sub_dept').val();
                    data.org = '<?php echo $client ;?>';
					data.member_dept = $('#member_dept').val();
                    data.from = $('#start_date').val();
                    data.to = $('#end_date').val();
                    data.post_month = $('#post_month').val();
                    data.date_type = $('input[name="date_type"]:checked').val();
				    data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>"; },
				"dataSrc":function(data) {
				     	//console.log(data)
			           	recordsTotal = data.sum;

			           return data.data;
			         },
                   },
		    "columns": [
			          { "data": "license_plate" },
			          { "data": "state_code" },
			          { "data": "dept" },
			          { "data": "unit" },
			          { "data": "agency_name" },
			          { "data": "exit_date_time" },
			          { "data": "exit_lane" },
			          { "data": "exit_location" },
			          { "data": "toll" },
			          { "data": "action" },
			       ],
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
		        	$( api.column(amountColumnIndex).footer() ).html('$'+ pageTotal +' of $'+ recordsTotal);
		        } else{
		        	$( api.column(amountColumnIndex).footer() ).html('$'+ pageTotal +' of $'+ searchTotal);
		        }
			}
		});
});

function filter_post_month(){
    $('#date_range_modal_form')[0].reset();
    $('#month_transactions').modal('show'); 

}

function filter_range(){
    $('#post_transaction_form')[0].reset();
    $('#date_range_modal').modal('show'); 

}

function dispute_status(id, client){
		swal({
			title: 'Dispute - Transaction ID:' +id,
			text: "Give Reason for dispute",
			html: '<br><input class="form-control" placeholder="Reason For dispute" id="reason">',
			content: {
				element: "input",
				attributes: {
					placeholder: "Reason",
					type: "text",
					id: "reason",
					className: "form-control"
				},
			},
			buttons: {
				cancel: {
					visible: true,
					className: 'btn btn-danger'
				},        			
				confirm: {
					className : 'btn btn-success'
				}
			},
		}).then((Dispute) => {
			if (Dispute) {
				 $.ajax({
		            url : "<?php echo site_url('frontend/member/dispute_status')?>",
		            type: "POST",
		            data: {reason: $('#reason').val(), id: id, client: client},
		            dataType: "JSON",
		            success: function(data)
		            {
		                swal({
							title: 'Disputed!',
							text: data.msg,
							type: 'success',
							buttons : {
								confirm: {
									className : 'btn btn-success'
								}
							},
							timer: 6000
							});

		                location.reload();
		            },
		            error: function (jqXHR, textStatus, errorThrown)
		            {
		                 swal({
							title: 'Error!',
							text: data.msg,
							type: 'danger',
							buttons : {
								confirm: {
									className : 'btn btn-danger'
								}
							},
							timer: 6000,
							});
		            }
		        });
			} else {
				swal.close();
			}
		});
}


</script>
<?php $this->load->view('frontend/includes/footer_end'); ?>