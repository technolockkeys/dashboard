

<form action="{{route('backend.countries.change.zone', $country->id)}}" method="post" id="zone_update">
    @csrf
    <div class=" form-group">
        <label for="zone_id" class="col-form-label form-label">{{trans('backend.country.zone_id')}}</label>
        <select class="form-control" name="zone_id" data-control="select2" id="zone_id">
            @for($i = 1; $i <= 10; $i++)
                <option value="{{$i}}" @if($country->zone_id == $i) selected @endif>{{$i}}</option>
            @endfor
        </select>
    </div>
    <div class="modal-footer flex-center">
        <button type="submit" class="btn btn-primary">
        {{trans('backend.global.save')}} </button>
    </div>
</form>
