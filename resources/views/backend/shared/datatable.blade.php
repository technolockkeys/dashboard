<script>
    var dt{{$name}};

    $(document).ready(() => {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            let deleteButton = '';
            @if($delete_all_route != null)
            let deleteButtonTrans = '{{ trans('backend.global.datatables.delete_all') }}';
            deleteButton = {
                text: deleteButtonTrans,
                className: 'btn-danger delete-all',
                {{--action: function (e, dt, node, config) {--}}
                {{--    var ids = $.map(dt{{$name}}.rows({selected: true}).data(), function (entry) {--}}
                {{--        return entry.id--}}
                {{--    });--}}
                {{--    $.ajax({--}}
                {{--        headers: {'x-csrf-token': "{{ csrf_token() }}"},--}}
                {{--        method: 'POST',--}}

                {{--        url: "{{$delete_all_route}}",--}}
                {{--        data: { ids: ids }})--}}
                {{--        .done(function () {--}}
                {{--            dt{{$name}}.ajax.reload()--}}
                {{--        })--}}
                {{--}--}}
            }
            dtButtons.push(deleteButton)
            @endif
            let colvisButtonTrans = '{{ trans('backend.global.view_columns') }}'
            let excelButtonTrans = '{{ trans('backend.global.excel') }}'
            let pdfButtonTrans = '{{ trans('backend.global.pdf') }}'
            let pageLength = '{{ trans('backend.global.pageLength') }}'

            var route = "{{$route}}";
            var datatable_id = '{{isset($id) ?  "#".$id : "#datatable"}}';
            var datatable_method = "{{isset($method)?  $method : "post"}}";
            dt{{$name}} = $(datatable_id).DataTable({

                stateSave: true,


                processing: true,
                serverSide: true,
                "scrollX": true,
                @if(!$search)
                "searching": false,
                @endif
                iDisplayLength: 10,
                aLengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "All"]
                ],
                retrieve: true,
                dom: 'lfBrtip <"actions">',
                lengthChange: false,
                @if($delete_all_route != null && !auth('seller')->check())

                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },


                @elseif(auth('seller')->check())
                columnDefs: [
                    {
                        searchable: false,
                        orderable: false,
                        targets: 0,
                    },
                ],
                @endif
                buttons:
                    [
                            @if( $export )
                        {
                            extend: 'excel',
                            className: 'btn btn-primary btn-sm mr-1 ml-1',
                            text: excelButtonTrans,
                        },
                        {
                            extend: 'pdfHtml5',
                            className: 'btn btn-info btn-sm mr-1 ml-1',
                            text: pdfButtonTrans,
                            exportOptions: {
                                columns: ':visible',
                                selected: true
                            },

                            // if(dt{{$name}}.columns(':visible').count()>10)
                            orientation: 'landscape',
                            // pageSize: 'a2'

                        },
                        {
                            extend: 'colvis',
                            className: 'btn btn-dark btn-sm mr-1 ml-1',
                            text: colvisButtonTrans,
                            exportOptions: {
                                columns: ':visible',
                                // selected: true
                            }
                        },
                        {
                            extend: 'pageLength',
                            className: 'btn btn-success btn-sm mr-1 ml-1',
                            text: pageLength,
                            exportOptions: {
                                columns: ':visible'
                            }
                        }

                        @if($delete_all_route != null)
                        ,
                        dtButtons
                        @endif
                        @endif
                    ],

                ajax:
                    {
                        url: route,
                        method:
                        datatable_method,
                        data:
                            (d) => {
                                d._token = "{{ csrf_token() }}";
                                @if(isset($data_send) && !empty($data_send))
                                    @foreach($data_send as $key=>$value)
                                    d.{{$key}} = "{{$value}}";
                                @endforeach
                                    @endif

                                    @if(!empty($filters))
                                    @foreach($filters as $item)

                                    d.{{$item}} = $("#{{$item}}").val();


                                @endforeach
                                @endif

                            }
                    }
                ,
                search: {
                    "regex":
                        true
                }
                ,
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                }
                ,
                @if(isset($data_columns) && !empty($data_columns))
                columns: [

                        @foreach($data_columns as $key=>$value)
                    {
                        data: "{{{$key}}}", name: "{{$value}}"
                        @if(!empty($stop_search_column) && in_array($key ,$stop_search_column ) )
                        , "searchable": false
                        @endif


                    },
                    @endforeach
                ],
                @endif

                stateSaveCallback: function (settings, data) {
                    // Send an Ajax request to the server with the state object
                    $.ajax({
                        "url": "{{route('datatable.set')}}",
                        "data": {data: data, page: route, _token: "{{csrf_token()}}"},
                        "dataType": "json",
                        "type": "POST",
                        "success": function (response) {

                        }
                    });
                },
                stateSaveParams: function (settings, data) {

                    delete data.search;
                    data.start = parseInt(data.start);

                    // delete data.paging;
                    // delete data.order;
                    console.log(data)
                },
                stateLoadCallback: function (settings, callback) {

                    $.ajax({
                        url: '{{route('datatable.get')}}',
                        async: false,
                        data: {page: route, _token: "{{csrf_token()}}"},
                        "type": "POST",
                        success: function (response) {
                            var data = response.data;
                            res= data ;
                            if (data != null) {


                                data.time = new Date().getTime();
                                for (const column in data.columns) { // change column visibility from string to bool
                                    if (data.columns[column].visible == "true") {
                                        data.columns[column].visible = true;
                                    } else {
                                        data.columns[column].visible = false;
                                    }
                                }
                                callback(data);
                            }


                        }

                    });

                },


            });
            $("#select_all").on("click", function (e) {
                if ($(this).is(":checked")) {
                    dt{{$name}}.rows().select();
                } else {
                    dt{{$name}}.rows().deselect();
                }
            });
            @if(!empty($filters))
            $("#apply_filter").on('click', function () {
                dt{{$name}}.ajax.reload()
            });
            @endif
            @if(auth('seller')->check())
            dt{{$name}}.on('order.dt search.dt', function () {
                dt{{$name}}.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                    dt{{$name}}.cell(cell).invalidate('dom');
                });
            }).draw();
            @endif
            setTimeout(() => {
                $('#dt{{$name}} thead').addClass('w-100');
            }, 1000)
        }
    );

    $(document).on('click', ".delete-all", function () {
        var message = '{{trans('backend.global.datatables.you_sure_to_delete_selected_items?')}}';
        var route = '{{$delete_all_route}}';
        var token = '{{csrf_token()}}';
        var deleted = $(this).data('deleted');
        var ids = $.map(dt{{$name}}.rows({selected: true}).data(), function (entry) {
            return entry.id
        });
        Swal.fire({
            html: message,
            icon: "warning",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: 'No',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            }
        },).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({

                    url: route,
                    method: "post",
                    data: {
                        _token: token,
                        ids: ids,
                    },
                    success: function (respose) {
                        success_message(respose.data.message)
                        if (typeof dt{{$name}} !== "undefined") {
                            dt{{$name}}.ajax.reload();
                        } else {
                            // alert(123)
                        }
                    }, error: function (xhr, status, error) {
                        var response = JSON.parse(xhr.responseText)
                        error_message(response.message);
                    },
                })
            }
        });

    });

</script>
