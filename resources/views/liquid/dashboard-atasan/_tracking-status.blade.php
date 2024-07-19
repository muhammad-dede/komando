
<div class="card-box">
    <div class="row">
        <div class="col-md-6">
            @if (isset($jadwalStatus))
				<ul class="comp-progress">
					<li class="progress__item progress__item--{{ $jadwalStatus['feedback'] }}">
						<p class="progress__title">Pelaksanaan Feedback</p>
						<p class="progress__info">
							Tanggal Mulai: {{ \Carbon\Carbon::parse($liquid->feedback_start_date)
								->format('d-m-Y') }}
						</p>
					</li>
					<li class="progress__item progress__item--{{ $jadwalStatus['penyelarasan'] }}">
						<p class="progress__title">Pelaksanaan Penyelarasan</p>
						<p class="progress__info">
							Tanggal Mulai: {{ \Carbon\Carbon::parse($liquid->penyelarasan_start_date)
								->format('d-m-Y') }}
						</p>
					</li>
					<li class="progress__item progress__item--{{ $jadwalStatus['pengukuran_pertama'] }}">
						<p class="progress__title">Pengukuran Pertama</p>
						<p class="progress__info">
							Tanggal Mulai: {{ \Carbon\Carbon::parse($liquid->pengukuran_pertama_start_date)
								->format('d-m-Y') }}
						</p>
					</li>
					<li class="progress__item progress__item--{{ $jadwalStatus['pengukuran_kedua'] }}">
						<p class="progress__title">Pengukuran Kedua</p>
						<p class="progress__info">
							Tanggal Mulai: {{ \Carbon\Carbon::parse($liquid->pengukuran_kedua_start_date)
								->format('d-m-Y') }}
						</p>
					</li>
				</ul>
			@endif
        </div>
        <div class="col-md-6">
            <div class="card height-full">
                <div class="card-header">
                    <div class="title-top lh-35">Keterangan Tracking</div>
                </div>
                <div class="card-body">
                    @if (isset($jadwalStatus))
						{{ \App\Enum\LiquidStatus::trackingWording(
							$liquid->getCurrentSchedule(),
							$liquid->peserta()->distinct('bawahan_id')
								->count('bawahan_id')
						) }}
					@else
						<i>No Result Found</i>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
