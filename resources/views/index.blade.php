<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
	<meta name="author" content="Coderthemes">

	<!-- App Favicon -->
	<link rel="shortcut icon" href="{{asset('assets/images/logo_pln.jpg')}}">

	<!-- App title -->
	<title>{{env('SITE_TITLE', 'PT PLN (Persero)')}}</title>

	<!-- Switchery css -->
	<link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet" />

	<!-- App CSS -->
	<link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<!-- Modernizr js -->
	<script src="{{asset('assets/js/modernizr.min.js')}}"></script>

</head>


<body>

<!-- Navigation Bar-->
<header id="topnav">
	<div class="topbar-main">
		<div class="container">

			<!-- LOGO -->
			<div class="topbar-left">
				<a href="index.html" class="logo">
					<i class="zmdi zmdi-group-work icon-c-logo"></i>
					<span>Uplon</span>
				</a>
			</div>
			<!-- End Logo container-->


			<div class="menu-extras">

				<ul class="nav navbar-nav pull-right">

					<li class="nav-item">
						<!-- Mobile menu toggle-->
						<a class="navbar-toggle">
							<div class="lines">
								<span></span>
								<span></span>
								<span></span>
							</div>
						</a>
						<!-- End mobile menu toggle-->
					</li>

					<li class="nav-item dropdown notification-list">
						<a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
						   aria-haspopup="false" aria-expanded="false">
							<i class="zmdi zmdi-notifications-none noti-icon"></i>
							<span class="noti-icon-badge"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg" aria-labelledby="Preview">
							<!-- item-->
							<div class="dropdown-item noti-title">
								<h5><small><span class="label label-danger pull-xs-right">7</span>Notification</small></h5>
							</div>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<div class="notify-icon bg-success"><i class="icon-bubble"></i></div>
								<p class="notify-details">Robert S. Taylor commented on Admin<small class="text-muted">1min ago</small></p>
							</a>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<div class="notify-icon bg-info"><i class="icon-user"></i></div>
								<p class="notify-details">New user registered.<small class="text-muted">1min ago</small></p>
							</a>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<div class="notify-icon bg-danger"><i class="icon-like"></i></div>
								<p class="notify-details">Carlos Crouch liked <b>Admin</b><small class="text-muted">1min ago</small></p>
							</a>

							<!-- All-->
							<a href="javascript:void(0);" class="dropdown-item notify-item notify-all">
								View All
							</a>

						</div>
					</li>

					<li class="nav-item dropdown notification-list">
						<a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
						   aria-haspopup="false" aria-expanded="false">
							<i class="zmdi zmdi-email noti-icon"></i>
							<span class="noti-icon-badge"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-arrow-success dropdown-lg" aria-labelledby="Preview">
							<!-- item-->
							<div class="dropdown-item noti-title bg-success">
								<h5><small><span class="label label-danger pull-xs-right">7</span>Messages</small></h5>
							</div>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<div class="notify-icon bg-faded">
									<img src="assets/images/users/avatar-2.jpg" alt="img" class="img-circle img-fluid">
								</div>
								<p class="notify-details">
									<b>Robert Taylor</b>
									<span>New tasks needs to be done</span>
									<small class="text-muted">1min ago</small>
								</p>
							</a>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<div class="notify-icon bg-faded">
									<img src="assets/images/users/avatar-3.jpg" alt="img" class="img-circle img-fluid">
								</div>
								<p class="notify-details">
									<b>Carlos Crouch</b>
									<span>New tasks needs to be done</span>
									<small class="text-muted">1min ago</small>
								</p>
							</a>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<div class="notify-icon bg-faded">
									<img src="assets/images/users/avatar-4.jpg" alt="img" class="img-circle img-fluid">
								</div>
								<p class="notify-details">
									<b>Robert Taylor</b>
									<span>New tasks needs to be done</span>
									<small class="text-muted">1min ago</small>
								</p>
							</a>

							<!-- All-->
							<a href="javascript:void(0);" class="dropdown-item notify-item notify-all">
								View All
							</a>

						</div>
					</li>

					<li class="nav-item dropdown notification-list">
						<a class="nav-link waves-effect waves-light right-bar-toggle" href="javascript:void(0);">
							<i class="zmdi zmdi-format-subject noti-icon"></i>
						</a>
					</li>

					<li class="nav-item dropdown notification-list">
						<a class="nav-link dropdown-toggle arrow-none waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
						   aria-haspopup="false" aria-expanded="false">
							<img src="assets/images/users/avatar-1.jpg" alt="user" class="img-circle">
						</a>
						<div class="dropdown-menu dropdown-menu-right dropdown-arrow profile-dropdown " aria-labelledby="Preview">
							<!-- item-->
							<div class="dropdown-item noti-title">
								<h5 class="text-overflow"><small>Welcome ! John</small> </h5>
							</div>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<i class="zmdi zmdi-account-circle"></i> <span>Profile</span>
							</a>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<i class="zmdi zmdi-settings"></i> <span>Settings</span>
							</a>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<i class="zmdi zmdi-lock-open"></i> <span>Lock Screen</span>
							</a>

							<!-- item-->
							<a href="javascript:void(0);" class="dropdown-item notify-item">
								<i class="zmdi zmdi-power"></i> <span>Logout</span>
							</a>

						</div>
					</li>

				</ul>

			</div> <!-- end menu-extras -->
			<div class="clearfix"></div>

		</div> <!-- end container -->
	</div>
	<!-- end topbar-main -->


	<div class="navbar-custom">
		<div class="container">
			<div id="navigation">
				<!-- Navigation Menu-->
				<ul class="navigation-menu">
					<li>
						<a href="index.html"><i class="zmdi zmdi-view-dashboard"></i> <span> Dashboard </span> </a>
					</li>
					<li class="has-submenu">
						<a href="#"><i class="zmdi zmdi-format-underlined"></i> <span> User Interface </span> </a>
						<ul class="submenu megamenu">
							<li>
								<ul>
									<li><a href="ui-buttons.html">Buttons</a></li>
									<li><a href="ui-cards.html">Cards</a></li>
									<li><a href="ui-dropdowns.html">Dropdowns</a></li>
									<li><a href="ui-checkbox-radio.html">Checkboxs-Radios</a></li>
									<li><a href="ui-navs.html">Navs</a></li>
									<li><a href="ui-progress.html">Progress</a></li>
									<li><a href="ui-modals.html">Modals</a></li>
									<li><a href="ui-alerts.html">Alerts</a></li>
									<li><a href="ui-bootstrap.html">Bootstrap UI</a></li>
									<li><a href="ui-typography.html">Typography</a></li>
								</ul>
							</li>
							<li>
								<ul>
									<li><a href="ui-notification.html">Notification</a></li>
									<li><a href="ui-carousel.html">Carousel</a></li>
									<li><a href="components-grid.html">Grid</a></li>
									<li><a href="components-range-sliders.html">Range sliders</a></li>
									<li><a href="components-sweet-alert.html">Sweet Alerts</a></li>
									<li><a href="components-ratings.html">Ratings</a></li>
									<li><a href="components-treeview.html">Treeview</a></li>
									<li><a href="components-tour.html">Tour</a></li>
									<li><a href="widgets-tiles.html">Tile Box</a></li>
									<li><a href="widgets-charts.html">Chart Widgets</a></li>
								</ul>
							</li>
						</ul>
					</li>

					<li class="has-submenu">
						<a href="#"><i class="zmdi zmdi-album"></i> <span> Icons </span> </a>
						<ul class="submenu">
							<li><a href="icons-materialdesign.html">Material Design</a></li>
							<li><a href="icons-ionicons.html">Ion Icons</a></li>
							<li><a href="icons-fontawesome.html">Font awesome</a></li>
							<li><a href="icons-themify.html">Themify Icons</a></li>
							<li><a href="icons-simple-line.html">Simple line Icons</a></li>
							<li><a href="icons-weather.html">Weather Icons</a></li>
							<li><a href="icons-pe7.html">PE7 Icons</a></li>
							<li><a href="icons-typicons.html">Typicons</a></li>
						</ul>
					</li>

					<li class="has-submenu">
						<a href="#"><i class="zmdi zmdi-collection-text"></i><span> Forms </span> </a>
						<ul class="submenu">
							<li><a href="form-elements.html">General Elements</a></li>
							<li><a href="form-advanced.html">Advanced Form</a></li>
							<li><a href="form-validation.html">Form Validation</a></li>
							<li><a href="form-pickers.html">Form Pickers</a></li>
							<li><a href="form-wizard.html">Form Wizard</a></li>
							<li><a href="form-mask.html">Form Masks</a></li>
							<li><a href="form-uploads.html">Multiple File Upload</a></li>
							<li><a href="form-xeditable.html">X-editable</a></li>
						</ul>
					</li>

					<li class="has-submenu">
						<a href="#"><i class="zmdi zmdi-format-list-bulleted"></i> <span> Tables </span> </a>
						<ul class="submenu">
							<li><a href="tables-basic.html">Basic Tables</a></li>
							<li><a href="tables-datatable.html">Data Table</a></li>
							<li><a href="tables-responsive.html">Responsive Table</a></li>
							<li><a href="tables-tablesaw.html">Tablesaw</a></li>
						</ul>
					</li>

					<li class="has-submenu">
						<a href="#"><i class="zmdi zmdi-chart"></i><span> Charts </span> </a>
						<ul class="submenu">
							<li><a href="chart-flot.html">Flot Chart</a></li>
							<li><a href="chart-morris.html">Morris Chart</a></li>
							<li><a href="chart-chartjs.html">Chartjs</a></li>
							<li><a href="chart-peity.html">Peity Charts</a></li>
							<li><a href="chart-chartist.html">Chartist Charts</a></li>
							<li><a href="chart-c3.html">C3 Charts</a></li>
							<li><a href="chart-sparkline.html">Sparkline charts</a></li>
							<li><a href="chart-knob.html">Jquery Knob</a></li>
						</ul>
					</li>

					<li class="has-submenu">
						<a href="#"><i class="zmdi zmdi-collection-item"></i><span> Pages </span> </a>
						<ul class="submenu megamenu">
							<li>
								<ul>
									<li><a href="calendar.html">Calendar</a></li>
									<li><a href="pages-starter.html">Starter Page</a></li>
									<li><a href="pages-login.html">Login</a></li>
									<li><a href="pages-register.html">Register</a></li>
									<li><a href="pages-recoverpw.html">Recover Password</a></li>
									<li><a href="pages-lock-screen.html">Lock Screen</a></li>
									<li><a href="pages-404.html">Error 404</a></li>
									<li><a href="pages-500.html">Error 500</a></li>
									<li><a href="pages-timeline.html">Timeline</a></li>
									<li><a href="pages-invoice.html">Invoice</a></li>
								</ul>
							</li>
						</ul>
					</li>

				</ul>
				<!-- End navigation menu  -->
			</div>
		</div>
	</div>
</header>
<!-- End Navigation Bar-->



<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="wrapper">
	<div class="container">

		<!-- Page-Title -->
		<div class="row">
			<div class="col-sm-12">
				<div class="btn-group pull-right m-t-15">
					<button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light"
							data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i
									class="fa fa-cog"></i></span></button>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="#">Action</a>
						<a class="dropdown-item" href="#">Another action</a>
						<a class="dropdown-item" href="#">Something else here</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">Separated link</a>
					</div>

				</div>
				<h4 class="page-title">Starter Page</h4>
			</div>
		</div>


		<!-- Footer -->
		<footer class="footer text-right">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						2016 � Uplon.
					</div>
				</div>
			</div>
		</footer>
		<!-- End Footer -->


	</div> <!-- container -->




	<!-- Right Sidebar -->
	<div class="side-bar right-bar">
		<div class="nicescroll">
			<ul class="nav nav-tabs text-xs-center">
				<li class="nav-item">
					<a href="#home-2"  class="nav-link active" data-toggle="tab" aria-expanded="false">
						Activity
					</a>
				</li>
				<li class="nav-item">
					<a href="#messages-2" class="nav-link" data-toggle="tab" aria-expanded="true">
						Settings
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade in active" id="home-2">
					<div class="timeline-2">
						<div class="time-item">
							<div class="item-info">
								<small class="text-muted">5 minutes ago</small>
								<p><strong><a href="#" class="text-info">John Doe</a></strong> Uploaded a photo <strong>"DSC000586.jpg"</strong></p>
							</div>
						</div>

						<div class="time-item">
							<div class="item-info">
								<small class="text-muted">30 minutes ago</small>
								<p><a href="" class="text-info">Lorem</a> commented your post.</p>
								<p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
							</div>
						</div>

						<div class="time-item">
							<div class="item-info">
								<small class="text-muted">59 minutes ago</small>
								<p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
								<p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
							</div>
						</div>

						<div class="time-item">
							<div class="item-info">
								<small class="text-muted">1 hour ago</small>
								<p><strong><a href="#" class="text-info">John Doe</a></strong>Uploaded 2 new photos</p>
							</div>
						</div>

						<div class="time-item">
							<div class="item-info">
								<small class="text-muted">3 hours ago</small>
								<p><a href="" class="text-info">Lorem</a> commented your post.</p>
								<p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
							</div>
						</div>

						<div class="time-item">
							<div class="item-info">
								<small class="text-muted">5 hours ago</small>
								<p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
								<p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="messages-2">

					<div class="row m-t-20">
						<div class="col-xs-8">
							<h5 class="m-0">Notifications</h5>
							<p class="text-muted m-b-0"><small>Do you need them?</small></p>
						</div>
						<div class="col-xs-4 text-right">
							<input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
						</div>
					</div>

					<div class="row m-t-20">
						<div class="col-xs-8">
							<h5 class="m-0">API Access</h5>
							<p class="m-b-0 text-muted"><small>Enable/Disable access</small></p>
						</div>
						<div class="col-xs-4 text-right">
							<input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
						</div>
					</div>

					<div class="row m-t-20">
						<div class="col-xs-8">
							<h5 class="m-0">Auto Updates</h5>
							<p class="m-b-0 text-muted"><small>Keep up to date</small></p>
						</div>
						<div class="col-xs-4 text-right">
							<input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
						</div>
					</div>

					<div class="row m-t-20">
						<div class="col-xs-8">
							<h5 class="m-0">Online Status</h5>
							<p class="m-b-0 text-muted"><small>Show your status to all</small></p>
						</div>
						<div class="col-xs-4 text-right">
							<input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
						</div>
					</div>

				</div>
			</div>
		</div> <!-- end nicescroll -->
	</div>
	<!-- /Right-bar -->



</div> <!-- End wrapper -->




<script>
	var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/tether.min.js')}}"></script><!-- Tether for Bootstrap -->
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/waves.js')}}"></script>
<script src="{{asset('assets/js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('assets/plugins/switchery/switchery.min.js')}}"></script>

<!-- App js -->
<script src="{{asset('assets/js/jquery.core.js')}}"></script>
<script src="{{asset('assets/js/jquery.app.js')}}"></script>

</body>
</html>