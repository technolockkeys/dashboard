
<div class="card   flex-row-fluid mb-2  ">
    <div class="card-header">
        <h3 class="card-title"> {{trans('backend.menu.cards')}}</h3>
    </div>
    <!--begin::Card Body-->
    <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
        <div class="table-responsive">
            <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                <thead>
                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th>{{trans('backend.global.id')}}</th>
                    <th>{{trans('backend.card.last_four')}}</th>
                    <th>{{trans('backend.card.brand')}}</th>
                    <th>{{trans('backend.card.is_default')}}</th>
                    <th>{{trans('backend.global.created_at')}}</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <!--end::Card Body-->
</div>

{!! datatable_script() !!}
{!! $datatable_script !!}
