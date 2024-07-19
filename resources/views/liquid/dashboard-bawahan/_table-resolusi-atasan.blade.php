<div class="card-box">
    <table class="datatable table table-striped table-bordered">
        <thead class="thead-blue">
        <tr>
            <th class="color-white vertical-middle" style="width: 60px;">Foto</th>
            <th class="color-white vertical-middle" style="min-widt: 80px;">Atasan/Leader</th>
            <th class="color-white vertical-middle" style="min-width: 170px;">Tanggal Pelaksanaan</th>
            <th class="color-white vertical-middle">Resolusi</th>
            <th class="color-white vertical-middle">Aksi Nyata</th>
            </th>
        </tr>
        </thead>
        <tbody>
			@foreach ($resAtasans as $atasan)
				<tr>
					<td>
						<img src="{{ app_user_avatar($atasan['atasan']['nip']) }}"
							 alt="user"
							 width="200"
							 class="radius-full img-fluid mx-auto d-block">
					</td>
					<td>
						<table class="table-border-unset">
							<tr>
								<td><span>: {{ $atasan['atasan']['nama'] }}</span></td>
							</tr>
							<tr>
								<td><span>: {{ $atasan['atasan']['nip'] }}</span></td>
							</tr>
							<tr>
								<td><span>: {{ $atasan['atasan']['jabatan'] }}</span></td>
							</tr>
							<tr>
								<td>
									<span>: @foreach ($atasan['liquid']->businessAreas as $area)
										{{ $area->business_area
											.' - '. $area->description . "\n" }}
									@endforeach</span>
								</td>
							</tr>
						</table>
					</td>
					<td>
					<span class="bold-text">
						{{ \Carbon\Carbon::parse($atasan['liquid']->feedback_start_date)
							->format('d/m/Y') }}</span> s/d <span class="bold-text">
								{{ \Carbon\Carbon::parse($atasan['liquid']->pengukuran_kedua_end_date)
							->format('d/m/Y') }}</span>
					</td>
					<td>
							@forelse ($atasan['resolusi'] as $resolusi)
								{{ ($resolusi['index'] + 1).'. '.$resolusi['resolusi'] }} <br>
							@empty
							{{ "-" }}
							@endforelse
					</td>
					<td align="center">
						@forelse ($atasan['resolusi'] as $resolusi)
							{{ ($resolusi['index'] + 1).'. '.$resolusi['aksi_nyata'] }} <br>
						@empty
							{{ "-" }}
						@endforelse
					</td>
				</tr>
			@endforeach
        </tbody>
    </table>
</div>
