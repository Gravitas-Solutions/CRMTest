<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-bar-chart"></i> Return on Investment (ROI)</div>
                                    <div class="card-tools">
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-default btn-sm" onclick="calculator()"> <i class="fa fa-calculator text-primary"></i> ROI Calculator</button>
                              </div><!-- panel-body -->
                        </div><!-- panel panel-primary -->
                    </div>
                </div>
            </div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>

<!-- ROI modal -->
<div class="modal fade" id="roi_calculator">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-muted">ROI Calculator</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('', '', ['id' => 'roi_form', 'class' => 'form-horizontal']); ?>
                <div class="form-group">
                    <div class="col-md-3"><label class="control-label">Cars: </label></div>
                    <div class="col-md-3">
                        <input type="text" class="form-control input-sm cars" name="total_cars" value="" placeholder="Total cars" >
                    </div>
                    <div class="col-md-3"><label class="control-label">Cost/car: </label></div>
                    <div class="col-md-3">
                        <input type="text" class="form-control input-sm cars" name="cost_per_car" value="" placeholder="Cost per car" >
                    </div>
                </div>
                <div class="clearfix"><hr></div>
                <div class="form-group">
                    <div class="col-md-3"><label class="control-label">Monthly cost: </label></div>
                    <div class="col-md-3">
                        <input type="text" class="form-control input-sm" name="monthly_cost" value="" placeholder="Monthly cost" disabled="disabled" >
                    </div>
                    <div class="col-md-3"><label class="control-label">Current spend: </label></div>
                    <div class="col-md-3">
                        <input type="text" class="form-control input-sm" name="current_spend" value="" placeholder="Current spend" >
                    </div>
                </div> 
                <div class="clearfix"><hr></div>
                <div class="form-group">
                    <div class="col-md-3"><label class="control-label">Store location: </label></div>
                    <div class="col-md-9">
                        <select name="store_location" class="form-control input-sm">
                            <option value="" selected disabled>-- Select location --</option>
                            <?php foreach ($states as $s): ?>
                               <option value="<?php echo $s->state_id ?>"><?php echo $s->state_code." &raquo ".$s->state_name ?></option> 
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>  
                <button type="reset" style="display: none;"></button>             
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer">
                <h3>ROI: <span id="roi" class="text-success" style="font-weight: bolder"></span></h3>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function calculator(){
        $('button[type="reset"]').click();
        $('#roi').text('0.00');
        $('#roi_calculator').modal('show');
    }

    /*Variables*/
    var total_cars_input = $('input[name="total_cars"]');
    var cost_per_car_input = $('input[name="cost_per_car"]');
    var monthly_cost_input = $('input[name="monthly_cost"]');
    var current_spend_input = $('input[name="current_spend"]');

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
        var calculated_roi = parseFloat((current_spend - monthly_cost) * .95);

        var roi = (isNaN(calculated_roi)) ? 0 : '$'+calculated_roi.toFixed(2);
        $('#roi').text(roi);
    }

    $('.cars').keyup(function(event) {
        monthly_cost();
        calculate_roi();
    });

    current_spend_input.keyup(function(event) {
        calculate_roi();
    }); 
</script>

<?php $this->load->view('templates/includes/footer_end'); ?>