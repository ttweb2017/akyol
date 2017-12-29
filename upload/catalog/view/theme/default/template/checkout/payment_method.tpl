<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<?php foreach ($payment_methods as $payment_method) { ?>
<div class="radio">
  <label>
    <?php if ($payment_method['code'] == $code || !$code) { ?>
    <?php $code = $payment_method['code']; ?>
    <input type="radio" name="payment_method" onclick="credit('<?php echo $payment_method['code']; ?>');" value="<?php echo $payment_method['code']; ?>" checked="checked" />
	<?php }else{ ?>
	<input type="radio" name="payment_method" onclick="credit('<?php echo $payment_method['code']; ?>');" value="<?php echo $payment_method['code']; ?>" />
	<?php } ?>
    <?php echo $payment_method['title']; ?>
    <?php if ($payment_method['terms']) { ?>
    (<?php echo $payment_method['terms']; ?>)
    <?php } ?>
  </label>
</div>
<?php } ?>
<?php } ?>

<div class="col-xs-12"><!--style="background-color: #a0cdee54;border: 1px solid #ddd;"-->
	<div class="col-xs-10 col-xs-offset-1" id="credit"></div>
</div>

<p><strong><?php echo $text_comments; ?></strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control"><?php echo $comment; ?></textarea>
</p>
<?php if ($text_agree) { ?>
<div class="buttons">
  <div class="pull-right"><?php echo $text_agree; ?>
    <?php if ($agree) { ?>
    <input type="checkbox" name="agree" value="1" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="agree" value="1" />
    <?php } ?>
    &nbsp;
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } else { ?>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } ?>
<script type="text/javascript"><!--
	function credit(data){
		$.ajax({
			url: 'index.php?route=extension/payment/store_credit/getcredit',
			type: 'post',
			data: 'payment_method='+data,
			dataType: 'json',
			success: function(json) {
				//alert(json.output);
				if (json.output) { 
						//alert(json.output);
						$('#credit').html(json.output);
						$('#credit').fadeIn(3000);
						
				}
													
			}
				});
	}
	
	function addOptionPrice(){
		$.ajax({
			url: 'index.php?route=extension/payment/store_credit/getcreditoption',
			type: 'post',
			data: $('#credit-option input[type=\'radio\']:checked'),
			dataType: 'json',
			success: function(json) {
				//alert(json.price);
				if (json.price) { 
						//alert(json.price);
						//$('#credit_result').html(json['price']).toFixed(2));
						$('#credit_result').html(json.price);
						$('#credit_result').fadeIn(3000);
						
				}
													
			}
				});
	}
//--></script>
