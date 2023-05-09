<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.top_selling_categories')}}</h3>
        </div>
    </div>

    <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
        <div class="row">

            <div class="col-6 col-md-3 col-lg-3 col-xl-3 form-group">
                <label for="reports" class="col-form-label form-label">{{trans('backend.statistic.reports')}}</label>
                <select class="form-control get_category" name="category" data-control="select2"
                        id="category">
                    <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                    @foreach($categories as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row" id="chart_wrapper">

        </div>
    </div>
</div>
<script>
    $("#category").select2();
</script>
