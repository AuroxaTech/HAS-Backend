<!-- ***********************************--> 

                    <!--**********************************
                        Sidebar start
                    ***********************************-->
                <div class="deznav">
                  <div class="deznav-scroll">
                    <ul class="metismenu" id="menu">
                      <li><a href="{{ route('home') }}">
                        <div class="menu-icon">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.13478 20.7733V17.7156C9.13478 16.9351 9.77217 16.3023 10.5584 16.3023H13.4326C13.8102 16.3023 14.1723 16.4512 14.4393 16.7163C14.7063 16.9813 14.8563 17.3408 14.8563 17.7156V20.7733C14.8539 21.0978 14.9821 21.4099 15.2124 21.6402C15.4427 21.8705 15.756 22 16.0829 22H18.0438C18.9596 22.0024 19.8388 21.6428 20.4872 21.0008C21.1356 20.3588 21.5 19.487 21.5 18.5778V9.86686C21.5 9.13246 21.1721 8.43584 20.6046 7.96467L13.934 2.67587C12.7737 1.74856 11.1111 1.7785 9.98539 2.74698L3.46701 7.96467C2.87274 8.42195 2.51755 9.12064 2.5 9.86686V18.5689C2.5 20.4639 4.04738 22 5.95617 22H7.87229C8.55123 22 9.103 21.4562 9.10792 20.7822L9.13478 20.7733Z" fill="#90959F"/>
                          </svg>
                        </div>	
                        <span class="nav-text">Dashboard</span>
                        </a>
                      </li>
                      @if ($collection)
                        @php
                          $menuchecker = 0;
                        @endphp
                            @foreach ( $categories as $cat)
                              @php
                                $modules_of_this_category = $module_object->wherestatus(1)->wherecategory_id($cat->id)->get();
                              @endphp
                              @if(!$modules_of_this_category->isEmpty())
                                    @foreach($modules_of_this_category as $mod)
                                      @if(in_array($mod->slug , $collection))
                                        @if($menuchecker == 0)
                                        <li><a class="has-arrow " href="javascript:void(0);" >
                                          <div class="menu-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.5">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.2428 4.73756C15.2428 6.95855 17.0459 8.75902 19.2702 8.75902C19.5151 8.75782 19.7594 8.73431 20 8.68878V16.6615C20 20.0156 18.0215 22 14.6624 22H7.34636C3.97851 22 2 20.0156 2 16.6615V9.3561C2 6.00195 3.97851 4 7.34636 4H15.3131C15.2659 4.243 15.2423 4.49001 15.2428 4.73756ZM13.15 14.8966L16.0078 11.2088V11.1912C16.2525 10.8625 16.1901 10.3989 15.8671 10.1463C15.7108 10.0257 15.5122 9.97345 15.3167 10.0016C15.1211 10.0297 14.9453 10.1358 14.8295 10.2956L12.4201 13.3951L9.6766 11.2351C9.51997 11.1131 9.32071 11.0592 9.12381 11.0856C8.92691 11.1121 8.74898 11.2166 8.63019 11.3756L5.67562 15.1863C5.57177 15.3158 5.51586 15.4771 5.51734 15.6429C5.5002 15.9781 5.71187 16.2826 6.03238 16.3838C6.35288 16.485 6.70138 16.3573 6.88031 16.0732L9.35125 12.8771L12.0948 15.0283C12.2508 15.1541 12.4514 15.2111 12.6504 15.1863C12.8494 15.1615 13.0297 15.0569 13.15 14.8966Z" fill="white"/>
                                                <circle opacity="0.4" cx="19.5" cy="4.5" r="2.5" fill="white"/>
                                                </g>
                                              </svg>
                                          </div>	
                                          <span class="nav-text">{{$cat->name}}</span>
                                          </a>
                                          <ul >
                                            @php
                                              $menuchecker++;
                                            @endphp
                                      @endif
                                      <li><a href="{{ $mod->menu_template }}">{{ $mod->module_name}}</a></li>
                                      @endif
                                    @endforeach
                              @endif
                              @if($menuchecker == 1)
                                  </ul>
                              </li>
                              @php
                                $menuchecker = 0;
                              @endphp
                              @endif

                            @endforeach
                        @else
                            You don't have permission
                        @endif

                    </ul>
                  </div>
                </div>

<!--**********************************
    Sidebar end
***********************************-->