<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

    <div class="container"> 
        <ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
		</ul>
		<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $customer_notice; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
        <div class="panel panel-default">
			<div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <!-- <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
                    <div class=""> -->
                        <table id="conf_table" class="table table-striped table-bordered ware">
                            <colgroup>
                                <col width="20%">
                                <col>
                                <col width="60">
                                <col width="90">
                            </colgroup>
                            <tbody>
                                <tr class="head">
                                    <td class="text-center"><?php echo $column_type; ?></td>
                                    <td class="text-center"><?php echo $column_name; ?></td>
                                    <td class="text-center"><?php echo $column_quantity; ?></td>
                                    <td class="text-center"><?php echo $column_total; ?></td>
                                </tr>
                                
									<?php $length = sizeof($categories); ?>
                                    <?php for ($row = 0; $row <= $length - 1; $row++) { ?>
                                        <?php $class = 'odd'; ?>
                                        <?php if(($row + 2) % 2 == 0){ ?>
                                        <?php $class = 'even'; ?>
                                        <?php } ?>
                                        <tr class="<?php echo $class; ?>" id="product_td_<?php echo $row; ?>">
                                            <td>
                                                <i class="fa fa-exclamation-circle"></i> <?php echo $categories[$row]['name']; ?>
                                            </td>
                                            <td>
                                                <div class="switcher" id="switcher_<?php echo $row; ?>">
                                                    <div class="selected">
                                                         <a onclick="sel_change($(this), 0, 0, <?php echo $row; ?>);"><center><?php echo $text_select; ?></center></a> 
                                                    </div> 
                                                    <div class="option" style="display: none;">
                                                        <a onclick="sel_change($(this), 0, 0, <?php echo $row; ?>);"><center><?php echo $text_select; ?></center></a>
                                                        <?php foreach ($categories_2[$categories[$row]['category_id']]['products'] as $product) { ?>
                                                            <?php $prc = '0.00'; ?>
                                                            <?php $prc_x = '0.00'; ?>
                                                            <?php if($product['price']){ ?>
                                                                <?php if(!$product['special']){ ?>
                                                                    <?php $prc = $product['price']; ?>
                                                                    <?php $prc_x = $product['price_xformat']; ?>
                                                                <?php }else { ?> 
                                                                    <?php $prc = $product['special']; ?> 
                                                                    <?php $prc_x = $product['special_xformat']; ?> 
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <a class="hasimg" onclick="sel_change($(this), <?php echo $product['product_id']; ?>, <?php echo $prc_x; ?>, <?php echo $row; ?>);">
                                                                <img src="<?php echo $product['thumb']; ?>" alt="">
                                                                (+ <?php echo $prc_x; ?>) <?php echo $product['name']; ?>				
                                                                <div style="clear:both;"></div>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="products[<?php echo $row; ?>][product_id]" id="sel_count_<?php echo $row; ?>" value="">
                                                <input type="hidden" id="sel_price_count_<?php echo $row; ?>" value="">

                                                <!--*******WORK ON THIS SECTION************-->
                                                <div class="options" id="opt_count_<?php echo $row; ?>" style="display:none;">
                                                    <!--<a href="http://demo53.finesites.ru/#" onclick="toggle_block({{ row }}); return false;" class="a_hide" id="a_hide_{{ row }}">↑</a>
                                                    <h2>Доступные варианты</h2>
		
                                                    <div class="options_block" id="options_block_{{ row }}">
		
                                                        <div id="option-225" class="option3">
                                                            <span class="required">*</span>
                                                            <b>Delivery Date:</b><br>
                                                            <input type="text" name="products[0][option][225]" value="2011-04-22" class="date hasDatepicker" id="dp1510571892998">
                                                        </div>
                                                    </div>-->
                                                </div>
                                                <!--*******UP TO HERE***********************-->
                                            </td>
                                            <td>
                                                <input name="products[<?php echo $row; ?>][quantity]" type="text" onkeyup="upd_price(<?php echo $row; ?>);" style="width:50px;" id="i_count_<?php echo $row; ?>" value="" disabled>
                                            </td>
                                            <td align="right">
                                                <span class="s_count" id="s_count_<?php echo $row; ?>"></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <tr>
                                    <td colspan="3" align="right"><b><?php echo $text_total; ?></b></td>
                                    <td id="td_sum_total" align="right"></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <div class="buttons">
                            <div class="right">
                                <a class="btn btn-primary" onclick="addToCart();"><span> <?php echo $text_add_cart; ?> <i class="fa fa-shopping-cart"></i></span></a>
                            </div>
                        </div>
                    <!--</div>
                </form> cart.add('{{ product.product_id }}', '{{ product.minimum }}');-->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$('.switcher').bind('click', function() {
	$(this).find('.option').slideToggle('fast');
});
$('.switcher').bind('mouseleave', function() {
	$(this).find('.option').slideUp('fast');
}); 
//--></script>	
<script type="text/javascript"><!--
function sel_change(e, pid, p, i){
	$('#conf_table #switcher_'+i+' div.selected').html(e.clone().removeAttr('onclick'));
	
	$('#conf_table input#sel_count_'+i).val(pid);
	$('#conf_table input#sel_price_count_'+i).val(p);

	$('#conf_table div#opt_count_'+i).hide();
	$('#conf_table div#opt_count_'+i).html();
	if(pid !== 0){
		if($('#conf_table input#i_count_'+i).val()==''){
			$('#conf_table input#i_count_'+i).val('1');
		}
		$('#conf_table input#i_count_'+i).removeAttr('disabled');
		
		$.ajax({
			url: 'index.php?route=custom/pc/getproductoptions',
			type: 'post',
			data: 'product_id='+pid+'&i='+i,
			dataType: 'json',
			success: function(json) {
				//alert(json.output);
				if (json['output']) { 
					$('#conf_table div#opt_count_'+i).html(json['output']);
					$('#conf_table div#opt_count_'+i).fadeIn('slow');						
				}
                                
				upd_price(i);
				
				
			}
		});
		
		
	}else{
		$('#conf_table input#i_count_'+i).val('');
		$('#conf_table input#i_count_'+i).attr('disabled', 'true');
                
                //check it here if correct
                $('#product_td_'+i+' span#s_count_'+i).val(0);
                $('#product_td_'+i+' span#s_count_'+i).html('');
                
	}
	
	//inp_change($('#conf_table input#i_count_'+i), i);
	upd_summ();
}
                        
function upd_price(i){
	$.ajax({
					url: 'index.php?route=custom/pc/getproductprice',
					type: 'post',
					data: $('#product_td_'+i+' input[type=\'text\'], #product_td_'+i+' input[type=\'hidden\'], #product_td_'+i+' input[type=\'radio\']:checked, #product_td_'+i+' input[type=\'checkbox\']:checked, #product_td_'+i+' select, #product_td_'+i+' textarea'),
					dataType: 'json',
					success: function(json) {
						//alert(json.price);
						if (json['price']) { 
							var val=0;
							var val1 = $('#conf_table input#i_count_'+i).val();
                                                        //alert(json['option_price']);
							if(val1 !== '')val = val1;
							$('#product_td_'+i+' span#s_count_'+i).html((val*json['price']).toFixed(2));
                                                        upd_summ();
						}
					}
				});
}

function addOptionPrice(i){
    $.ajax({
		url: 'index.php?route=custom/pc/getproductprice',
		type: 'post',
		data: $('#product_td_'+i+' input[type=\'text\'], #product_td_'+i+' input[type=\'hidden\'], #product_td_'+i+' input[type=\'radio\']:checked, #product_td_'+i+' input[type=\'checkbox\']:checked, #product_td_'+i+' select, #product_td_'+i+' textarea'),
		dataType: 'json',
		success: function(json) {
			//alert(json.price);
			if (json['price']) { 
                            var val=0;
                            var val1 = $('#conf_table input#i_count_'+i).val();
                            //alert(json['option_price']);
                            if(val1 !== '')val = val1;
				$('#product_td_'+i+' span#s_count_'+i).html((val*json['price']+parseFloat(json['option_price'])).toFixed(2));
                                upd_summ();
                            }
                                                
			}
            });
}

function upd_summ(){
	var s = 0.00;
	$('#conf_table span.s_count').each(function(){
		if($(this).html()!='') s=s+parseFloat($(this).html());
	});
	$('#conf_table #td_sum_total').html(''+s.toFixed(2)+'');
}

function addToCart() {
	$.ajax({
		url: 'index.php?route=custom/pc/updatecart',
		type: 'post',
		data: $('#conf_table input[type=\'text\'], #conf_table input[type=\'hidden\'], #conf_table input[type=\'radio\']:checked, #conf_table input[type=\'checkbox\']:checked, #conf_table select, #conf_table textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
			
			if (json['error']) {
				var err=0;
				for (var key in json['error']) {
					var error = json['error'][key];
					if(error['warning']){ 
						$('#conf_table input#sel_count_'+key).after('<span class="error">' + error['warning'] + '</span>');
					}
					for (i in error) { 
						if(i!='warning'){ err=1;}
						$('#option-' + i).after('<span class="error">' + error[i] + '</span>');
					}
				}
				if(err==1){
					$('#conf_table div#options_block_'+key).fadeIn('slow', function(){
						$('#conf_table a#a_hide_'+key).html('&uarr;');
						$('#conf_table div#options_block_'+key).removeClass( "rolled" );
					});	
				}
                alert(json['error']);
			}

			if (json['success']) {
				$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		}
	});
}

//--></script>
<?php echo $footer; ?> 
