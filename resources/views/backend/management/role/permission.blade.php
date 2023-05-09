
@if( count($PermissionsGroup))
    <div class="card">

        <div class="card-body">

            <div class="fv-row">

                <!--begin::Table wrapper-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <!--begin::Table body-->
                        <tbody class="text-gray-600 fw-bold">
                        <!--begin::Table row-->
                        <tr>
                            <td class="text-gray-800 w-250px">
                                <label class="fs-5 fw-bolder form-label mb-2">{{trans('backend.role.role_permission')}}</label>


                            </td>
                            <td >
                                <!--begin::Checkbox-->
                            {{--                            <label class="form-check form-check-custom form-check-solid me-9">--}}
                            {{--                                <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all">--}}
                            {{--                                <span class="form-check-label" for="kt_roles_select_all">Select all</span>--}}
                            {{--                            </label>--}}
                            <!--end::Checkbox-->
                            </td>
                        </tr>
                        <!--end::Table row-->
                        @foreach($PermissionsGroup as  $key => $item)
                            <!--begin::Table row-->
                            <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800 w-250px"><b>{{$key+1}}. </b> {{$item->name}}</td>
                                <!--end::Label-->
                                <!--begin::Options-->
                                <td>
                                    <!--begin::Wrapper-->
                                    <div class="d-flex">
                                        @foreach($item->permissions() as $permission)
                                            <label
                                                class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                <input class="form-check-input" type="checkbox" value="{{$permission->id}}"
                                                       @if(!empty($role) && $role->hasPermissionTo($permission->id))
                                                       checked
                                                       @endif
                                                       name="permission[]">
                                                <span class="form-check-label">{{$permission->name}}</span>
                                            </label>
                                        @endforeach

                                    </div>
                                    <!--end::Wrapper-->
                                </td>
                                <!--end::Options-->
                            </tr>
                            <!--end::Table row-->
                        @endforeach

                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Table wrapper-->
            </div>
        </div>
    </div>
@else
    {{--@include('backend.shared.not_found_items')--}}
@endif
