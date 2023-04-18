<?php 
include 'header.php'; 
if(empty($_SESSION['PatientData'])){
	header("location:index.php");
	exit;
}
if(isset($_POST['Submit'])){
// 	require_once '../authorize.net/test.php';
	
	$id=$_SESSION['PatientData']['id'];
	$amount= $_POST['amount'];
	$ccNumber= $_POST['ccNumber'];
	$name= $_POST['name'];
	$expiryMonth= $_POST['expiryMonth'];
	$expiryYear= $_POST['expiryYear'];
	$cvvNumber= $_POST['cvvNumber'];

	$data = Array (
		'amount' => $amount,
		'ccNumber' => $ccNumber,
		'name' => $name,
		'expiryMonth' => $expiryMonth,
		'expiryYear' => $expiryYear,
		'cvvNumber' => $cvvNumber,
	);
	//echo'<pre>'; print_r($data);die;
	$result = chargeCreditCard($data);

	$data = Array (
		'credits' => $db->inc($amount),
		'updatedAt' => $db->now()
	);
	$db->where('id', $id);
	$credit = $db->update ('patients', $data);
}
?>
<div class="col-lg-12 col-md-12 col-sm-12 issue_tracker_list_main">
<div class="container">
    <div class="pay_main_cntr">
        <div class="col-lg-12 col-md-12 col-sm-12">
		  <form role="form" id="ccForm" method="post">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title paymnt_ttl">Payment Details</h3>
                </div>
                <div class="panel-body">

				 <!-- Amount -->
					<div class="form-group">
					  <label class="control-label col-sm-3">Amount<span class="text-danger">*</span></label>
					  <div class="col-md-8 col-sm-9 mrg_bttm_pay">
					  <div class="input-group">
					  <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
						<input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount" min="0" value="" data-error="Please Enter Amount" required>
						</div>
						<div class="help-block with-errors"></div>
					  </div>
					</div>
				 <!-- / Amount -->
				 <!-- CARD -->
					<div class="form-group">
					  <label class="control-label col-sm-3">CARD NUMBER<span class="text-danger">*</span></label>
					  <div class="col-md-8 col-sm-9 mrg_bttm_pay">
					  <div class="input-group">
					  <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
						<input type="text" class="form-control" name="ccNumber" id="ccNumber" placeholder="Valid Card Number" required autofocus />
						</div>
						<div class="help-block with-errors"></div>
					  </div>
					</div>
				 <!-- / CARD -->
                 <!-- Name -->
					<div class="form-group">
					  <label class="control-label col-sm-3">Full Name <span class="text-danger">*</span></label>
					  <div class="col-md-8 col-sm-9 mrg_bttm_pay">
					  <div class="input-group">
					  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" name="name" id="name" placeholder="Enter your Name here" value="" data-error="Please Enter Your Full Name" required>
						</div>
						<div class="help-block with-errors"></div>
					  </div>
					</div>
				 <!-- / Name -->
				 <!-- Cvv -->
					<div class="form-group">
					  <label class="control-label col-sm-3">CVV CODE<span class="text-danger">*</span></label>
					  <div class="col-md-8 col-sm-9 mrg_bttm_pay">
					  <div class="input-group">
					  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="password" class="form-control" id="cvvNumber" name="cvvNumber" placeholder="CVV" required />
						</div>
						<div class="help-block with-errors"></div>
					  </div>
					</div>
				 <!-- / Cvv -->
				 <!-- EXPIRY DATE -->
					<div class="form-group">
					  <label for="expiryMonth" class="control-label col-sm-3">EXPIRY DATE</label>
						<div class="col-xs-3 col-lg-3 pl-ziro">
						  <select class="form-control pay_pra" id="expiryMonth" name="expiryMonth" required>
							<option value="">Select Month</option>
								<?php
									$MonthArray = range(1, 12);
									foreach ($MonthArray as $Month) {
										echo '<option value="'.$Month.'">'.$Month.'</option>';
									}
								?>
						  </select>
						</div>
						<div class="col-xs-3 col-lg-3 pl-ziro">
						  <select class="form-control pay_pra" id="expiryYear" name="expiryYear" required>
							<option value="">Select Year</option>
								<?php
									$yearArray = range(2016, 2090);
										foreach ($yearArray as $year) {
											echo '<option value="'.$year.'">'.$year.'</option>';
										}
								?>
						  </select>
						</div>
					</div>
				<!-- / EXPIRY DATE -->
            </div>
            <ul class="nav nav-pills nav-stacked pay_prtl_ul">
                <li class="active"><a href="#"><span class="badge pull-right"><span class="glyphicon glyphicon-usd"></span><span class="amountNo">0</span></span> Invoice Total</a>
                </li>
            </ul>
            <br/>
            <!-- <a href="#" class="btn btn-success btn-lg btn-block" role="button">	</a> -->
			<div class="col-md-offset-7">
			<input name="Submit" type="submit" value="PAY" class="pay_bttn_wbprtl btn btn-success btn-lg btn-block">
			</div>
        </div>
		</form>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
$('#ccForm').bootstrapValidator({
	fields: {
		ccNumber: {
			validators: {
				creditCard: {
					message: 'The credit card number is not valid'
				}
			}
		},
		cvvNumber: {
			validators: {
				cvv: {
					creditCardField: 'ccNumber',
					message: 'The CVV number is not valid'
				}
			}
		}
	}
});

$("#amount").blur(function(){
	var amountval = $(this).val();
	$('.amountNo').text(amountval)
});

</script>
<?php include 'footer.php'; ?>