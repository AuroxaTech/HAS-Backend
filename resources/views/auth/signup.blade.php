<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="Enzo admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities."
    />
    <meta
      name="keywords"
      content="admin template, Enzo admin template, dashboard template, flat admin template, responsive admin template, web app"
    />
    <meta name="author" content="pixelstrap" />
    <link
      rel="icon"
      href="{{ asset('public/assets/images/favicon/favicon.png')}}"
      type="image/x-icon"
    />
    <link
      rel="shortcut icon"
      href="{{ asset('public/assets/images/favicon/favicon.png')}}"
      type="image/x-icon"
    />
    <title>OAS | Login</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="{{ asset('public/assets/css/vendors/font-awesome.css')}}"
    />
    <!-- ico-font-->
    <link
      rel="stylesheet"
      type="text/css"
      href="{{ asset('public/assets/css/vendors/icofont.css')}}"
    />
    <!-- Themify icon-->
    <link
      rel="stylesheet"
      type="text/css"
      href="{{ asset('public/assets/css/vendors/themify.css')}}"
    />
    <!-- Flag icon-->
    <link
      rel="stylesheet"
      type="text/css"
      href="{{ asset('public/assets/css/vendors/flag-icon.css')}}"
    />
    <!-- Feather icon-->
    <link
      rel="stylesheet"
      type="text/css"
      href="{{ asset('public/assets/css/vendors/feather-icon.css')}}"
    />
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link
      rel="stylesheet"
      type="text/css"
      href="{{ asset('public/assets/css/vendors/bootstrap.css')}}"
    />
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/style.css')}}" />
    <link
      id="color"
      rel="stylesheet"
      href="{{ asset('public/assets/css/color-1.css')}}"
      media="screen"
    />
    <!-- Responsive css-->
    <link
      rel="stylesheet"
      type="text/css"
      href="{{ asset('public/assets/css/responsive.css')}}"
    />
  </head>
  <body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="loader"></div>
    </div>
    <!-- Loader ends-->
    <!-- login page start-->
    <div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-12 p-0">
          <div class="login-card">
            <div>
              <div>
                <a class="logo" href="index-2.html">
                  <img class="img-fluid for-light" src="{{ asset('public/assets/images/logo.png')}}" style="width: 100px;height: 95px;" alt="loginpage"/>
                </a>
              </div>
              <div class="login-main">
                <form class="theme-form" method="POST" action="{{ route('usersignup') }}" >
                    @csrf
                  <h4 class="text-center">Create your account</h4>
                  <p class="text-center">
                    Enter your personal details to create account
                  </p>
                  <div class="form-group">
                    <label class="col-form-label pt-0">Your Name</label>
                    <div class="row g-2">
                      <div class="col-6">
                        <input class="form-control" type="text" name="firstname" placeholder="First name"/>
                        <span class="text-danger">{{$errors->first('firstname')}}</span>
                      </div>
                      <div class="col-6">
                        <input class="form-control" type="text" name="lastname" placeholder="Last name"/>
                        <span class="text-danger">{{$errors->first('lastname')}}</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Email Address</label>
                    <input class="form-control" type="email" name="email"/>
                    <span class="text-danger">{{$errors->first('email')}}</span>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                      <input class="form-control" type="password" name="password"/>
                      <div class="show-hide"><span class="show"></span></div>
                      <span class="text-danger">{{$errors->first('password')}}</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary btn-block w-100 mt-3" type="submit"> Create Account</button>
                  </div>
                    
                  <p class="mt-4 mb-0 text-center">
                    Already have an account<a class="ms-2" href="{{ route('indexlogin') }}">Sign In</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- latest jquery-->
      <script src="{{ asset('public/assets/js/jquery-3.6.0.min.js')}}"></script>
      <!-- Bootstrap js-->
      <script src="{{ asset('public/assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
      <!-- feather icon js-->
      <script src="{{ asset('public/assets/js/icons/feather-icon/feather.min.js')}}"></script>
      <script src="{{ asset('public/assets/js/icons/feather-icon/feather-icon.js')}}"></script>
      <!-- scrollbar js-->
      <!-- Sidebar jquery-->
      <script src="{{ asset('public/assets/js/config.js')}}"></script>
      <!-- Plugins JS start-->
      <!-- Plugins JS Ends-->
      <!-- Theme js-->
      <script src="{{ asset('public/assets/js/script.js')}}"></script>
      <!-- login js-->
      <!-- Plugin used-->
    </div>
  </body>
</html>



