<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.most_visited_pages')}}</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                <thead>
                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th>{{trans('backend.global.id')}}</th>
                    <th>{{trans('backend.statistic.page_title')}}</th>
                    <th>{{trans('backend.statistic.page_views')}}</th>
                    <th>{{trans('backend.statistic.url')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($analyticsData as $key => $page )
                <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$page['pageTitle']}}</td>
                    <td>{{$page['pageViews']}}</td>
                    <td><a href="{{url($page['url'])}}"><span class="badge badge-sm badge-light-primary">{{url($page['url'])}}</span></a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
