<div class="aside-menu flex-column-fluid">
    <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
         data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
         data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
         data-kt-scroll-offset="0">
        <div
            class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
            id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
            @include('backend.dashboard.sidebar')
            <div class="menu-item">
                <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">{{trans('backend.menu.products')}}</h4>
            </div>
            @include('backend.category.sidebar')
            @include('backend.attribute.sidebar')
            @include('backend.color.sidebar')
            @include('backend.media.sidebar')
            @include('backend.brand.sidebar')
            @include('backend.manufacturer.sidebar')
            @include('backend.product.sidebar')
            @include('backend.coupon.sidebar')
            @include('backend.currency.sidebar')
            <div class="menu-item">
                <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">{{trans('backend.menu.orders')}}</h4>
            </div>
            @include('backend.order.sidebar')
            @include('backend.offer.sidebar')
            <div class="menu-item">
                <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">General</h4>
            </div>
            @include('backend.location.sidebar')
            @include('backend.page.sidebar')
            @include('backend.whatnew.sidebar')
            @include('backend.cms.sidebar')
            @include('backend.setting.sidebar')
            @include('backend.download.sidebar')
            @include('backend.language.sidebar')
            @include('backend.statistics.sidebar')
            @include('backend.redirects.sidebar')
            <div class="menu-item">
                <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">{{trans('backend.menu.users')}}</h4>
            </div>
            @include('backend.user.sidebar')
            @include('backend.user_wallet.sidebar')
            @include('backend.contact_us.sidebar')
            @include('backend.seller.sidebar')
            @include('backend.ticket.sidebar')
            @include('backend.cart.sidebar')
            @include('backend.wishlist.sidebar')
            @include('backend.review.sidebar')
            @include('backend.management.sidebar')


        </div>
    </div>
</div>

