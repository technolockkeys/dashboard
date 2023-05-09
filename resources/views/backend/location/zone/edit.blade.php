@extends('backend.layout.app')
@section('title',trans('backend.menu.zones').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($zone, array('method' => 'PUT', 'class'=>'form   card-body' , 'route' => array('backend.zones.update', $id))) }}
        @csrf
        <div class="card flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.zone.edit', ['number' => $id])}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.zones.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
            </div>
            <div class="card-body">

                <div id="prices_table">
                    <div class="row mb-4">
                        <div class='col-12 col-md-5'>
                            <div class='form-group'>
                                <b>{{trans('backend.zone.weight')}}</b>
                            </div>
                        </div>
                        <div class='col-12 col-md-5'>
                            <div class='form-group'>
                                <b>{{trans('backend.zone.price')}}</b>
                            </div>
                        </div>
                    </div>
                    @php
                        $zones  =$zone;
                        if(count($zones)  != 0){

                        foreach ($zones as $zone ){
                            $zone_weights[]= $zone->weight ;
                            $zone_prices[]= $zone->price ;
                        }
                        }
                    @endphp
                    @if(count($zones)  != 0)
                        @foreach($zone_weights as $key=> $zone_weight)
                            <div class="row mb-4" data-row="price_{{$key}}">
                                <div class='col-12 col-md-5'>
                                    <div class='form-group'>
                                        <input type='number' required step='0.01' class='form-control'
                                               name='weight[]' value="{{old('weight', $zone_weights)[$key]}}">
                                    </div>
                                </div>
                                <div class='col-12 col-md-5'>
                                    <div class='form-group'>
                                        <input type='number' required step='0.01' class='form-control'
                                               name='price[]' value="{{old('price', $zone_prices)[$key]}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-1">
                                    <div class="form-group">
                                        <button type='button' data-uuid='{{'price_'.$key}}'
                                                class='btn btn-danger btn-icon btn-sm remove-row'><i
                                                    class='fa fa-times'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="button" id="add_price"
                                class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">
                            <i class="fa fa-plus"></i> {{trans('backend.zone.add_price')}}
                        </button>

                    </div>
                </div>
                <div class=" form-group">
                    <div class="col-12 col-md-12 mt-3">

                        <div class="form-group mt-2">

                            <button class="btn btn-primary"
                                    type="submit">  {{trans('backend.global.save')}} </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{Form::close()}}
    </div>
@endsection

@section('script')
    <script>
        function uuidv4() {
            return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
            );
        }

        $(document).on('click', '#add_price', function () {
            var uuid = uuidv4();
            var html = "<div class='row mb-4' data-row='" + uuid + "'>" +

                "<div class='col-12 col-md-5'><div class='form-group'><input type='number' step='0.01'  class='form-control' name='weight[]'></div></div>" +
                "<div class='col-12 col-md-5'><div class='form-group'><input type='number' step='0.01'  class='form-control' name='price[]'></div></div>" +
                "<div class='col-12 col-md-1'><div class='form-group'><button type='button' data-uuid='" + uuid + "' class='btn btn-danger btn-icon btn-sm remove-row'><i class='fa fa-times'></i></button></div></div>" +
                "</div>";

            $("#prices_table").append(html)
        });

        $(document).on('click', ".remove-row", function () {
            var uuid = $(this).data('uuid');
            $("div[data-row='" + uuid + "']").remove();
        });

    </script>
@endsection
