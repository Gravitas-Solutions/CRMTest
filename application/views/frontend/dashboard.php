<?php $this->load->view('frontend/includes/header_start'); ?>
<style type="text/css">
	dt {
	    font-weight: 500;
	}
	th small{
		font-size: .725rem;
		font-weight: bold;
	}
	.table td, .table th {
	    height: 40px !important;
	}
</style>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
	<div class="content" data-spy="scroll" data-target="#chart-legends" data-offset="50">
		<div class="page-inner">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="card-head-row">
								<div class="card-title"><i class="far fa-chart-bar"></i> Dashboard</div>
								 <div class="card-tools">
								 	<form action="<?php echo base_url('home') ?>" method="POST" id="auto_submit_form">
								 		<div class="form-group">
								 			<input type="hidden" value="<?php echo $client;?>" name="client_name"/>
								 			<input type="hidden" value="<?php echo $client_dept;?>" name="member_dept"/>
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fa fa-calendar"></i></span>
												</div>
												<input type="text" name="filter_month" class="form-control" id="filter_month" value="<?php echo $month;?>" placeholder="yyyy-mm"  onchange="this.form.submit()">
												<div class="input-group-append">
													<button class="btn btn-default btn-xs">Filter</button>
												</div>
											</div>
										</div>
				                    </form>
                            	</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-12">
									<div class="card shadow-none border-dark border-left border-bottom border-right">
										<div class="card-header bg-dark text-white font-weight-bold text-uppercase">
											<h5>MTD Summary <?php echo nice_date($month, 'F Y') ?></h5>
										</div>
										<div class="card-body">
											<h5 class="font-weight-bold">Toll transactions</h5>
											<dl class="row">
											<?php if($client == 'amazon'){ ?>
											  <dt class="col-sm-8">MTD Toll Spend </dt>
											  <dd class="col-sm-4 text-right"><?php echo '$'.number_format($amazon_total, 2);?></dd>
											  <dt class="col-sm-8">Power Unit </dt>
											  <dd class="col-sm-4 text-right"><?php echo '$'.number_format($power_unit, 2);?></dd>
											  <dt class="col-sm-8">Trailers </dt>
											  <dd class="col-sm-4 text-right"><?php echo '$'.number_format($trailer, 2);?></dd>
											  <dt class="col-sm-8">AFP </dt>
											  <dd class="col-sm-4 text-right"><?php echo '$'.number_format($afp, 2);?></dd>
											  <?php } else{?>
											  	<dt class="col-sm-8">Account Balance <small class="text-muted">[<?php echo str_replace('_', ' ', $organization);?> <?php echo (isset($group_name)) ? $group_name : '';?>]</small></dt>
											  <dd class="col-sm-4 text-right"><?php echo '$-'.number_format($toll_transactions, 2);?></dd>

											   <?php } ?>

											  <dt class="col-sm-8">Vehicle Count</dt>
											  <dd class="col-sm-4 text-right"><?php echo number_format($active_vehicles+$inactive_vehicles, 0);?></dd>

											  <dt class="col-sm-8">Total Transactions</dt>
											  <dd class="col-sm-4 text-right"><?php echo number_format($total_transactions);?></dd>

											  <dt class="col-sm-8">Disputed Transactions</dt>
											  <dd class="col-sm-4 text-right">0</dd>

											  <dt class="col-sm-8">Total Savings (Fees Waived)</dt>
											  <dd class="col-sm-4 text-right"><?php echo '$'.number_format($saving, 2);?></dd>
											</dl>
											<h4 class="font-weight-bold">Citations</h4>
											<dl class="row">
											  <dt class="col-sm-8">Red light</dt>
											  <dd class="col-sm-4">
											  	<?php echo ($red_light_no == 0) ? '0' : number_format($red_light_no); ?>
											  	<span class="float-right pl-5"><?php echo ($red_light_amount == 0.00) ? '$0.00' : '$'.number_format($red_light_amount, 2); ?></span>
											  </dd>

											  <dt class="col-sm-8">Speed ticket</dt>
											  <dd class="col-sm-4">
											  	<?php echo ($speeding_ticket_no == 0) ? '0' : number_format($speeding_ticket_no); ?>
											  	<span class="float-right pl-5"><?php echo ($speeding_ticket_amount == 0.00) ? '$0.00' : '$'.number_format($speeding_ticket_amount, 2); ?></span></dd>

											  <dt class="col-sm-8">Parking</dt>
											  <dd class="col-sm-4">
											  	<?php echo ($parking_no == 0) ? '0' : number_format($parking_no); ?>
											  	<span class="float-right pl-5"><?php echo ($parking_amount == 0.00) ? '$0.00' : '$'.number_format($parking_amount, 2); ?></span></dd>
											</dl>
										</div>
									</div>
								</div>
								<div class="col-lg-8 col-md-8 col-sm-12">
									<div class="card full-height shadow-none border-dark border-left border-bottom border-right">
										<div class="card-header bg-dark text-white font-weight-bold text-uppercase">
											<h5>Daily Toll Spending <?php echo nice_date($month, 'F Y') ?></h5>
										</div>
										<div class="card-body">
											<div class="chart-container">
												<canvas id="lineChart"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-12 col-sm-12">
									<div class="card shadow-none border-dark border-left border-bottom border-right">
										<div class="card-header bg-dark text-white font-weight-bold text-uppercase">
											<h5><?php echo nice_date($month, 'F Y') ?> MTD Toll Spending </h5>
										</div>
										<div class="card-body">
											<div class="chart-container">
												<canvas id="doughnutChart" ></canvas>
											</div>
											<!-- <div id="chart-legends"></div> -->
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12">
									<div class="card shadow-none border-dark border-left border-bottom border-right">
										<div class="card-header bg-dark text-white font-weight-bold text-uppercase">
											<h5><?php echo nice_date($month, 'Y') ?> YTD Toll Spending</h5>
										</div>
										<div class="card-body">
											<div class="chart-container">
												<canvas id="barChart"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="card full-height shadow-none border-dark border-left border-bottom border-right">
										<div class="card-header bg-dark text-white font-weight-bold text-uppercase">
											<h5><?php echo nice_date($month, 'F Y') ?> MTD Top Vehicles</h5>
										</div>
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-condensed table-hover table-sm table-borderless">
													<thead>
														<th><small>#</small></th>
														<th><small>LP</small></th>
														<th><small>STATE</small></th>
														<th><small>TAG TYPE</small></th>
														<th><small>AXLES</small></th>
														<th class="text-right"><small>AMOUNT</small></th>
													</thead>
													<tbody>
														<?php $i = 0; foreach ($top_vehicles as $tv) {?>
															<tr>
																<td><?php echo ++$i;?></td>
																<td><?php echo $tv->license_plate;?></td>
																<td><?php echo $tv->state_code;?></td>
																<td><?php echo (in_array($organization, ['pike', 'shermco', 'amazon', 'protech'])) ? 'Cab':'Dealer';?> Tag</td>
																<td><?php echo (isset($tv->axles)) ? ucwords($tv->axles) : '-';?></td>
																<td class="text-right"><?php echo '$'.number_format($tv->amount, 2);?></td>
															</tr>
														<?php }?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $this->load->view('frontend/includes/footer_start'); ?>

<script src="<?php echo base_url() ?>assets/js/plugin/chart.js/chart.min.js"></script>
<script >
$(document).ready(function() {
	var lineChart = document.getElementById('lineChart').getContext('2d'),
	barChart = document.getElementById('barChart').getContext('2d'),
	doughnutChart = document.getElementById('doughnutChart').getContext('2d');
	var myLineChart = new Chart(lineChart, {
		type: 'line',
		data: {
			labels: [<?php echo $daily_labels ?>],
			datasets: [{
				label: "DTD Toll",
				borderColor: "#1d7af3",
				pointBorderColor: "#FFF",
				pointBackgroundColor: "#1d7af3",
				pointBorderWidth: 2,
				pointHoverRadius: 4,
				pointHoverBorderWidth: 1,
				pointRadius: 4,
				backgroundColor: 'transparent',
				fill: true,
				borderWidth: 2,
				data: [<?php echo $daily_tolls ?>]
			}]
		},
		options : {
			responsive: true, 
			maintainAspectRatio: false,
			legend: {
				position: 'bottom',
				labels : {
					padding: 10,
					fontColor: '#1d7af3',
				}
			},
			tooltips: {
				bodySpacing: 4,
				mode:"nearest",
				intersect: 0,
				position:"nearest",
				xPadding:10,
				yPadding:10,
				caretPadding:10
			},
			layout:{
				padding:{left:15,right:15,top:15,bottom:15}
			}
		}
	});

	var myBarChart = new Chart(barChart, {
		type: 'bar',
		data: {
			labels: [<?php echo $monthly_labels ?>],
			datasets : [{
				label: "YTD Toll",
				backgroundColor: 'rgb(23, 125, 255)',
				borderColor: 'rgb(23, 125, 255)',
				data: [<?php echo $monthly_tolls ?>],
			}],
		},
		options: {
			responsive: true, 
			maintainAspectRatio: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero:true
					}
				}]
			},
		}
	});


	var myDoughnutChart = new Chart(doughnutChart, {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [<?php echo $agency_tolls ?>],
					backgroundColor: [<?php echo $agency_colors ?>]
				}],
				labels: [<?php echo $agency_labels ?>]
			},
			options: {
		        responsive: true, 
				maintainAspectRatio: false,
				legend : {
					position: 'right',
					labels: {
		                fontSize: 10,
		                boxWidth: 30
		            }
				},
				layout: {
					padding: {
						left: 20,
						right: 20,
						top: 20,
						bottom: 20
					}
				},
		       /* legendCallback: function(chart) {
		            var legendHtml = [];
		            legendHtml.push('<ul>');
		            var item = chart.data.datasets[0];
		            for (var i=0; i < item.data.length; i++) {
		                legendHtml.push('<li>');
		                legendHtml.push('<span class="chart-legend" style="background-color:' + item.backgroundColor[i] +'"></span>');
		                legendHtml.push('<span class="chart-legend-label-text"><strong>$' + item.data[i] + '</strong>: '+chart.data.labels[i]+'</span>');
		                legendHtml.push('</li>');
		            }

		            legendHtml.push('</ul>');
		            return legendHtml.join("");
		        },*/
		        tooltips: {
		             enabled: true,
		             mode: 'label',
		             callbacks: {
		                label: function(tooltipItem, data) {
		                    var indice = tooltipItem.index;
		                    return " $"+data.datasets[0].data[indice];
		                }
		             }
		        }
			}
		});
	if(document.getElementById('chart-legends') != null){
    document.getElementById('chart-legends').innerHTML = myDoughnutChart.generateLegend();
	}

});
</script>
<?php $this->load->view('frontend/includes/footer_end'); ?>