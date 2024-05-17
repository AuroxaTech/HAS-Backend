


<!DOCTYPE html>
<html lang="en" class="h-100">

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>Error Message</title>
        @extends('layouts.backend.app')

        @section('content')
            <div class="authincation h-100">
              <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">
                  <div class="col-md-6">
                      <div class="error-page">
                        <div class="error-inner text-center">
                        <div class="dz-error" data-text="404">404</div>
                        <h4 class="error-head"><i class="fa fa-exclamation-triangle text-warning"></i> {{$message}}</h4>
                        
                      <div>
                      <a href="{{route('home')}}" class="btn btn-secondary">BACK TO HOMEPAGE</a>
                  </div>
                </div>
					    </div>
            </div>
           
    @endsection

    @section('script')

    @endsection
