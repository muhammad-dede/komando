@include('liquid.components.legend-penilaian')

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col" rowspan="2">Indikator Sikap</th>
            <th class="min-100" rowspan="2">Rata-rata</th>
            <th scope="col" colspan="5">Jumlah Penilai</th>
        </tr>
        <tr>
            <th class="min-100">0 - 2 (SJ)</th>
            <th class="min-100">3 - 4 (J)</th>
            <th class="min-100">5 - 6 (K)</th>
            <th class="min-100">7 - 8 (S)</th>
            <th class="min-120">9 - 10 (SS)</th>
        </tr>
        </thead>
        <tbody>
			@foreach ($detail['resolusi'] as $resolusi)
				<tr>
					<td>
						{{ $resolusi['resolusi'] }}
					</td>
                    <td>{{ $resolusi['avg_pengukuran_pertama'] }}</td>
                    @foreach($resolusi['jumlah_penilai_pengukuran_pertama'] as $jumlah)
                    <td>{{ $jumlah }}</td>
                    @endforeach
				</tr>
			@endforeach
        </tbody>
    </table>
</div>
