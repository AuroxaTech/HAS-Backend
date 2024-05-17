<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>Dashboard</title>      
        @extends('layouts.backend.app')
        @section('content')

		<!--**********************************
            Content body start
        ***********************************-->
		<div class="content-body">
			
			<div class="container-fluid">
				<?php if(Auth::user()->account_activated_conference === null) {?>
					<div class="alert alert-warning alert-dismissible fade show">
						<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" 	y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
						<strong>Warning!</strong> NEED TO ACTIVATE CONFERENCE .......
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
						</button>
					</div>
				<?php } ?>
				<div class="row">
					<?php $i =1;?>
					@foreach ($conferences as $con)
						<div class="col-xl-3 col-sm-6">
							<div class="card box-hover">
								<div class="card-header py-3">
									<h5 class="mb-0"># {{ $i }} .{{ $con->name }}</h5>
								</div>
								<div class="card-body">
									<div class="products style-1">
										<img src="{{ asset('public/assets/images/contacts/pic2.jpg')}}" class="avatar avatar-lg rounded-circle" alt="">
										<div>
											<h6>{{ $con->name  }}</h6>
											<span>{{ $con->start_date }}</span>	
										</div>	
									</div>
									<p class="my-3">{{$con->description}}</p>
									<div>
										<p class=" mb-1 font-w500"><strong style="color: black;">Venue : </strong></p>
										<div class="avatar-list avatar-list-stacked">
											<p class=" mb-1 font-w500">{{$con->venue}}</p>
										</div>
									</div>
								</div>
								<div class="card-footer d-flex justify-content-between flex-wrap">
									<div class="due-progress">
										<p class="mb-0 text-black">Due <span class="text-black">: {{ $con->end_date }}</span></p>
									</div>
									<div class="bootstrap-badge">
										@if ($con->status === 1)
											<span class="badge rounded-pill badge-success">Active</span>
										@elseif($con->status === 2)
											<span class="badge rounded-pill badge-primary">In-Active</span>
										@else
											<span class="badge rounded-pill badge-warning">Finished</span>
										@endif
									</div>
								</div>
							</div>
						</div>
					<?php $i++ ; ?>
					@endforeach
				</div>
			</div>
        </div>
		
		<!-- Vertically centered modal -->		
        <!--**********************************
            Content body end
        ***********************************-->

        @endsection

        @section('script')

        @endsection