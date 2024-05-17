<!DOCTYPE html>
<html lang="en" class="h-100">
	<head>
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

		<!-- PAGE TITLE HERE -->
		<title>Login</title>

		<!-- FAVICONS ICON -->
		<link rel="shortcut icon" type="image/png" href="{{ asset('public/assets/images/favicon.png')}}">
		<link href="{{ asset('public/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
		<link href="{{ asset('public/assets/css/style.css')}}" rel="stylesheet">

	</head>

	<body class="vh-100">
		<div class="page-wraper">

			<!-- Content -->
			<div class="browse-job login-style3">
				<!-- Coming Soon -->
				<div class="bg-img-fix overflow-hidden"
					style="background:#fff url({{ asset('public/assets/images/background/bg6.jpg')}}); height: 100vh;">
					<div class="row gx-0 justify-content-center align-items-center" style="height: 100%;">
						<div class="col-xl-4 col-lg-5 col-md-6 col-sm-12 vh-100 bg-white">
							<div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside"
								style="max-height: 653px;" tabindex="0">
								<div id="mCSB_1_container" class="mCSB_container" style="position:relative; top:0; left:0;"
									dir="ltr">
									<div class="login-form style-2">


										<div class="card-body">
											<div class="logo-header">
												<a href="javascript:void(0);" class="logo"><img
														src="{{ asset('public/assets/images/logo/riphah-logo-dark.png')}}" alt=""
														class="width-230 light-logo" style="width: 350px;height: 110px;padding-left: 75px;"></a>
												<a href="javascript:void(0);" class="logo"></a>
											</div>

											<nav>
												<div class="nav nav-tabs border-bottom-0" id="nav-tab" role="tablist">
													<div class="tab-content w-100" id="nav-tabContent">
														<div class="tab-pane fade show active" id="nav-personal"
															role="tabpanel" aria-labelledby="nav-personal-tab">

															<form id="login-form" class=" dz-form pb-3">
																@csrf
																<input type="hidden" name="flag" value="web">
																<h3 class="text-center form-title m-t0">Login</h3>
																<div class="dz-separator-outer m-b5">
																	<div class="dz-separator bg-primary style-liner"></div>
																</div>
																<p class="text-center">Enter your e-mail address and your password. </p>
																<div class="form-group mb-3">
																	<input type="text" name="email" class="form-control"
																		placeholder="Enter Valid Email">
																		<span id="email-error" class="text-danger"></span>
																</div>
																<div class="form-group mb-3">
																	<input type="password" name="password"
																		class="form-control" placeholder="Enter Password">
																		<span id="password-error" class="text-danger"></span>
																</div>
																<div class="form-group text-center mb-5 forget-main">
																	<button type="button" id="login-button"
																		class="btn btn-primary">Sign Me In</button>
																</div>
															</form>
														</div>
														<div class="tab-pane fade" id="nav-forget" role="tabpanel"
															aria-labelledby="nav-forget-tab">

														</div>

													</div>

												</div>
											</nav>
										</div>
									</div>

								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<!-- Full Blog Page Contant -->
		</div>
		<!-- Content END-->
		</div>

		<!--**********************************
		Scripts
		***********************************-->
		<!-- Required vendors -->
		<script src="{{ asset('public/assets/vendor/global/global.min.js')}}"></script>
		<script src="{{ asset('public/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
		<script src="{{ asset('public/assets/js/deznav-init.js')}}"></script>
		<script src="{{ asset('public/assets/js/custom.js')}}"></script>
		<script src="{{ asset('public/assets/js/demo.js')}}"></script><!-- Include jQuery from a CDN -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<script>
			$(document).ready(function () {
				$("#login-button").on("click", function () {
					var formData = $("#login-form").serialize();
					$("span[id$='-error']").empty();
					$.ajax({
						type: "POST",
						url: "{{ route('userlogin') }}",
						data: formData,
						success: function (response) {
							window.location.href = "{{ route('home') }}";
						},
						error: function (xhr) {
							if (xhr.status === 422) {
								var errors = xhr.responseJSON.errors;
								$.each(errors, function (key, value) {
									$("#" + key + "-error").html(value);
								});
							} else {
								console.log("Login failed", xhr.responseJSON.errors);
							}
						}
					});
				});
			});

		</script>
	</body>
</html>
