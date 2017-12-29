<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_message; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  
  <div class="col-md-12" style="padding-bottom: 3%;"><?php var_dump($received_data) ?></b></div>
<?php echo $footer; ?>