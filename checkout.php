<?php include 'header.php';?>
<div class="col-lg-12 col-md-12 col-sm-12 issue_tracker_list_main payMent_main_alltop">
<div class="container">
    <div class="pay_main_cntr">
        <div class="col-lg-12 col-md-12 col-sm-12 ">
		  <form role="form" id="ccForm" method="post" novalidate="novalidate" class="bv-form" _lpchecked="1"><button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;" disabled="disabled"></button>
            <div class="panel panel-default ">
                <div class="panel-heading pAYMENT_hding">
                    <h3 class="panel-title paymnt_ttl"><i class="fa fa-check" aria-hidden="true"></i> Payment Information</h3>
                </div>
				
                <div class="panel-body payment_body_pdd">
				 <!-- Amount -->
					<div class="form-group">
					  <label class="control-label col-sm-3">Amount<span class="text-danger">*</span></label>
					  <div class="col-md-8 col-sm-9 mrg_bttm_pay">
					  <div class="input-group">
					  <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
						<input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount" min="0" value="" data-error="Please Enter Amount" required="" data-bv-field="amount">
						</div>
						<div class="help-block with-errors"></div>
					  <small class="help-block" data-bv-validator="greaterThan" data-bv-for="amount" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value greater than or equal to %s</small><small class="help-block" data-bv-validator="integer" data-bv-for="amount" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a valid number</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="amount" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small></div>
					</div>
				 <!-- / Amount -->
				 <!-- CARD -->
					<div class="form-group">
					  <label class="control-label col-sm-3">CARD NUMBER<span class="text-danger">*</span></label>
					  <div class="col-md-8 col-sm-9 mrg_bttm_pay">
					  <div class="input-group">
					  <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
						<input type="text" class="form-control" name="ccNumber" id="ccNumber" placeholder="Valid Card Number" required="" autofocus="" data-bv-field="ccNumber" autocomplete="off">
						</div>
						<div class="help-block with-errors"></div>
					  <small class="help-block" data-bv-validator="creditCard" data-bv-for="ccNumber" data-bv-result="NOT_VALIDATED" style="display: none;">The credit card number is not valid</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="ccNumber" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small></div>
					</div>
				 <!-- / CARD -->
                 <!-- Name -->
					<div class="form-group has-success">
					  <label class="control-label col-sm-3">Full Name <span class="text-danger">*</span></label>
					  <div class="col-md-8 col-sm-9 mrg_bttm_pay">
					  <div class="input-group">
					  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" name="name" id="name" placeholder="Enter your Name here" value="" data-error="Please Enter Your Full Name" required="" data-bv-field="name" autocomplete="off">
						</div>
						<div class="help-block with-errors"></div>
					  <small class="help-block" data-bv-validator="notEmpty" data-bv-for="name" data-bv-result="VALID" style="display: none;">Please enter a value</small></div>
					</div>
				 <!-- / Name -->
				 <!-- Cvv -->
					<div class="form-group has-error">
					  <label class="control-label col-sm-3">CVV CODE<span class="text-danger">*</span></label>
					  <div class="col-md-8 col-sm-9 mrg_bttm_pay">
					  <div class="input-group">
					  <span class="input-group-addon">***</span>
						<input type="password" class="form-control" id="cvvNumber" name="cvvNumber" placeholder="CVV" required="" data-bv-field="cvvNumber" autocomplete="off">
						</div>
						<div class="help-block with-errors"></div>
					  <small class="help-block" data-bv-validator="cvv" data-bv-for="cvvNumber" data-bv-result="INVALID" style="">The CVV number is not valid</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="cvvNumber" data-bv-result="VALID" style="display: none;">Please enter a value</small></div>
					</div>
				 <!-- / Cvv -->
				 <!-- EXPIRY DATE -->
					<div class="form-group">
					  <label for="expiryMonth" class="control-label col-sm-3 col-xs-12">EXPIRY DATE</label>
						<div class="col-xs-6 col-sm-4 col-lg-4 pl-ziro">
						  <select class="form-control pay_pra" id="expiryMonth" name="expiryMonth" required="" data-bv-field="expiryMonth">
							<option value="">Select Month</option>
								<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>						  </select>
						<small class="help-block" data-bv-validator="notEmpty" data-bv-for="expiryMonth" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small></div>
						<div class="col-xs-6 col-sm-4 col-lg-4 pl-ziro">
						  <select class="form-control pay_pra" id="expiryYear" name="expiryYear" required="" data-bv-field="expiryYear">
							<option value="">Select Year</option>
								<option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option><option value="2032">2032</option><option value="2033">2033</option><option value="2034">2034</option><option value="2035">2035</option><option value="2036">2036</option><option value="2037">2037</option><option value="2038">2038</option><option value="2039">2039</option><option value="2040">2040</option><option value="2041">2041</option><option value="2042">2042</option><option value="2043">2043</option><option value="2044">2044</option><option value="2045">2045</option><option value="2046">2046</option><option value="2047">2047</option><option value="2048">2048</option><option value="2049">2049</option><option value="2050">2050</option><option value="2051">2051</option><option value="2052">2052</option><option value="2053">2053</option><option value="2054">2054</option><option value="2055">2055</option><option value="2056">2056</option><option value="2057">2057</option><option value="2058">2058</option><option value="2059">2059</option><option value="2060">2060</option><option value="2061">2061</option><option value="2062">2062</option><option value="2063">2063</option><option value="2064">2064</option><option value="2065">2065</option><option value="2066">2066</option><option value="2067">2067</option><option value="2068">2068</option><option value="2069">2069</option><option value="2070">2070</option><option value="2071">2071</option><option value="2072">2072</option><option value="2073">2073</option><option value="2074">2074</option><option value="2075">2075</option><option value="2076">2076</option><option value="2077">2077</option><option value="2078">2078</option><option value="2079">2079</option><option value="2080">2080</option><option value="2081">2081</option><option value="2082">2082</option><option value="2083">2083</option><option value="2084">2084</option><option value="2085">2085</option><option value="2086">2086</option><option value="2087">2087</option><option value="2088">2088</option><option value="2089">2089</option><option value="2090">2090</option>						  </select>
						<small class="help-block" data-bv-validator="notEmpty" data-bv-for="expiryYear" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small></div>
					</div>
				<!-- / EXPIRY DATE -->
				<div class="col-md-12 col-sm-12 col-xs-12 payment_btnn_live_site">
			<input name="Submit" type="submit" value="Pay & Continue" class="main_paymnt_bttn btn btn-success btn-lg btn-block" disabled="disabled">
			<p> <i class="fa fa-lock" aria-hidden="true"></i> Payment secured by 256-bit encryption</p>
			</div>
            </div>
            </div>
 
            <br>
            <!-- <a href="#" class="btn btn-success btn-lg btn-block" role="button">	</a> -->
			
        </div>
		</form>
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
</div>

<?php include 'footer.php';?>