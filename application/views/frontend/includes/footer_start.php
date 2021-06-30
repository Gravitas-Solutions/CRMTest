<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		
		<footer class="footer">
			<div class="container-fluid">
				<div class="copyright ml-auto">
					2021 <a href="https://www.innovativetoll.com">Innovativetoll</a>
				</div>				
			</div>
		</footer>
	</div>


</div>

	<!--   Core JS Files   -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/moment.min.js"></script>
	<script src="<?php echo base_url() ?>assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="<?php echo base_url() ?>assets/js/core/popper.min.js"></script>
	<script src="<?php echo base_url() ?>assets/js/core/bootstrap.min.js"></script>
	 <script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.min.js"></script>
	<!-- jQuery UI -->
	<script src="<?php echo base_url() ?>assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="<?php echo base_url() ?>assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	
	<!-- jQuery Scrollbar -->
	<script src="<?php echo base_url() ?>assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
	<!-- Datatables -->
	<script src="<?php echo base_url() ?>assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="<?php echo base_url();?>assets/dataTables/js/dataTables.responsive.js"></script>

    <script src="<?php echo base_url();?>assets/dataTables/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url();?>assets/dataTables/js/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets/dataTables/js/pdfmake.min.js"></script>
    <script src="<?php echo base_url();?>assets/dataTables/js/vfs_fonts.js"></script>
    <script src="<?php echo base_url();?>assets/dataTables/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url();?>assets/dataTables/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url();?>assets/dataTables/js/buttons.colVis.min.js"></script>
    <!-- auto complete in select -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
	<!-- Sweet Alert -->
	<script src="<?php echo base_url();?>assets/js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Atlantis JS -->
	<script src="<?php echo base_url() ?>assets/js/atlantis.min.js"></script>
	<!-- Atlantis DEMO methods, don't include it in your project! -->
	<script src="<?php echo base_url() ?>assets/js/setting-demo2.js"></script>
	<script src="<?php echo base_url();?>assets/js/script.js"></script>
	<script type="text/javascript">

				  /*ROI calculator*/
				  function calculator(){
				        $('button[type="reset"]').click();
				        $('#roi').text('--');
				        $('.modal-footer, .store_location').css('display', 'none');
				        $('#roi_calculator').modal('show');
				    }

				    /*Variables*/
				    var total_cars_input = $('input[name="total_cars"]');
				    var cost_per_car_input = $('input[name="cost_per_car"]');
				    var monthly_cost_input = $('input[name="monthly_cost"]');
				    var current_spend_input = $('input[name="current_spend"]');
				    var store = $('select[name="store_location"]');

				   function monthly_cost(){
				        var total_cars = parseInt(total_cars_input.val());
				        var cost_per_car = parseFloat(cost_per_car_input.val());
				        var monthly_cost = parseFloat(total_cars * cost_per_car);

				        var calculated_cost = (isNaN(monthly_cost)) ? 0 : monthly_cost.toFixed(2);
				        monthly_cost_input.val(calculated_cost);
				    }

				    function calculate_roi(){
				        var monthly_cost = monthly_cost_input.val();
				        var current_spend = current_spend_input.val();
				        var roi_rate = 0;        
				        var store_location = store.val();
				        if (store_location == 'NY') {
				          roi_rate += 0.91;
				        }else if(store_location == 'NJ'){
				          roi_rate += 0.89;
				        } else{
				          roi_rate += 0.85;
				        }
				        var calculated_roi = parseFloat((current_spend - monthly_cost) * roi_rate);

				        var roi = (isNaN(calculated_roi)) ? 0 : '$'+calculated_roi.toFixed(2);
				        $('#roi').text(roi);
				    }

				    total_cars_input.keyup(function(event) {
				      monthly_cost();
				      calculate_roi();
				    });

				    cost_per_car_input.keyup(function(event) {
				      monthly_cost();
				      calculate_roi();
				    });

				    current_spend_input.keyup(function(event) {
				      if($(this).val() != ''){
				        $('.store_location').css('display', 'block');
				      }
				      calculate_roi();
				    });

				    store.change(function(event) {
				      $('#store').css('display', 'none');
				      $('.modal-footer').css('display', 'block');
				      calculate_roi();
				    });

				    setInterval(function(){$('#store').css({'font-size': '.8em'}).toggleClass('help-block text-info');}, 2000);
				    
				</script>