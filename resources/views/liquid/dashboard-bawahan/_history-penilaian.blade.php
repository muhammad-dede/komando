@php($historiPenilaian = $liquidService->getHistoryPenilaianBawahan())
<div class="row m-t-20">
    <div class="col-md-12 col-xs-12 col-lg-12">
        <div class="card-box table-responsive">
            <div class="title-top mar-b-1rem">History Penilaian Atasan</div>
            <table class="datatable table table-striped table-bordered" id="table-history-penilaian">
                <thead class="thead-blue">
                <tr>
                    <th class="color-white vertical-middle" style="min-width: 40px;">Tahun</th>
                    <th class="color-white vertical-middle" style="min-width: 80px;">Nama</th>
                    <th class="color-white vertical-middle" style="min-width: 80px;">Jabatan</th>
                    <th class="color-white vertical-middle" style="min-width: 120px;">Unit</th>
                    <th class="color-white vertical-middle no-wrap-th">{!! $kelebihan !!} | {!! $kekurangan !!}</th>
                    <th class="color-white vertical-middle no-wrap-th">Penilaian</th>
					<th class="color-white vertical-middle">Aksi</th>
                    </th>
                </tr>
                </thead>
                <tbody>
					@foreach ($historiPenilaian as $index => $penilaian)
						<tr>
							<td>{{ \Carbon\Carbon::parse($penilaian['liquid']->feedback_start_date)->format('Y') }}</td>
							<td>{{ $penilaian['atasan']['nama'] }}</td>
							<td>{{ $penilaian['atasan']['jabatan'] }}</td>
							<td>
								{{ $penilaian['atasan']['unit_code']
									.' - '.$penilaian['atasan']['unit_name'] }}
							</td>
							<td class="no-wrap-td">
								<strong>{!! $kelebihan !!}</strong>
								<ol class="pad-l-1rem">
									@foreach (collect($penilaian['feedback_kelebihan'])->take(3) as $feedback)
										<li>{{ $feedback }}</li>
									@endforeach
								</ol>
								<button class="btn btn-primary btn-sm"
										data-toggle="modal"
										data-target="{{ '#feedback_'.($index+1) }}">
									Selengkapnya
								</button>

								@include('liquid.dashboard-bawahan._modal_kelebihan_kekurangan')

							</td>
							<td  class="no-wrap-td">
								<div class="table-responsive">
									<table class="table table-striped table-bordered">
										<thead>
										<tr>
											<th scope="col">Resolusi</th>
											<th scope="col">Penilaian Pertama</th>
											<th scope="col">Penilaian Kedua</th>
										</tr>
										</thead>
										<tbody>
										@foreach ($penilaian['penilaian'] as $item)
											<tr>
												<td class="no-wrap-td">{{ $item['resolusi'] }}</td>
												<td class="no-wrap-td">{{ $item['nilai_1'] }}</td>
												<td class="no-wrap-td">{{ $item['nilai_2'] }}</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							</td>
							<td align="center">
								<a href="{{ url('dashboard-bawahan/history-penilaian/show').'/'.$penilaian['atasan']['peserta'][auth()->user()->strukturJabatan->pernr]['liquid_peserta_id'] }}"
									class="badge badge-primary"
									data-toggle="tooltip"
									title="Lihat Detail"><em
											class="fa fa-eye fa-2x"></em></a>
							</td>
						</tr>
					@endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
