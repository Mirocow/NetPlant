<!DOCTYPE html>
<html>
  <head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php
      $cs = Yii::app()->clientScript;
      $cs->registerCssFile('themes/classic/css/bootstrap.less');
      $cs->registerCssFile('themes/classic/css/responsive.less');
      $cs->registerCssFile('/themes/classic/jquery-ui/jquery-ui-1.9.2.custom.css');
      $cs->registerCssFile('/themes/classic/jquery-ui/jquery.ui.1.9.2.ie.css');

      $cs->registerCssFile('/themes/classic/js/pnotify-1.2.0/jquery.pnotify.default.css');
      $cs->registerScriptFile('/themes/classic/js/pnotify-1.2.0/jquery.pnotify.min.js');
      //$cs->registerScriptFile('/themes/classic/js/utils.js');
      $cs->registerScriptFile('/themes/classic/js/jquery-migrate-1.0.0.js');
      //$cs->registerScriptFile('/themes/classic/js/textext-1.3.1-full.min.js');
      $cs->registerCoreScript('jquery.ui');
    ?>
   

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="/favicon.ico">
   
  </head>

  <body itemscope itemtype="http://schema.org/WebPage">

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="/"><i class="icon-cloud icon-3x"></i> NetPlant</a>
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> Username
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">Profile</a></li>
              <li class="divider"></li>
              <li><a href="#">Sign Out</a></li>
            </ul>
          </div>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="/">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="/site/flushCache">Flush cache</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
            <?php echo $content;?>
      <hr>

      <footer>
        <p>&copy; Company <?php echo date("Y");?>. Powered by <a href="http://netplant.ru/">NetPlant hosting panel</a>.</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <?php
    $bootstrapScripts = array(
    	'bootstrap-transition.js',
    	'bootstrap-alert.js',
    	'bootstrap-modal.js',
    	'bootstrap-dropdown.js',
    	'bootstrap-scrollspy.js',
    	'bootstrap-tab.js',
    	'bootstrap-tooltip.js',
    	'bootstrap-popover.js',
    	'bootstrap-button.js',
    	'bootstrap-collapse.js',
    	// 'bootstrap-carousel.js',
    	// 'bootstrap-typeahead.js',
    	);
    foreach ($bootstrapScripts as $js) {
    	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/'.$js, CClientScript::POS_END);
    }
	?>

  </body>
</html>