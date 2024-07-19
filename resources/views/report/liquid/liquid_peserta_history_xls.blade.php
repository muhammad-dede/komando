@inject('liquidService', 'App\Services\LiquidService')

@php
	$user = auth()->user();
	$unitCode = request('unit_code', $user->business_area);
	$divisi = request('divisi', $user->getKodeDivisiPusat());
	$year = request('year');

	$liquids = $liquidService->getHistoryInformation($unitCode, $divisi, $year);
@endphp

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table>
    <tr>
        <td></td>
        <td><h4>PT PLN (PERSERO)</h4></td>
    </tr>
    <tr>
        <td></td>
        <td><h4>PESERTA LIQUID</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>{{ 'DIVISI/UNIT/UNIT PELAKSANA '.$unitName->description }}</td>
    </tr>
</table>
<table border="1">
    <tr>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">NO</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">UNIT</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">NAMA ATASAN</th>
		<th height="38" width="10" valign="middle" align="center" style="border: 1px solid #000;">NIP</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">JABATAN</th>
		<th height="38" width="10" valign="middle" align="center" style="border: 1px solid #000;">JUMLAH BAAHAN</th>
		<th height="38" width="10" valign="middle" align="center" style="border: 1px solid #000;">FEEDBACK BAWAHAN</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PESERTA FEEDBACK</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PESERTA YANG BELUM MENGISI FEEDBACK</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PENYELARASAN</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PENGUKURAN PERTAMA</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PESERTA PENGUKURAN PERTAMA</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PESERTA YANG BELUM MENGISI PENGUKURAN PERTAMA</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PENGUKURAN KEDUA</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PESERTA PENGUKURAN KEDUA</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">PESERTA YANG BELUM MENGISI PENGUKURAN KEDUA</th>
		<th height="38" valign="middle" align="center" style="border: 1px solid #000;">STATUS TERAKHIR</th>
	</tr>
	@php($i = 0)
	@foreach ($liquids as $liquid)
		@foreach ($liquid as $dataPerAtasan)
			<tr>
				<td>{{ $i+=1 }}</td>
				<td>
					{{ $dataPerAtasan['atasan_snapshot']['business_area'] }}
					-
					{{ \App\Models\Liquid\BusinessArea::where('business_area', $dataPerAtasan['atasan_snapshot']['business_area'])->value('description') }}
				</td>
				<td>
					{{ $dataPerAtasan['atasan_snapshot']['nama'] }}
				</td>
				<td>
					{{ $dataPerAtasan['atasan_snapshot']['nip'] }}
				</td>
				<td>
					{{ $dataPerAtasan['atasan_snapshot']['jabatan'] }}
				</td>
				<td>{{ $dataPerAtasan['peserta_count'] }}</td>
				<td>
					{{ $dataPerAtasan['feedback_count']
					. '/' .$dataPerAtasan['peserta_count'] }}
				</td>
				<td>
					{!! implode(",<br>", $dataPerAtasan['peserta_done_feedback']) !!}
				</td>
				<td>
					{!! implode(",<br>", $dataPerAtasan['peserta_unfinished_feedback']) !!}
				</td>
				<td>
					@if ($dataPerAtasan['has_penyelarasan'] === true)
						<span>DONE</span>
					@else
						<span >NOT YET</span>
					@endif
				</td>
				<td>
					{{ $dataPerAtasan['pengukuran_pertama_count']
					. '/' .$dataPerAtasan['peserta_count'] }}
				</td>
				<td>
					{!! implode(",<br>", $dataPerAtasan['peserta_done_pengukuran_pertama']) !!}
				</td>
				<td>
					{!! implode(",<br>", $dataPerAtasan['peserta_unfinished_pengukuran_pertama']) !!}
				</td>
				<td>
					{{ $dataPerAtasan['pengukuran_kedua_count']
					. '/' .$dataPerAtasan['peserta_count'] }}
				</td>
				<td>
					{!! implode(",<br>", $dataPerAtasan['peserta_done_pengukuran_kedua']) !!}
				</td>
				<td>
					{!! implode(",<br>", $dataPerAtasan['peserta_unfinished_pengukuran_kedua']) !!}
				</td>
				<td>
					<span>
						{{ $dataPerAtasan['liquid_status'] }}
					</span>
				</td>
			</tr>
		@endforeach
	@endforeach
</table>
</body>
</html>
