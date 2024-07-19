@php($isDetailPenilaian = \Illuminate\Support\Facades\Route::currentRouteName() === 'dashboard-atasan.history-penilaian.show')
<div class="row {{ $isDetailPenilaian ? '' : 'm-t-20' }}">
    <div class="col-md-12">
        <div class="card-box">
			@if (!$isDetailPenilaian)
            <div class="title-top mar-b-1rem">Activity Log Book Atasan</div>
			@endif
            <table id="table-activity" class="table table-striped table-bordered">
                <thead class="thead-blue">
                <tr>
                    <th class="color-white vertical-middle">Resolusi</th>
                    <th class="color-white vertical-middle" style="width: 80px;">Nama Kegiatan/Perbaikan</th>
                    <th class="color-white vertical-middle" style="min-width: 120px;">Tanggal Pelaksanaan</th>
                    <th class="color-white vertical-middle">Tempat Pelaksanaan</th>
                    <th class="color-white vertical-middle">Deskripsi Kegiatan</th>
                    </th>
                </tr>
                </thead>
				<tbody>
                @foreach(isset($atasanActivityLogBook) ? $atasanActivityLogBook : app(\App\Services\ActivityLog::class)->getActiveLogsForAtasan(auth()->user()) as $log)
                    <tr>
                        <td>
                            <ul>
                                @foreach($log->getResolusi() as $resolusi)
                                    <li>{{ $resolusi }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $log->nama_kegiatan }}</td>
                        <td>
                            <div> <strong>{{ \Carbon\Carbon::parse($log->start_date)->format('d-m-Y') }}</strong> </div>
                            s/d
                            <div> <strong>{{ \Carbon\Carbon::parse($log->end_date)->format('d-m-Y') }}</strong> </div>
                        </td>
                        <td>{{ $log->tempat_kegiatan }}</td>
                        <td>{!! $log->keterangan !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
