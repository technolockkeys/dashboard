<div class="menu-item @if(request()->routeIs('backend.home')) here show @endif">
    <a class="menu-link" href="{{ route("backend.home") }}">
							<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none">
													<rect x="2" y="2" width="9" height="9" rx="2"
                                                          fill="currentColor"></rect>
													<rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                                          fill="currentColor"></rect>
													<rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                                          fill="currentColor"></rect>
													<rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                                          fill="currentColor"></rect>
												</svg>
											</span>
                                <!--end::Svg Icon-->
										</span>
        <span class="menu-title">
                    {{ trans('backend.menu.dashboard') }}</span>
    </a>
</div>
