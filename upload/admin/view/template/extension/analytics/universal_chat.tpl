<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-universal-chat" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-universal-chat" class="form-horizontal">
          <p><a href="https://universalchat.org" target="_blank">Universal</a> allows you to chat with your online visitors from your mobile while they visit your website. It's a free service installed on your Telegram messenger.</p>
          <p>Follow the next steps to add Universal Chat to your website:</p>
          <ol>
            <li><a href="https://universalchat.org/registrations/add" target="_blank">Register for a free account</a></li>
            <li>Copy the HTML Widget Code into the 'Html Widget Code' box below</li>
          </ol>
          <p>And you are good to go!</p>
          <ul>
            <li>If you have any questions about Universal, <a href="https://universalchat.org/pages/faq" target="_blank">read the FAQ</a></li>
            <li>With Universal, you can also <a href="https://universalchat.org/pages/usinggroups" target="_blank">set up a Service Center</a></li>
            <li>The Business Plan allows you to <a href="https://universalchat.org/pages/usingchannels" target="_blank">create multiple communication channels</a></li>
          </ul>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-code"><?php echo $entry_code; ?></label>
            <div class="col-sm-10">
              <textarea name="universal_chat_code" rows="5" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control"><?php echo $universal_chat_code; ?></textarea>
              <?php if ($error_code) { ?>
              <div class="text-danger"><?php echo $error_code; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-use_sku"><span data-toggle="tooltip" title="<?php echo $help_use_sku; ?>"><?php echo $entry_use_sku; ?></span></label>
            <div class="col-sm-10">
              <select name="universal_chat_use_sku" id="entry-use_sku" class="form-control">
                <?php if ($universal_chat_use_sku) { ?>
                <option value="1" selected="selected"><?php echo $text_sku_sku; ?></option>
                <option value="0"><?php echo $text_sku_model; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_sku_sku; ?></option>
                <option value="0" selected="selected"><?php echo $text_sku_model; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="universal_chat_status" id="input-status" class="form-control">
                <?php if ($universal_chat_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 