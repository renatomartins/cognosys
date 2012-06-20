<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Application Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/humane-jackedup.css">
    <link rel="stylesheet" href="/css/bootstrap-responsive.css">
    <link rel="stylesheet" href="/css/datepicker.css">
    <link rel="stylesheet" href="/css/app.css">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="/img/favicon.ico">
  </head>

  <body>

    <div class="navbar">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Application</a>
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
              <li class="active"><a href="#">Dashboard</a></li>
              <li><a href="#news">News</a></li>
              <li><a href="#about">About</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Sidebar</li>
              <li class="active"><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li class="nav-header">Sidebar</li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li class="nav-header">Sidebar</li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <?=view()?>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Company 2012</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery-1.7.2.min.js"></script>
    <script src="/js/bootstrap/bootstrap-transition.js"></script>
    <script src="/js/bootstrap/bootstrap-alert.js"></script>
    <script src="/js/bootstrap/bootstrap-modal.js"></script>
    <script src="/js/bootstrap/bootstrap-dropdown.js"></script>
    <script src="/js/bootstrap/bootstrap-scrollspy.js"></script>
    <script src="/js/bootstrap/bootstrap-tab.js"></script>
    <script src="/js/bootstrap/bootstrap-tooltip.js"></script>
    <script src="/js/bootstrap/bootstrap-popover.js"></script>
    <script src="/js/bootstrap/bootstrap-button.js"></script>
    <script src="/js/bootstrap/bootstrap-collapse.js"></script>
    <script src="/js/bootstrap/bootstrap-carousel.js"></script>
    <script src="/js/bootstrap/bootstrap-typeahead.js"></script>
    <script src="/js/bootstrap/bootstrap-datepicker.js"></script>
    <script src="/js/humane-3.0.0.js"></script>
    <script src="/js/cognosys.js"></script>
    <script src="/js/app.js"></script>
    <script>
      onload = function(){
        cognosys.init()
        <?=alerts()?>
      }
    </script>
  </body>
</html>
