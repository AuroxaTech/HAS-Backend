
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="keywords" content="">
		<meta name="author" content="">
		<meta name="robots" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Yashadmin:Sales Management System Admin Bootstrap 5 Template">
		<meta property="og:title" content="Yashadmin:Sales Management System Admin Bootstrap 5 Template">
		<meta property="og:description" content="Yashadmin:Sales Management System Admin Bootstrap 5 Template">
		<meta property="og:image" content="page-error-404.html">
		<meta name="format-detection" content="telephone=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<!-- PAGE TITLE HERE -->
		<title>Conference</title>
		<!-- FAVICONS ICON -->
		<link rel="shortcut icon" type="image/png" href="{{ asset('public/assets/images/favicon.png')}}">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!-- Style css -->
		<link href="{{ asset('public/assets/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
		<link href="{{ asset('public/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
		<link href="{{ asset('public/assets/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
		<link href="{{ asset('public/assets/css/style.css')}}" rel="stylesheet">
    </head>
	<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black" data-headerbg="color_1">

		<!--*******************
			Preloader start
		********************-->
		<!-- <div id="preloader">
			<div>
				<img src="images/pre.gif" alt=""> 
			</div>
		</div> -->
		<!--*******************
			Preloader end
		********************-->

		<!--**********************************
			Main wrapper start
		***********************************-->
		<div id="main-wrapper">
            <x-layout.header/>
            <x-layout.sidebar/>
                @yield('content')
            <x-layout.footer />

			<!--**********************************
				Scripts
			***********************************-->
			<!-- Required vendors --> 
			<script src="{{ asset('public/assets/vendor/global/global.min.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/chart.js/Chart.bundle.min.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/apexchart/apexchart.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/jquery-steps/build/jquery.steps.min.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
			<script src="{{ asset('public/assets/js/plugins-init/jquery.validate-init.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
			<script src="{{ asset('public/assets/js/dashboard/dashboard-2.js')}}"></script>		
			<script src="{{ asset('public/assets/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/datatables/js/dataTables.buttons.min.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/datatables/js/buttons.html5.min.js')}}"></script>
			<script src="{{ asset('public/assets/vendor/datatables/js/jszip.min.js')}}"></script>
			<script src="{{ asset('public/assets/js/plugins-init/datatables.init.js')}}"></script>
			<script src="{{ asset('public/assets/js/custom.js')}}"></script>
			<script src="{{ asset('public/assets/js/custom-core.js')}}"></script>
			<script src="{{ asset('public/assets/js/deznav-init.js')}}"></script>
			<script src="{{ asset('public/assets/js/demo.js')}}"></script>
        @yield('script')
    </body>
</html>        