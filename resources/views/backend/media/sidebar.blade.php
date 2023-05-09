<div class="menu-item @if(request()->routeIs('backend.media.index')) here show @endif">
    <a class="menu-link" href="{{ route("backend.media.index") }}">
							<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
							<span class="svg-icon svg-icon-muted  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="currentColor"/>
<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"/>
</svg></span>
                                <!--end::Svg Icon-->
										</span>
        <span class="menu-title">
                    {{ trans('backend.menu.media') }}</span>
    </a>
</div>
