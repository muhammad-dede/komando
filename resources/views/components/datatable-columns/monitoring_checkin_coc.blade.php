@php
    $unit = App\UnitMonitoring::where('orgeh', $data->orgeh)->first();
    $arr_percentage = $unit->getPersentaseCocWeeks($selected_bulan, $selected_tahun);
@endphp
<tr>
    <td id="nama_unit">{{ $data->nama_unit }}</td>
    <td id="target">{{ $data->target_realisasi_coc }}%</td>
    <td id="minggu_1">{{ number_format($arr_percentage[1],2,',','.') }}%</td>
    <td id="minggu_2">{{ number_format($arr_percentage[2],2,',','.') }}%</td>
    <td id="minggu_3">{{ number_format($arr_percentage[3],2,',','.') }}%</td>
    <td id="minggu_4">{{ number_format($arr_percentage[4],2,',','.') }}%</td>
    <td id="minggu_5">{{ number_format($arr_percentage[5],2,',','.') }}%</td>
</tr>
