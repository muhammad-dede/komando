@php
    $unit = App\UnitMonitoring::where('orgeh', $data->orgeh)->first();
    // $arr_percentage = $unit->getPersentaseReadMateriWeeks($selected_bulan, $selected_tahun);
@endphp
<tr>
    <td id="nama_unit">{{ $data->nama_unit }}</td>
    <td id="target">{{ $data->target_realisasi_coc }}%</td>
    @for($i = 1; $i <= 5; $i++)
<td id="minggu_{{ $i }}">
<a href="{{ url('report/monitoring-baca-materi-pegawai?orgeh='.$data->orgeh.'&bulan='.$selected_bulan.'&tahun='.$selected_tahun.'&minggu_ke='.$i) }}" target="blank">%</a>
</td>
    @endfor
</tr>
