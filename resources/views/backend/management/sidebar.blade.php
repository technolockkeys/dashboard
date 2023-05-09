@if(permission_can('show admins' ,'admin') || permission_can('show roles' , 'admin'))
    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion @if(request()->routeIs('backend.admins.*') || request()->routeIs('backend.roles.*')  ) show @endif ">
									<span class="menu-link">
										<span class="menu-icon">
 <span class="svg-icon svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
<path
    d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z"
    fill="currentColor"/>
<rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
<path
    d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z"
    fill="currentColor"/>
<rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
</svg></span>
                     					</span>
										<span class="menu-title">{{trans('backend.menu.management')}}</span>
										<span class="menu-arrow"></span>
									</span>
        <div class="menu-sub menu-sub-accordion  ">
            @if(permission_can('show admins' ,'admin'))
                <div class="menu-item @if(request()->routeIs('backend.admins.*')   ) show @endif">
                    <a class="menu-link" href="{{route('backend.admins.index')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.menu.admins')}}</span>
                    </a>
                </div>
            @endif
            @if(permission_can('show roles' ,'admin'))
                <div class="menu-item @if( request()->routeIs('backend.roles.*')  ) show @endif">
                    <a class="menu-link" href="{{route('backend.roles.index')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.menu.roles')}}</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif
