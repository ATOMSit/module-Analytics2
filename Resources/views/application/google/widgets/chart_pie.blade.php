@push('scripts')
    <script type="text/javascript">
        window.addEventListener("load", function () {
            var datas = @json($datas);
            var labels = @json($labels);
            var colors = @json($colors);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: datas,
                        backgroundColor: colors
                    }],
                    labels: labels
                },
                options: {
                    responsive: true
                }
            };
            var div = document.getElementById('{{$id}}').getContext('2d');
            window.myPies = new Chart(div, config);
        }, false);
    </script>
@endpush

<div class="kt-portlet kt-portlet--bordered-semi kt-portlet--height-fluid">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                @lang("analytics::google_translation.widgets.$widget.views.title")
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-widget4">
            <canvas id="{!! $id !!}"></canvas>
        </div>
    </div>
</div>
