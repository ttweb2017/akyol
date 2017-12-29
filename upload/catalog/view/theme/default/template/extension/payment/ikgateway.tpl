<?php 
/*
* =================================================================
* Ecomerce модуль OPENCART 2.3.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
* =================================================================
*  Этот файл предназначен для Opencart 2.3.x
*  www.ttweb.org не гарантирует правильную работу этого расширения на любой другой
*  версии Opencart, кроме Opencart 2.3.x
*  данный продукт не поддерживает программное обеспечение для других
*  версий Opencart.
* =================================================================
*/
?>

<div id="error"></div>
<input type="hidden" name="payment_method" value="<?php echo $code; ?>" />
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$.ajax({
		url: 'index.php?route=extension/payment/ikgateway/confirm',
		type: 'post',
		data: $('input[name=\'payment_method\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
		success: function(json) {
			$('.text-danger').remove();

			if (json['error']) {
				alert(json['error']);
						$('#error').html('<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
						$('#error').fadeIn(3000);
			}
			
			if (json['redirect']) {
				location = json['redirect'];	
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script>