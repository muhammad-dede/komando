<canvas id="lines"></canvas>
<div class="row" style="margin-bottom: 1rem">
    <div class="col-md-4">
        <button type="button" class="btn btn-primary btn-block" onclick="getScreenshotLineChart()">
            <em class="fa fa-download"></em>
            Export Chart
        </button>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {

            var ctx = document.getElementById("lines").getContext("2d");

            var lineChartData = {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: "{{ isset($labelChart) ? $labelChart : '' }}",
                        fillColor: "rgba(220,220,220,0.2)",
                        strokeColor: "rgba(220,220,220,1)",
                        lineTension: 0.2,
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: {!! json_encode(array_values($counter)) !!}
                    }
                ]
            };

            var ctx = document.getElementById("lines").getContext("2d");

            var myPieChart = new Chart(ctx,{
                type: 'line',
                data: lineChartData,
                options: {
                    bezierCurve : false,
                    legend: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                maxTicksLimit: 10,
                            },
                        }]
                    }
                }
            });
        });

        function getScreenshotLineChart() {
            var printWindow = window.open('','PrintWindow', 'width=400,height=200');
            html2canvas(document.getElementById('lines')).then(function (canvas) {
                var doc = printWindow.document;
                var img = doc.createElement('img');
                img.src = canvas.toDataURL('image/png');
                doc.body.appendChild(img);
                setTimeout(function () {
                    printWindow.print();
                    printWindow.close();
                }, 0);
            });
        }
    </script>
@endpush
