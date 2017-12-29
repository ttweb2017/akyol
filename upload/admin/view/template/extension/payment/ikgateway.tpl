<?php
/* Создано в компании www.ttweb.com
 * =================================================================
 * Ecomerce модуль OPENCART 2.3.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 2.3.x
 *  данный продукт не поддерживает программное обеспечение для других
 *  версий Opencart.
 * =================================================================
*/
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="ik" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
           
            <div class="panel-body">
                <ul id="tabs" class="nav nav-tabs">
                    <li class="active"><a href="#tab_general" data-toggle="tab" aria-expanded="true"><?php echo $tab_general; ?></a></li>
                </ul>
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="ik" class="form-horizontal">
                    <div class="tab-content">
                        <div id="tab_general" class="tab-pane active">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ikgateway_status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10"><select class="form-control" id="ikgateway_status" name="ikgateway_status">
                                        <?php if ($ikgateway_status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ikgateway_order_status_id"><?php echo $entry_order_status; ?></label>
                                <div class="col-sm-10"><select class="form-control" id="ikgateway_order_status_id" name="ikgateway_order_status_id">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                        <?php if (!isset($order_status['order_status_id'])) continue; ?>
                                        <?php if ($order_status['order_status_id'] == $ikgateway_order_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"
                                                selected="selected"><?php echo $order_status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ikgateway_sort_order"><?php echo $entry_sort_order; ?></label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="ikgateway_sort_order" name="ikgateway_sort_order"
                                           value="<?php echo $ikgateway_sort_order; ?>"
                                           size="1"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ikgateway_currency">
                                    <span data-toggle="tooltip" title="" data-original-title="<?php echo $entry_ik_currency_help; ?>"><?php echo $entry_ik_currency; ?></span>
                                </label>
                                <div class="col-sm-10"><select class="form-control" id="ikgateway_currency" name="ikgateway_currency">
                                        <?php foreach ($currencies as $currency) { ?>
                                        <?php if ($currency['code'] == $ikgateway_currency) { ?>
                                        <option value="<?php echo $currency['code']; ?>"
                                                selected="selected"><?php echo $currency['title']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select></div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12"><strong><?php echo $text_ik_parameters; ?></strong></div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label required" for="ikgateway_shop_id">
                                    <span data-toggle="tooltip" title="" data-original-title="<?php echo $entry_ik_shop_id_help; ?>"><?php echo $entry_ik_shop_id ?></span>
                                </label>
                                <div class="col-sm-10"><input type="text" id="ikgateway_shop_id" class="form-control" name="ikgateway_shop_id" value="<?php echo $ikgateway_shop_id; ?>"/>
                                    <?php if ($error_ik_shop_id) { ?>
                                    <span class="error"><?php echo $error_ik_shop_id; ?></span>
                                    <?php } ?></div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label required" for="ikgateway_sign_hash">
                                    <span data-toggle="tooltip" title="" data-original-title="<?php echo $entry_ik_sign_hash_help; ?>"><?php echo $entry_ik_sign_hash ?></span>
                                </label>
                                <div class="col-sm-10"><input type="text" id="ikgateway_sign_hash" class="form-control" name="ikgateway_sign_hash" value="<?php echo $ikgateway_sign_hash; ?>"/>
                                    <?php if ($error_ik_sign_hash) { ?>
                                    <span class="error"><?php echo $error_ik_sign_hash; ?></span>
                                    <?php } ?></div>
                            </div>


                             <div class="form-group">
                                <label class="col-sm-2 control-label required" for="ikgateway_counter">
                                    <span data-toggle="tooltip" title="" data-original-title="<?php echo $ikgateway_counter_help; ?>"><?php echo $ikgateway_counter_text ?></span>
                                </label>
                                <div class="col-sm-10"><input type="number" id="ikgateway_counter" class="form-control" name="ikgateway_counter" value="<?php echo $ikgateway_counter; ?>"/>
							</div>
                                 </div>

                        </div><!-- </div id="tab_general">  -->
                      <!-- </div id="tab_log">  -->
                    </div><!-- </div class="tab-content">  -->
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>