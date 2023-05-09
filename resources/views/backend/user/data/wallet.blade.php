<div class="row">

    @foreach($statistics as $element)
        @php
            $svg = $element['svg'] ;
            $number= $element['number'];
            $name=$element['name'];
            $sum=$element['sum'];
        @endphp
        <div class="col-xl-3 col-6 mb-1">
            <!--begin::Statistics Widget 5-->
            <div class="card bg-body   card-xl-stretch mb-xl-8">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                    <span class="svg-icon svg-icon-primary   ms-n1">
		       <img style='width: 10%' src="{{$svg}}"/>
												</span>
                    <!--end::Svg Icon-->
                    <div class="text-gray-900 fw-bolder fs-2 mb-2 mt-5">{{$sum}}</div>
                    <div class="fw-bold text-gray-400">{{$name}}
                        @if($number != '-')
                            <span class="badge badge-light-success fs-base">
                            {{$number}}</span>
                        @endif
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Statistics Widget 5-->
        </div>

        {{--        <div class=" col-12 col-md mb-6">--}}

        {{--            <div class="card h-lg-80">--}}
        {{--                <!--begin::Body-->--}}
        {{--                <div class="card-body d-flex justify-content-between align-items-start flex-column">--}}
        {{--                    <!--begin::Icon-->--}}
        {{--                    <div class="w-50 h-25">--}}
        {{--                        <img style='width: 50%' src="{{$svg}}"/>--}}
        {{--                    </div>--}}
        {{--                    <div class="d-flex flex-column my-7">--}}
        {{--                        <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">{{$sum}}</span>--}}
        {{--                        <div class="m-0">--}}
        {{--                            <span class="fw-semibold fs-6 text-gray-400">{{$name}}</span>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                    --}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    @endforeach

</div>
<div class="row mt-10">
    <div class="col">
        <div class="card  card-flush   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.user.wallet')}}</h3>
                <div class="card-toolbar">
                    @if(permission_can('change user balance','admin'))
                        <button class="btn btn-sm btn-primary change_balance_wallet" type="button"
                                data-user="{{$user->id}}">{{trans('backend.wallet.change_balance')}}</button>
                    @endif

                    <button id="send_reminder"
                            class="btn btn-info btn-sm ms-1"><i class="las la-bell"></i> {{trans('backend.user.send_reminder')}}</button>

                    <button id="send_account_statement" class="btn btn-primary  btn-sm  ms-1"><i class="las la-file-invoice"></i> {{trans('backend.user.send_account_statement')}}</button>
                </div>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-row-bordered gy-5 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th>{{trans('backend.global.id')}}</th>
                            <th>{{trans('backend.order.amount')}}</th>
                            <th>{{trans('backend.order.type')}}</th>
                            <th>{{trans('backend.order.order')}}</th>
                            <th>{{trans('backend.global.status')}}</th>
                            <th>{{trans('backend.order.note')}}</th>
                            <th>{{trans('backend.global.created_at')}}</th>
                            <th>{{trans('backend.global.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!--end::Card Body-->
            </div>
        </div>
    </div>
</div>
@include('backend.user.data.payment.create')

{!! $datatable_script !!}

