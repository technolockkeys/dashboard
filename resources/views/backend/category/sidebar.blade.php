@if(permission_can('show category' ,'admin'))
    <div class="menu-item @if(request()->routeIs('backend.categories.*')) here show @endif">
        <a class="menu-link" href="{{ route("backend.categories.index") }}">
							<span class="menu-icon">
					<!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen010.svg-->
<span class="svg-icon svg-icon-muted  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path opacity="0.3" d="M2 21V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H3C2.4 22 2 21.6 2 21Z" fill="currentColor"/>
<path d="M2 10V3C2 2.4 2.4 2 3 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H3C2.4 11 2 10.6 2 10Z" fill="currentColor"/>
</svg></span>
                                <!--end::Svg Icon-->
										</span>
            <span class="menu-title">
                    {{ trans('backend.menu.categories') }}</span>
        </a>
    </div>

@endif
