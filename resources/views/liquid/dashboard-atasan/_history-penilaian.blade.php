<div class="row m-t-20">
    <div class="col-md-12 col-xs-12">
        <div class="card-box">
            <div class="title-top mar-b-1rem">History Penilaian</div>
            <div class="table-responsive">
                <table class="datatable table table-striped table-bordered">
                    <thead class="thead-blue">
                    <tr>
                        <th class="color-white vertical-middle" style="min-width: 40px;">Tahun</th>
                        <th class="color-white vertical-middle" style="width: 80px;">Jabatan</th>
                        <th class="color-white vertical-middle" style="min-width: 120px;">Unit</th>
                        <th class="color-white vertical-middle">Resolusi</th>
                        <th class="color-white vertical-middle">Aksi</th>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @php($historiPenilaian = app(\App\Services\LiquidService::class)->getHistoryPenilaianAtasan(auth()->user()))
                        @foreach ($historiPenilaian as $index => $penilaian)
                            <tr>
                                <td>{{ $penilaian['liquid']->feedback_start_date->format('Y') }}</td>
                                <td>{{ $penilaian['atasan']['jabatan'] }}</td>
                                <td>
									{{ $penilaian['atasan']['business_area']->business_area
										.' - '.$penilaian['atasan']['business_area']->description }}
                                </td>
                                <td>
                                    @if(count($penilaian['resolusi']) > 0)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col" width="75%">Resolusi</th>
                                            <th scope="col">Penilaian Pertama</th>
                                            <th scope="col">Penilaian Kedua</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($penilaian['resolusi'] as $item)
                                            <tr>
                                                <td>{{ $item['resolusi'] }}</td>
                                                <td>{{ $item['avg_pengukuran_pertama'] }}</td>
                                                <td>{{ $item['avg_pengukuran_kedua'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </td>
                                <td align="center">
                                    <a href="{{ url('dashboard-atasan/history-penilaian/show').'/'.$penilaian['liquid']->id }}" class="badge badge-primary" data-toggle="tooltip" title="Lihat Detail"><em
                                                class="fa fa-eye fa-2x"></em></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
