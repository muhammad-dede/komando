<div class="card-box">
    <canvas id="chart-penilaian-resolusi" height="250px"></canvas>

    <table class="table table-bordered">
        <tr>
            <th colspan="2">Keterangan</th>
        </tr>
        @foreach($resolusi as $item)
            <tr>
                <td width="150px">{{ $item['label'] }}</td>
                <td>{{ $item['resolusi'] }}</td>
            </tr>
        @endforeach
    </table>
</div>
@push('scripts')
    <script src="{{ asset('vendor/chartjs/dist/Chart.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            let resolusi			= {!! $resolusi->pluck('label')->toJson() !!};
            let avgPenilaianPertama	= {!! $resolusi->pluck('avg_pengukuran_pertama')->toJson() !!};
            let avgPenilaianKedua	= {!! $resolusi->pluck('avg_pengukuran_kedua')->toJson() !!};

            Chart.defaults.global.elements.rectangle.pointStyle = 'circle'

            var marksCanvas = document.getElementById("chart-penilaian-resolusi");
            var marksData = {
                labels: resolusi,
                datasets: [{
                    label: "Penilaian Pertama",
                    backgroundColor: "rgba(45, 156, 219, 0.4)",
                    data: avgPenilaianPertama
                }, {
                    label: "Penilaian Kedua",
                    backgroundColor: "rgba(235, 87, 87, 0.4)",
                    data: avgPenilaianKedua
                }]

            };

            var radarChart = new Chart(marksCanvas, {
                type: 'radar',
                data: marksData,
                options: {
                    scale: {
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            min: 0,
                            max: 10
                        },
                    },
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: true,
                        },
                    }
                }
            });
        });
    </script>
@endpush
