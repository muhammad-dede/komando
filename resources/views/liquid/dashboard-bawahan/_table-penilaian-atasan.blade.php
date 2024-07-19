<div class="card-box">
    <div class="card-box">
        <ul class="nav nav-tabs comp-tab" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-1" role="tab"
                   aria-selected="true">{!! $kelebihan !!} dan {!! $kekurangan !!}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-2" role="tab" aria-selected="false">{!! $saran !!}
                    dan Harapan</a>
            </li>
        </ul>
        <div class="tab-content comp-tab-content" id="myTabContent">
            <div class="tab-pane fade show active in" id="tab-1" role="tabpanel">
                <table class="datatable table table-striped table-bordered">
                    <thead class="thead-blue">
                    <tr>
                        <th class="color-white vertical-middle" style="width: 60px;">Foto</th>
                        <th class="color-white vertical-middle" style="min-widt: 80px;">Atasan/Leader</th>
                        <th class="color-white vertical-middle">Vote {!! $kelebihan !!}</th>
                        <th class="color-white vertical-middle">Vote {!! $kekurangan !!}</th>
                        <th class="color-white vertical-middle">Aksi</th>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
						@foreach ($resAtasans as $atasan)
							<tr>
								<td>
									<img class="img img-circle img-thumbnail"
										src="{{ asset('assets/images/users/foto').'/'.$atasan['foto'] }}" alt="">
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
												<span>
													: {{ $atasan['business_area']->business_area
														.' - '.$atasan['business_area']->description }}
												</span>
											</td>
										</tr>
									</table>
								</td>
								<td>
									<ol class="pad-l-1rem">
										@if (! empty($atasan['feedback']->kelebihan))
											@foreach ($atasan['feedback']->kelebihan as $feedback)
												<li>
													{{ \App\Models\Liquid\KelebihanKekuranganDetail::withTrashed()
														->find($feedback)
														->deskripsi_kelebihan }}
												</li>
											@endforeach
										@endif
										@if (! empty($atasan['feedback']->new_kelebihan))
											@foreach ($atasan['feedback']->new_kelebihan as $new_kelebihan)
												<li>{{ $new_kelebihan }}</li>
											@endforeach
										@endif
									</ol>
								</td>
								<td>
									<ol class="pad-l-1rem">
										@if (! empty($atasan['feedback']->kekurangan))
											@foreach ($atasan['feedback']->kekurangan as $feedback)
												<li>
													{{ \App\Models\Liquid\KelebihanKekuranganDetail::withTrashed()
														->find($feedback)
														->deskripsi_kekurangan }}
												</li>
											@endforeach
										@endif
										@if (! empty($atasan['feedback']->new_kekurangan))
											@foreach ($atasan['feedback']->new_kekurangan as $new_kekurangan)
												<li>{{ $new_kekurangan }}</li>
											@endforeach
										@endif
									</ol>
								</td>
								<td align="center">
									<a href="" class="badge badge-primary" data-toggle="tooltip" title="Lihat Detail"><em
												class="fa fa-eye fa-2x"></em></a>
								</td>
							</tr>
						@endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="tab-2" role="tabpanel">
                <table class="datatable table table-striped table-bordered">
                    <thead class="thead-blue">
                    <tr>
                        <th class="color-white vertical-middle" style="width: 60px;">Foto</th>
                        <th class="color-white vertical-middle" style="min-widt: 80px;">Atasan/Leader</th>
                        <th class="color-white vertical-middle">Harapan</th>
                        <th class="color-white vertical-middle">{!! $saran !!}</th>
                        <th class="color-white vertical-middle">Aksi</th>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
						@foreach ($resAtasans as $atasan)
							<tr>
								<td>
									<img class="img img-circle img-thumbnail"
										src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="">
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
									@if (! empty($atasan['feedback']))
										{!! $atasan['feedback']->harapan !!}
									@else
										{{ "-" }}
									@endif
								</td>
								<td>
									@if (! empty($atasan['feedback']))
										{!! $atasan['feedback']->saran !!}
									@else
										{{ "-" }}
									@endif
								</td>
								<td align="center">
									<a href="" class="badge badge-primary" data-toggle="tooltip" title="Lihat Detail"><em
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
