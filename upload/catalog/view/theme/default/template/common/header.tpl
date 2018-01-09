<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
</head>
<body class="<?php echo $class; ?>">
<nav id="top">
  <div class="container">
	<div class="new-logo pull-left">
	<ul class="list-inline">
	<?php if ($logo) { ?>
          <li><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a></li>
          <?php } else { ?>
          <li><h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1></li>
    <?php } ?>
	</ul>
	</div>
	<?php echo $currency; ?>
    <?php echo $language; ?>
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
		<li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
		   <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
        <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
        <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
      </ul>
    </div>
  </div>
</nav>
<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <!--<div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div> -->
      </div>
      <div class="col-sm-5"><?php echo $search; ?>
      </div>
      <div class="col-sm-3"><?php echo $cart; ?></div>
    </div>
  </div>
</header>
<?php if ($categories) { ?>
<div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <?php if($categories) {?>
      	  <script type="text/javascript">
            $(document).ready(function () {
                var setari = {
                    over: function () {
                        if ($('#supermenu').hasClass('superbig')) {
                            $(this).find('.bigdiv').slideDown('fast');
                        }
                    },
                    out: function () {
                        if ($('#supermenu').hasClass('superbig')) {
                            $(this).find('.bigdiv').slideUp('fast');
                        }
                    },
                    timeout: 150
                };
                $("#supermenu ul li.tlli").hoverIntent(setari);
                var setariflyout = {
                    over: function () {
                        if ($('#supermenu').hasClass('superbig')) {
                            $(this).find('.flyouttoright').fadeIn('fast');
                        }
                    },
                    out: function () {
                        if ($('#supermenu').hasClass('superbig')) {
                            $(this).find('.flyouttoright').fadeOut('fast');
                        }
                    },
                    timeout: 200
                };
                $("#supermenu ul li div.bigdiv.withflyout > .withchildfo").hoverIntent(setariflyout);
            });
        </script>
        <nav id="supermenu" class="imgmenu superbig">
            <a class="mobile-trigger"><?php echo $text_category; ?></a>
            <ul> <!--  class="exped" -->
                <?php foreach ($categories as $category){ ?>
                    <li class="tlli mkids">
                        <?php if($category['children']) { ?>
                            <a class="superdropper" href="#"><span>+</span><span>-</span></a>
                            <a class="tll" href="<?php echo $category['href']; ?>">
								<img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>"><br>
									<?php echo $category['name']; ?> <!-- <i class="fa fa-angle-down superdropper-2" style="color: #2383CC;"></i> -->
							</a>
                            <div class="bigdiv">
                                <div class="supermenu-left">
                                    <?php foreach($category['children'] as $child){ ?>

                                        <div class="withimage" style="width: 150px;">
                                            <div class="image">
                                                <a href="<?php echo $child['href']; ?>">
                                                    <img src="<?php echo $child['image']; ?>" alt="<?php echo $child['image_name']; ?>" title="<?php echo $child['image_name']; ?>">
                                                </a>
                                            </div>
                                            <div class="name">
                                                <a class="nname" href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
                                            </div>
                                        </div>

                                    <?php } ?>
                                </div>
                                <div class="addingaspace"></div>
                            </div>

                        <?php }else{ ?>
                            <a class="tll" href="<?php echo $category['href']; ?>">
                                <img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>"><br><?php echo $category['name']; ?>
                            </a>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </nav>
      <?php } ?>
      </div>
    </div>
  </div>
<?php } ?>
