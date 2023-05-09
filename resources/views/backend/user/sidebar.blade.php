@if(permission_can('show users' ,'admin') )
    <div
            class="menu-item  @if(request()->routeIs('backend.users.*')  ) here show @endif ">
        <a class="menu-link"
           href="{{route('backend.users.index')}}">
										<span class="menu-icon">
<!--begin::Svg Icon | path: assets/media/icons/duotune/communication/com013.svg-->
<span class="svg-icon svg-icon-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                           fill="none">
<path d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
      fill="currentColor"/>
<rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="currentColor"/>
</svg></span>
                                            <!--end::Svg Icon-->
                     					</span>
            <span class="menu-title">{{trans('backend.menu.users')}}</span>
        </a>

    </div>
@endif
