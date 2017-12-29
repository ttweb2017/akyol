<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-credit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-credit" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="store_credit_status" id="input-status" class="form-control">
                                        <?php if($store_credit_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0" ><?php echo $text_disabled; ?></option>
                                        <?php }else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="entry-email"><?php echo $entry_email; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="store_credit_email" value="<?php echo $store_credit_email; ?>" placeholder="<?php echo $entry_email; ?>" id="entry-email" class="form-control"/>
                                    <?php if($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-limit"><span data-toggle="tooltip" title="<?php echo $help_limit; ?>"><?php echo $entry_limit; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="store_credit_limit" value="<?php echo $store_credit_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control"/>
                                    <?php if($error_limit) { ?>
                                        <div class="text-danger"><?php echo $error_limit; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-percents"><span data-toggle="tooltip" title="<?php echo $help_percents; ?>"><?php echo $entry_percents; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="store_credit_percents" value="<?php echo $store_credit_percents; ?>" placeholder="<?php echo $entry_percents; ?>" id="input-percents" class="form-control"/>
                                    <?php if($error_percents) { ?>
                                        <div class="text-danger"><?php echo $error_percents; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-downpayment"><span data-toggle="tooltip" title="<?php echo $help_downpayment; ?>"><?php echo $entry_downpayment; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="store_credit_downpayment" value="<?php echo $store_credit_downpayment; ?>" placeholder="<?php echo $entry_downpayment; ?>" id="input-downpayment" class="form-control"/>
                                    <?php if($error_downpayment) { ?>
                                        <div class="text-danger"><?php echo $error_downpayment; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_credit_currency; ?></label>
                                <div class="col-sm-10">
                                    <select name="store_credit_currency" id="input-currency" class="form-control">
                                        <?php foreach ($currencies as $currency) { ?>
                                        <?php if ($currency['code'] == $store_credit_currency) { ?>
                                        <option value="<?php echo $currency['code']; ?>"
                                                selected="selected"><?php echo $currency['title']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                                <div class="col-sm-10">
                                    <select name="store_credit_geo_zone_id" id="input-geo-zone" class="form-control">
                                        <option value="0"><?php echo $text_all_zones; ?></option>
                                        <?php foreach ($geo_zones as $geo_zone) { ?>
                                        <?php if ($geo_zone['geo_zone_id'] == $store_credit_geo_zone_id) { ?>
                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                                                selected="selected"><?php echo $geo_zone['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="store_credit_sort_order" value="<?php echo $store_credit_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>