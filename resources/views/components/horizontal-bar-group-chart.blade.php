<canvas id="chartes"></canvas>
<div class="row" style="margin-bottom: 1rem">
    <div class="col-md-4">
        <button type="button" class="btn btn-primary btn-block" onclick="getScreenshotBarChart()">
            <em class="fa fa-download"></em>
            Export Chart
        </button>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('vendor/chartjs/dist/Chart.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    <script>
        $(document).ready(function () {
            Chart.defaults.global.elements.rectangle.pointStyle = 'circle'

            var marksCanvas = document.getElementById("chartes");
            let datas = [
             {
                label: 'Actual',
                backgroundColor: '#92d400',
                data: [5200, 7245]
            }];

            var horizontalChart = new Chart(marksCanvas, {
                type: 'horizontalBar',
                data: {!! json_encode($data) !!},
                plugins: [ChartDataLabels],
                options: {
                    legend: {
                        display: false,
                    },
                    plugins: {
                        datalabels: {
                            anchor: 'center',
                            clamp: true,
                            align: 'center',
                            color: '#000000',
                            font: {
                                weight: 'bold',
                                size: 12,
                            },
                            formatter: (value, ctx) => {
                                let sum = "{{ $voter }}";
                                let percentage = (value*100 / parseInt(sum)).toFixed(2)+"%";
                                return percentage;
                            },
                            color: '#000',
                        }
                    },
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

        function getScreenshotBarChart() {
            var printWindow = window.open('','PrintWindow', 'width=400,height=200');
            html2canvas(document.getElementById('chartes')).then(function (canvas) {
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
