@if(permission_can('setting website' ,'admin') || permission_can('setting smtp' ,'admin') || permission_can('setting social','admin') )
    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion @if(request()->routeIs('backend.setting.*')   ) show @endif ">
									<span class="menu-link">
										<span class="menu-icon">
<!--begin::Svg Icon | path: assets/media/icons/duotune/coding/cod001.svg-->
<span class="svg-icon svg-icon-muted  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
<path opacity="0.3"
      d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5ZM12.1 8.59998C10.2 8.59998 8.6 10.2 8.6 12.1C8.6 14 10.2 15.6 12.1 15.6C14 15.6 15.6 14 15.6 12.1C15.6 10.2 14 8.59998 12.1 8.59998Z"
      fill="currentColor"/>
<path
        d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z"
        fill="currentColor"/>
</svg></span>
                                            <!--end::Svg Icon-->
                     					</span>
										<span class="menu-title">{{trans('backend.menu.setting')}}</span>
										<span class="menu-arrow"></span>
									</span>
        <div class="menu-sub menu-sub-accordion  ">
            @if(permission_can('setting website' ,'admin'))
                <div class="menu-item @if(request()->routeIs('backend.setting.index')   ) show @endif">
                    <a class="menu-link" href="{{route('backend.setting.index')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.website')}}</span>
                    </a>
                </div>
            @endif

            @if(permission_can('setting smtp' ,'admin'))
                <div class="menu-item @if(request()->routeIs('backend.setting.smtp')   ) show @endif">

                    <a class="menu-link" href="{{route('backend.setting.smtp')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.smtp')}}</span>
                    </a>
                </div>
            @endif

            @if(permission_can('setting global_seo' ,'admin'))
                <div class="menu-item @if(request()->routeIs('backend.setting.global')   ) show @endif">

                    <a class="menu-link" href="{{route('backend.setting.global')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.global_seo')}}</span>
                    </a>
                </div>
            @endif

            @if(permission_can('setting social' ,'admin'))
                <div class="menu-item @if(request()->routeIs('backend.setting.social')   ) show @endif">

                    <a class="menu-link" href="{{route('backend.setting.social')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.social.social')}}</span>
                    </a>
                </div>
            @endif

            @if(permission_can('setting contact' ,'admin'))
                <div class="menu-item @if(request()->routeIs('backend.setting.contact')   ) show @endif">

                    <a class="menu-link" href="{{route('backend.setting.contact')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.social.contact')}}</span>
                    </a>
                </div>
            @endif

            @if(permission_can('setting payment' ,'admin'))
                <div class="menu-item @if(request()->routeIs('backend.setting.payment')   ) show @endif">

                    <a class="menu-link" href="{{route('backend.setting.payment')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.payment')}}</span>
                    </a>
                </div>
            @endif
{{--            @if(permission_can('setting default_images' ,'admin'))--}}
{{--                <div class="menu-item @if(request()->routeIs('backend.setting.default_images')   ) show @endif">--}}

{{--                    <a class="menu-link" href="{{route('backend.setting.default_images')}}">--}}
{{--												<span class="menu-bullet">--}}
{{--													<span class="bullet bullet-dot"></span>--}}
{{--												</span>--}}
{{--                        <span class="menu-title">{{trans('backend.setting.default_images')}}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            @endif--}}

        </div>
        @if(permission_can('setting translate' ,'admin'))
        <div class="menu-sub menu-sub-accordion  ">

                <div class="menu-item @if(request()->routeIs('backend.setting.translate')   ) show @endif">

                    <a class="menu-link" href="{{route('backend.setting.translate')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.translate')}}</span>
                    </a>
                </div>


        </div>
        @endif
        @if(permission_can('setting shipping' ,'admin'))
        <div class="menu-sub menu-sub-accordion  ">

                <div class="menu-item @if(request()->routeIs('backend.setting.shipping')   ) show @endif">

                    <a class="menu-link" href="{{route('backend.setting.shipping')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.shipping_method')}}</span>
                    </a>
                </div>


        </div>
        @endif
        @if(permission_can('setting notifications', 'admin'))
            <div class="menu-sub menu-sub-accordion  ">
                <div class="menu-item @if(request()->routeIs('backend.setting.notifications')   ) show @endif">
                    <a class="menu-link" href="{{route('backend.setting.notifications')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.notifications')}}</span>
                    </a>
                </div>
            </div>
        @endif
        @if(permission_can('setting frontend' ,'admin'))
        <div class="menu-sub menu-sub-accordion  ">
                <div class="menu-item @if(request()->routeIs('backend.setting.frontend')   ) show @endif">
                    <a class="menu-link" href="{{route('backend.setting.frontend')}}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.setting.frontend')}}</span>
                    </a>
                </div>
        </div>
        @endif
    </div>
@endif
