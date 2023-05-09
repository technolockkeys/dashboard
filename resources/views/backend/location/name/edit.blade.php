

<form action="{{route('backend.countries.update_name', $country->id)}}" method="post" id="update_name">
    @csrf

        <div class="card-body">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                @foreach(get_languages() as $key=> $item)
                    <li class="nav-item">
                        <a class="nav-link  @if($key == 0 ) active @endif" data-bs-toggle="tab"
                           href="#{{$item->code}}">{{$item->language}}</a>
                    </li>
                @endforeach

            </ul>
            <div class="tab-content" id="myTabContent">
                @foreach(get_languages() as $key=> $item)
                    <div class="tab-pane fade   @if($key == 0 )show active @endif" id="{{$item->code}}"
                         role="tabpanel">
                        <div class="row mb-6">

                            <div class="mb-10">
                                <label for="name_{{$item->code}}"
                                       class="required form-label">{{trans('backend.country.name')}}</label>
                                <input required autocomplete="off" type="text" class="form-control "
                                       id="name_{{$item->code}}" name="name_{{$item->code}}"
                                       value="{{old('name_'.$item->code,$country->getTranslation('name', $item->code))}}"
                                       placeholder="{{trans('backend.country.name')}}"/>
                                @error('name_'.$item->code) <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    <div class="modal-footer flex-center">
        <button type="submit" class="btn btn-primary">
            {{trans('backend.global.save')}} </button>
    </div>
</form>
