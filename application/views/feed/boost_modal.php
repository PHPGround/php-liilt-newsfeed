<div id = "modal_boost" class="modal fade modal-boost">
	<div class="modal-dialog">
		<div class="modal-content">
			<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
			<div class="modal-header">
				<button type="button" class="close button_modal_close" onclick="close_boost_modal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title modal_box_title">Boost Your Post For Just €5</h4>
			</div>
			<div class="modal-body">
				<p class="liilt_text">Your post will appear on the Timelines of all LiiLT members</p>
				<div class="full_box">
					<div class="row">
						<div class="col-md-5 col-sm-5">
							<label>
								<span class="card_number">*Card Number</span><br>
								(Enter Card Number exactly as it appears on the card. Please do not include spaces.)
							</label>
						</div><!--col-->
						<div class="col-md-7 col-sm-7">
							<input id="c_number" type="text">
						</div><!--col-->
						<div class="clearfix"></div>
					</div><!--row-->
				</div><!---full_box-->

				<div class="full_box">
					<div class="row">
						<div class="col-md-5 col-sm-5">
							<label>
								<span class="card_number">*CVC</span><br>
								(This is a 3 digit code on the back of Visa & MasterCard while a 4 digit code for American Express Card.)
							</label>
						</div><!--col-->

						<div class="col-md-7 col-sm-7">
							<input id="card-cvc" type="text" >
						</div><!--col-->
						<div class="clearfix"></div>
					</div><!--row-->
				</div><!---full_box-->

				<div class="full_box">
					<div class="row">
						<div class="col-md-5 col-sm-5">
							<label>
								<span class="mm_yy">*Expiration (MM/YY)</span>
							</label>
						</div><!--col-->

						<div class="col-md-7 col-sm-7">
							<select data-stripe="exp-month" id = "expiry_month" class="card-expiry-month">
								<option value="">MM</option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
							
							<?php
								$starting_year = date('Y');
								$ending_year = date('Y', strtotime('+10 year'));

								for($starting_year; $starting_year <= $ending_year; $starting_year++) {
									$years[] = $starting_year;
								}
							?>
							
							<select data-stripe="exp-year" id = "expiry_year" class="card-expiry-year">
								<option value="">YY</option>
								<?
									foreach($years as $year){
										?><option value="<?php echo $year;?>"><?php echo $year;?></option><?
									}
								?>
							</select>
						</div><!--col-->

					<input type="hidden" id="stripe_key" value="<?php echo STRIPE_PUBLISHABLE_KEY; ?>" />
					<input type="hidden" id="post_id" value="<?php echo $post_id; ?>" />
						
						<div class="clearfix"></div>
					</div><!--row-->
					<div class="clearfix"></div>
				</div><!---full_box-->

				<div class="full_box">
					<div class="row">
						<div class="col-md-5 col-sm-5">
							<button id = "btn_boost_pay" type="button" class="btn pay_btn" onclick="boostPost()"><img id = "boost_loader" class = "button_progress hidden" src="<?php echo base_url().'resources/frontend/images/loader-16.gif' ; ?>">Pay Now</button>
							<div class="clearfix"></div>
						</div><!--col-->

						<div class="col-md-7 col-sm-7">
							<img src="<?php echo base_url().'resources/frontend/images/stripe.png' ; ?>">
							<p class="liilt_text">At LiiLT.com we truly value payment security. For that reason, all payments on LiiLT.com are processed through <a target = "_blank" href="http://stripe.com/">Stripe</a></p> 
						</div><!--col-->
						<div class="clearfix"></div>
					</div><!--row-->
					<div class="clearfix"></div>
				</div><!---full_box-->
				<div class="clearfix"></div>
			</div><!---modal-body-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->