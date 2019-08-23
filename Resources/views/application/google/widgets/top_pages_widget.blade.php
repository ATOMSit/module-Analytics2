<div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                @lang('analytics::google_translation.widgets.top_pages.views.title')
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-section">
            <div class="kt-section__content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>
                            @lang('analytics::google_translation.widgets.top_pages.views.page')
                        </th>
                        <th>
                            @lang('analytics::google_translation.widgets.top_pages.views.visitors')
                        </th>
                        <th>
                            @lang('analytics::google_translation.widgets.top_pages.views.news_visitors')
                        </th>
                        <th>
                            @lang('analytics::google_translation.widgets.top_pages.views.avg_time')
                        </th>
                        <th>
                            @lang('analytics::google_translation.widgets.top_pages.views.bounce_rate')
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($top_pages as $page)
                        <tr>
                            <th scope="row">
                                <a href="{{url($page[1])}}">{{$page[0]}}</a>
                            </th>
                            <td>
                                {{$page[5]}}
                            </td>
                            <td>
                                {{$page[2]}}
                            </td>
                            <td>
                                {{$page[4]}}
                            </td>
                            <td>
                                {{$page[3]}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>