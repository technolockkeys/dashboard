@if((permission_can('show countries' ,'admin') || permission_can('show cities' , 'admin') || permission_can('show zones', 'admin')))
    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion @if(request()->routeIs('backend.countries.*') || request()->routeIs('backend.cities.*')  || request()->routeIs('backend.zones.*') ) show @endif ">
									<span class="menu-link">
										<span class="menu-icon">
<!--begin::Svg Icon | path: assets/media/icons/duotune/maps/map009.svg-->
<span class="svg-icon svg-icon-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path opacity="0.3" d="M13.625 22H9.625V3C9.625 2.4 10.025 2 10.625 2H12.625C13.225 2 13.625 2.4 13.625 3V22Z" fill="currentColor"/>
<path d="M19.625 10H12.625V4H19.625L21.025 6.09998C21.325 6.59998 21.325 7.30005 21.025 7.80005L19.625 10Z" fill="currentColor"/>
<path d="M3.62499 16H10.625V10H3.62499L2.225 12.1001C1.925 12.6001 1.925 13.3 2.225 13.8L3.62499 16Z" fill="currentColor"/>
</svg></span>
                                            <!--end::Svg Icon-->
                     					</span>
										<span class="menu-title">{{trans('backend.menu.location')}}</span>
										<span class="menu-arrow"></span>
									</span>
        <div class="menu-sub menu-sub-accordion  ">
            @if(permission_can('show countries' ,'admin'))
                <div class="menu-item @if(request()->routeIs('backend.countries.*')   ) show @endif">
                    <a class="menu-link" href="{{route('backend.countries.index')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.menu.countries')}}</span>
                    </a>
                </div>
            @endif
            @if(permission_can('show cities' ,'admin') && false)
                <div class="menu-item @if( request()->routeIs('backend.cities.*')  ) show @endif">
                    <a class="menu-link" href="{{route('backend.cities.index')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.menu.cities')}}</span>
                    </a>
                </div>
            @endif
            @if(permission_can('show zones' ,'admin'))
                <div class="menu-item @if( request()->routeIs('backend.zones.*')  ) show @endif">
                    <a class="menu-link" href="{{route('backend.zones.index')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.menu.zones')}}</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif
