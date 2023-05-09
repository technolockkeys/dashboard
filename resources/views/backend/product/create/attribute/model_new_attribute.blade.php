 <div style="display: none"  id="create_new_attribute_body" class="card mt-3 border-secondary border-1 border-dashed">
     <form id="form_add_new_attribute">
         @csrf
         <div class="card-header "><h3 class="card-title">  {{trans('backend.product.Create_new_attribute')}}</h3>
             <div class="card-toolbar">
                 <button type="button" class="btn ms-2 me-2 btn-icon btn-sm btn-success" id="save_new_attributes">
                     <i class="fa fa-save"></i>
                 </button>
                 <button type="button" class="btn btn-icon btn-sm btn-light" id="close_new_attributes">
                     <i class="fa fa-times"></i>
                 </button>

             </div>
         </div>
         <div class="card-body">
             <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                 @foreach(get_languages() as $key => $item)
                     <li class="nav-item">
                         <a class="nav-link  {{$key == 0 ? "active" : ""}}" data-bs-toggle="tab"
                            href="#new_attr_lang{{$item->code}}">{{$item->language}}</a>
                     </li>
                 @endforeach

             </ul>
             <div class="tab-content" id="new_attr_tab">
                 @foreach(get_languages() as $key => $language)
                     <div class="tab-pane fade show  {{$key == 0 ? "active" : ""}}"
                          id="new_attr_lang{{$language->code}}" role="tabpane{{$key}}">
                         <div class="row">
                             <div class="col">
                                 <div class="form-group">
                                     <label class="form-label "
                                            for="new_attr_{{$language->code}}">{{trans('backend.attribute.name')}}</label>
                                     <input type="text" class="form-control" id="new_attr_{{$language->code}}"
                                            name="new_attr_{{$language->code}}" value="">
                                     <b id="message_error_new_attr_{{$language->code}}" class="text-danger"> </b>
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col form-group ">
                                 <label class="form-label" for="image">{{trans('backend.attribute.image')}}</label>
                                 <br>
                                 {!! single_image('img_new_attr_'.$language->code , media_file('-1')  ,'' ) !!}
                                 <br>
                                 <b id="message_error_new_attr_{{$language->code}}" class="text-danger">  </b>

                             </div>

                         </div>

                     </div>
                 @endforeach
             </div>
         </div>

     </form>
 </div>
