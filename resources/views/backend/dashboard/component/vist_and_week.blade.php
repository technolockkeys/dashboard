<div class="col-12 col-md-6 col-xl-3 col-lg-4 ">
    <div class="card card-xl-stretch mb-xl-8">
        <!--begin::Body-->
        <div class="card-body d-flex flex-column p-0">
            <div class="d-flex flex-stack flex-grow-1 card-p">
                <div class="d-flex flex-column me-2">
                    <a href="#"
                       class="text-dark text-hover-primary fw-bolder fs-3">{{__('backend.dashboard.visits_last_week')}}</a>
                </div>
            </div>
            <div class="statistics-widget-3-chart card-rounded-bottom" id="chart" data-kt-chart-color="success"
                 style="height: 150px"></div>
        </div>
    </div>
    @include('backend.dashboard.component.order_type')
</div>
<div class="col-12 col-md-6 col-xl-3     col-lg-6 col-xl-3  ">
    <div class="card card-flush">
        <div class="card-header ">
            <h3  class="card-title"> {{trans('backend.dashboard.seller_earning')}}
                <sub>
                    (  {{trans('backend.dashboard.seller_earning_date',['from'=> \Carbon\Carbon::now()->startOfMonth()->format('y/m/d'), 'to' =>\Carbon\Carbon::now()->format('y/m/d')])}} ) </sub>
            </h3>
        </div>
        <div class="card-body">
            <div id="seller_charts_widget_"  ></div>
        </div>
    </div>

</div>

