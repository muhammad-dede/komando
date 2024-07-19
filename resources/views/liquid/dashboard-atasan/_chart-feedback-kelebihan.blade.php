@push('styles')
    <style>
        canvas{
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
        #kelebihan-tooltip {
            opacity: 1;
            position: absolute;
            background: rgba(0, 0, 0, .7);
            color: white;
            border-radius: 3px;
            -webkit-transition: all .1s ease;
            transition: all .1s ease;
            pointer-events: none;
            -webkit-transform: translate(-50%, 0);
            transform: translate(-50%, 0);
        }

        .kelebihan-tooltip-key {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 10px;
        }
    </style>
@endpush

<canvas id="kelebihan" height="250px"></canvas>
<div id="kelebihan-legend-con" class="legend-con"></div>

@push('scripts')
    <script>
        Chart.defaults.global.pointHitDetectionRadius = 1;

        var kelebihanTooltips = function(tooltip) {
            // Tooltip Element
            var tooltipEl = document.getElementById('kelebihan-tooltip');

            if (!tooltipEl) {
                tooltipEl = document.createElement('div');
                tooltipEl.id = 'kelebihan-tooltip';
                tooltipEl.innerHTML = '<table></table>';
                this._chart.canvas.parentNode.appendChild(tooltipEl);
            }

            // Hide if no tooltip
            if (tooltip.opacity === 0) {
                tooltipEl.style.opacity = 0;
                return;
            }

            // Set caret Position
            tooltipEl.classList.remove('above', 'below', 'no-transform');
            if (tooltip.yAlign) {
                tooltipEl.classList.add(tooltip.yAlign);
            } else {
                tooltipEl.classList.add('no-transform');
            }

            function getBody(bodyItem) {
                return bodyItem.lines;
            }

            // Set Text
            if (tooltip.body) {
                var titleLines = tooltip.title || [];
                var bodyLines = tooltip.body.map(getBody);

                var innerHtml = '<thead>';

                titleLines.forEach(function(title) {
                    innerHtml += '<tr><th>' + title + '</th></tr>';
                });
                innerHtml += '</thead><tbody>';

                bodyLines.forEach(function(body, i) {
                    var colors = tooltip.labelColors[i];
                    var style = 'background:' + colors.backgroundColor;
                    style += '; border-color:' + colors.borderColor;
                    style += '; border-width: 2px';
                    var span = '<span class="kelebihan-tooltip-key" style="' + style + '"></span>';
                    innerHtml += '<tr><td>' + span + body + '</td></tr>';
                });
                innerHtml += '</tbody>';

                var tableRoot = tooltipEl.querySelector('table');
                tableRoot.innerHTML = innerHtml;
            }

            var positionY = this._chart.canvas.offsetTop;
            var positionX = this._chart.canvas.offsetLeft;

            // Display, position, and set styles for font
            tooltipEl.style.opacity = 1;
            tooltipEl.style.left = positionX + tooltip.caretX + 'px';
            tooltipEl.style.top = positionY + tooltip.caretY + 'px';
            tooltipEl.style.fontFamily = tooltip._bodyFontFamily;
            tooltipEl.style.fontSize = tooltip.bodyFontSize + 'px';
            tooltipEl.style.fontStyle = tooltip._bodyFontStyle;
            tooltipEl.style.padding = tooltip.yPadding + 'px ' + tooltip.xPadding + 'px';
        };

        let labelKelebihan = @php echo json_encode($dataChart['kelebihan_terbanyak_label']); @endphp;
        let dataKelebihan = @php echo json_encode($dataChart['voter_kelebihan_terbanyak']); @endphp;

        var myChart = new Chart(document.getElementById('kelebihan'), {
            type: 'pie',
            animation:{
                animateScale:true
            },
            data: {
                labels: labelKelebihan,
                datasets: [{
                    label: 'Pengguna Baru',
                    data: dataKelebihan,
                    backgroundColor: [
                        "#6F52ED",
                        "#FFB800",
                        "#FF4C61",
                        "#33D69F",
                        "#000000",
                    ]
                }]
            },
            options: {
                responsive: true,
                legend: false,
                legendCallback: function(chart) {
                    var legendHtml = [];
                    legendHtml.push('<ul>');
                    var item = chart.data.datasets[0];
                    var count = 0;
                    if(item.data.length >= 3) {
                        count = 3;
                    }
                    for (var i=0; i < count ; i++) {
                        legendHtml.push('<li>');
                        legendHtml.push('<span class="chart-legend" style="background-color:' + item.backgroundColor[i] +'"></span>');
                        legendHtml.push('<span class="chart-legend-label-text">' + chart.data.labels[i]+ ' - <b>' +item.data[i] +'</b> </span>');
                        legendHtml.push('</li>');
                    }

                    legendHtml.push('</ul>');
                    return legendHtml.join("");
                },
                tooltips: {
                    enabled: false,
                    mode: 'index',
                    position: 'nearest',
                    custom: kelebihanTooltips
                },
            }
        });

        $('#kelebihan-legend-con').html(myChart.generateLegend());

    </script>
@endpush
