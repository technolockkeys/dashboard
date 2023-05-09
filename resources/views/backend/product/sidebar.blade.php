
@if(permission_can('show product' ,'admin')  || permission_can('import product' ,'admin'))    <div data-kt-menu-trigger="click"
         class="menu-item menu-accordion @if(request()->routeIs('backend.products.*')  ) show @endif ">
									<span class="menu-link">
									 	<span class="menu-icon">
<!--begin::Svg Icon | path: assets/media/icons/duotune/ecommerce/ecm009.svg-->
<span class="svg-icon svg-icon-muted  ">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path opacity="0.3" d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z" fill="currentColor"/>
<path d="M7 16H6C5.4 16 5 15.6 5 15V13H8V15C8 15.6 7.6 16 7 16Z" fill="currentColor"/>
<path opacity="0.3" d="M14 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H14C13.4 22 13 21.6 13 21V14C13 13.4 13.4 13 14 13Z" fill="currentColor"/>
<path d="M18 16H17C16.4 16 16 15.6 16 15V13H19V15C19 15.6 18.6 16 18 16Z" fill="currentColor"/>
<path opacity="0.3" d="M3 2H10C10.6 2 11 2.4 11 3V10C11 10.6 10.6 11 10 11H3C2.4 11 2 10.6 2 10V3C2 2.4 2.4 2 3 2Z" fill="currentColor"/>
<path d="M7 5H6C5.4 5 5 4.6 5 4V2H8V4C8 4.6 7.6 5 7 5Z" fill="currentColor"/>
<path opacity="0.3" d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z" fill="currentColor"/>
<path d="M18 5H17C16.4 5 16 4.6 16 4V2H19V4C19 4.6 18.6 5 18 5Z" fill="currentColor"/>
</svg></span>
                                            <!--end::Svg Icon-->

                     					</span>
										<span class="menu-title">{{trans('backend.menu.products')}}</span>
										<span class="menu-arrow"></span>
									</span>
        <div class="menu-sub menu-sub-accordion  ">

            @if(permission_can('show product' ,'admin')  )
                <div
                    class="menu-item  @if(request()->routeIs('backend.products.*')  &&  !request()->routeIs('backend.products.import')   &&  !request()->routeIs('backend.products.show_out_of_stock') )here show @endif ">
                    <a class="menu-link"
                       href="{{route('backend.products.index')}}">

		                        <span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.menu.products')}}</span>


                    </a>
                </div>
            @endif
                @if(permission_can('show product' ,'admin') )
                <div
                    class="menu-item  @if(request()->routeIs('backend.products.show_out_of_stock')  )here show @endif ">
                    <a class="menu-link"
                       href="{{route('backend.products.show_out_of_stock')}}">

		                        <span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.menu.out_of_stock')}}</span>


                    </a>
                </div>
            @endif
                @if(permission_can('import product' ,'admin') )
                <div
                    class="menu-item  @if(request()->routeIs('backend.products.import')  )here show @endif ">
                    <a class="menu-link"
                       href="{{route('backend.products.import')}}">

		                        <span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
                        <span class="menu-title">{{trans('backend.menu.import_product')}}</span>


                    </a>
                </div>
            @endif
        </div>
    </div>
@endif
