<tr>
    <td id="nama">{{ $data->nama }}</td>
    <td id="nip">{{ $data->nip }}</td>
    <td id="jumlah_bawahan">{{ $data->jumlah_bawahan }}</td>
    <td id="jenjang_jabatan">{{ $data->present_jenjang_jabatan }}</td>
    <td id="sebutan_jabatan">{{ $data->sebutan_jabatan }}</td>
    <td id="unit">{{ $data->unit }}</td>
    <td id="jumlah_feedback">{{ $data->jumlah_feedback }}</td>
    @if(is_unit_pusat(request('unit_code', auth()->user()->getKodeUnit())))
        <td id="divisi">{{ $data->getDivisiPusat() }}</td>
    @endif
    <td id="jumlah_activity_log">{{ $data->jumlah_activity_log }}</td>
    <td id="pengukuran_pertama">{{ $data->pengukuran_pertama }}</td>
    <td id="pengukuran_kedua">{{ $data->pengukuran_kedua }}</td>
    <td id="aksi">
        @php
            $liqId = $data->liquid_id;
            $atasanId = $data->atasan_id;
        @endphp
        <a href="{{ url("dashboard-admin/download-report-liquid/$liqId/$atasanId") }}"
            target="_blank" rel="noopener" class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" data-placement="top"
            title="Grafik">
            &nbsp;
        </a>
    </td>
</tr>
