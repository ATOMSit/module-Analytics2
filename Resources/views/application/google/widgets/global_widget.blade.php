@push('scripts')
    <script src="{{asset('application/vendors/custom/chartjs/Chart.js')}}"></script>
    <script>
        var ctx = document.getElementById("canvas").getContext('2d');
        var my_json = @json($stats);
        var date = [];
        var visitors = [];
        var pageViews = [];
        for (var i in my_json) {
            date.push(my_json[i]['date']);
            visitors.push(my_json[i]['visitors']);
            pageViews.push(my_json[i]['pageViews'])
        }
        var a = {
            labels: date,
            datasets: [{
                label: "@lang('analytics::google_translation.widgets.global.views.visitors')",
                fill: !0,
                backgroundColor: "rgba(168, 183, 255, 0.76)",
                borderColor: "#a8b7ff",
                pointHoverRadius: 0,
                pointHoverBorderWidth: 0,
                pointBackgroundColor: "rgba(168, 183, 255, 0)",
                pointBorderColor: "rgba(168, 183, 255, 0)",
                pointHoverBackgroundColor: "#5d78ff",
                pointHoverBorderColor: "#000000",
                data: visitors
            }, {
                label: "@lang('analytics::google_translation.widgets.global.views.number_printing')",
                fill: !0,
                backgroundColor: "rgba(93, 120, 255, 0.76)",
                borderColor: "#5d78ff",
                pointHoverRadius: 4,
                pointHoverBorderWidth: 12,
                pointBackgroundColor: "rgba(168, 183, 255, 0)",
                pointBorderColor: "rgba(168, 183, 255, 0)",
                pointHoverBackgroundColor: "#5d78ff",
                pointHoverBorderColor: "#000000",
                data: pageViews
            }]
        };
        new Chart(ctx, {
            type: "line", data: a, options: {
                responsive: true,
                maintainAspectRatio: !1,
                legend: !1,
                scales: {
                    xAxes: [{
                        categoryPercentage: .35,
                        barPercentage: .7,
                        display: !0,
                        scaleLabel: {display: !1, labelString: "Month"},
                        gridLines: !1,
                        ticks: {display: !0, beginAtZero: !0, fontColor: "#646c9a", fontSize: 13, padding: 10}
                    }],
                    yAxes: [{
                        categoryPercentage: .35,
                        barPercentage: .7,
                        display: !0,
                        scaleLabel: {display: !1, labelString: "Value"},
                        gridLines: {
                            color: "#afb4d4",
                            drawBorder: !1,
                            offsetGridLines: !1,
                            drawTicks: !1,
                            borderDash: [3, 4],
                            zeroLineWidth: 1,
                            zeroLineColor: "#afb4d4",
                            zeroLineBorderDash: [3, 4]
                        },
                        ticks: {display: !0, beginAtZero: !0, fontColor: "#646c9a", fontSize: 13, padding: 10}
                    }]
                },
                title: {display: !1},
                hover: {mode: "index"},
                tooltips: {
                    enabled: !0,
                    intersect: !1,
                    mode: "nearest",
                    bodySpacing: 5,
                    yPadding: 10,
                    xPadding: 10,
                    caretPadding: 0,
                    displayColors: !1,
                    backgroundColor: "#5d78ff",
                    titleFontColor: "#ffffff",
                    cornerRadius: 4,
                    footerSpacing: 0,
                    titleSpacing: 0
                },
                layout: {padding: {left: 0, right: 0, top: 5, bottom: 5}}
            }
        })
    </script>
@endpush

<div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                @lang('analytics::google_translation.widgets.global.views.title')
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body kt-portlet__body--fluid">
        <div class="kt-widget12">
            <div class="kt-widget12__content">
                <div class="kt-widget12__item">
                    <div class="kt-widget12__info">
                        <span class="kt-widget12__desc">
                            @lang('analytics::google_translation.widgets.global.views.bounce_rate')
                        </span>
                        <span class="kt-widget12__value">
                            {{$bounce_rate}}%
                        </span>
                    </div>

                    <div class="kt-widget12__info">
                        <span class="kt-widget12__desc">
                            @lang('analytics::google_translation.widgets.global.views.avg_time')
                        </span>
                        <span class="kt-widget12__value">
                            {{$avgSessionDuration}} @lang('analytics::google_translation.widgets.global.views.minute')
                        </span>
                    </div>
                </div>
                <div class="kt-widget12__item">
                    <div class="kt-widget12__info">
                        <span class="kt-widget12__desc">
                            @lang('analytics::google_translation.widgets.global.views.number_printing')
                        </span>
                        <span class="kt-widget12__value">
                            {{$page_views}}
                        </span>
                    </div>
                    <div class="kt-widget12__info">
                        <span class="kt-widget12__desc">
                            @lang('analytics::google_translation.widgets.global.views.direct_users')
                        </span>
                        <div class="kt-widget12__progress">
                            <div class="progress kt-progress--sm">
                                <div class="progress-bar kt-bg-brand" role="progressbar" style="width:{{$direct_rate}}%;" aria-valuenow="{{$direct_rate}}" aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                            <span class="kt-widget12__stat">
                                {{$direct_rate}}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-widget12__chart" style="height:250px;">
                <div class="chartjs-size-monitor">
                </div>
                <canvas id="canvas" width="739" height="250"></canvas>
            </div>
        </div>
    </div>
</div>