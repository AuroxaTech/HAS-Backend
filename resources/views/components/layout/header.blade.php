 <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ route('home') }}" class="brand-logo">
				<img src="{{ asset('public/assets/images/logo/logo.png')}}" width="200" alt="" /> 
            </a>
            
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->
		
		<!--**********************************
            Chat box start
        ***********************************-->
		
		<!--**********************************
            Chat box End
        ***********************************-->
		
		<!--**********************************
            Header start
        ***********************************-->
		<div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
						<div class="header-left">
							<div class="dashboard_bar" style="color: #452b90;display: flex;">
                                Conference Management System
								<?php if(Auth::user()->account_activated_conference) {?>
									@php
										$conference_name = $conference_object->select('name')->whereid(Auth::user()->account_activated_conference)->get();
									@endphp
									<h4 style="padding: 5px 0px 0px 40px;">
										{{ $conference_name[0]['name'] }}
									</h4>
								<?php } else {?>
									<h4 style="padding-left: 40px;padding-top: 5px;color: red;">
										Need to Active Conference Before Start 
									</h4>
								<?php } ?>
                            </div>
						</div>
                        <div class="header-right d-flex align-items-center">
							
							<ul class="navbar-nav">
								<li class="nav-item dropdown notification_dropdown">
									
								</li>
								
								<li class="nav-item ps-3">
									<div class="dropdown header-profile2">
										<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" >
											<div class="header-info2 d-flex align-items-center">
												<div class="header-media">
													<img src="{{ asset('public/assets/images/user.jpg')}}" alt="">
												</div>
											</div>
										</a>
										<div class="dropdown-menu dropdown-menu-end" style="">
											<div class="card border-0 mb-0">
												
												<p style="padding: 13px 0px 0px 23px;">Welcome, {{ Auth::user()->name }}!</p>
												<div class="card-footer px-0 py-2">
													<a href="javascript:void(0);" class="dropdown-item ai-icon ">
													</a>
													<a  href="{{ url('/logout') }}"class="dropdown-item ai-icon">
														<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
														<span class="ms-2">Logout </span>
													</a>
												</div>
											</div>
											
										</div>
									</div>
								</li>
							</ul>
						</div>
                    </div>
				</nav>
			</div>
		</div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->