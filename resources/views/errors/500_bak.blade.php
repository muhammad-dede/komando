<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Aplikasi Komunikasi Manajemen dan Budaya Organisasi PLN">
	<meta name="author" content="Coderthemes">

	<!-- App Favicon -->
	<link rel="shortcut icon" href="{{asset('assets/images/logo_pln.jpg')}}">

	<!-- App title -->
	<title>{{env('SITE_TITLE','PT PLN (Persero)')}}</title>

	<!-- App CSS -->
	<link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script src="{{asset('assets/js/modernizr.min.js')}}"></script>

	<style>
		/*html, body {*/
			/*height: 100%;*/
		/*}*/

		body {
			margin: 0;
			padding: 0;
			width: 100%;
			color: #FFFFFF;
			display: table;
			/*font-weight: 100;*/
			/*font-family: 'Lato';*/
			background-color: #00A2B9;
		}

		/*.container {*/
			/*text-align: center;*/
			/*display: table-cell;*/
			/*vertical-align: middle;*/
		/*}*/

		/*.content {*/
			/*text-align: center;*/
			/*display: inline-block;*/
		/*}*/

		/*.title {*/
			/*/!*font-size: 14px;*!/*/
			/*margin-bottom: 40px;*/
		/*}*/
	</style>

</head>


<body style="background-color: #00A2B9;">

{{--<div class="account-pages"></div>--}}
{{--<div class="clearfix"></div>--}}
<div class="wrapper-page">

	<div class="ex-page-content text-xs-center">
		<div>
			<img src="{{asset('assets/images/503.png')}}">
		</div>
		{{--<div class="text-error">4<span class="ion-sad"></span>4</div>--}}
		{{--<div class="text-error">4<img src="{{asset('assets/images/503.png')}}">4</div>--}}
		{{--<h2 class="text-uppercase text-white font-600">404</h2>--}}
		<h3 class="text-uppercase text-white font-600 m-t-30">500 - Something went wrong</h3>
		<div>Error Code : {{@$error_code}}</div>
		<div>{{@$error_message}}</div>
		<p class="text-white m-t-30">
			We're sorry, but the server was unable to complete your request. Please try again later.
			If the problem persist, please report to your Administrator and mention this error message.
			{{--Here's a little tip that might help you get back on track.--}}
		</p>
		<br>
		<a class="btn btn-pink waves-effect waves-light" href="{{url('/')}}"> Return Home</a>

	</div>


</div>
<!-- end wrapper page -->


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