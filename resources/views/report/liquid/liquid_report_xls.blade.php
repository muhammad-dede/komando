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
        <td><h4>REKAPITULASI HASIL LEADERSHIP QUALITY FEEDBACK (INTERNAL PEGAWAI)</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>{{ 'DIVISI/UNIT/UNIT PELAKSANA '.$unitName->description }}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <td height="25" valign="middle" align="center">NO</td>
        <td height="25" valign="middle" align="center">NAMA</td>
        <td height="25" valign="middle" align="center">NIP</td>
        <td height="25" valign="middle" align="center">JENJANG JABATAN</td>
        <td height="25" valign="middle" align="center">SEBUTAN JABATAN</td>
        <td height="25" valign="middle" align="center">UNIT</td>
        @if(is_unit_pusat(request('unit_code', auth()->user()->getKodeUnit())))
            <td height="25" valign="middle" align="center">DIVISI</td>
        @endif
        <td height="25" valign="middle" align="center">KELEBIHAN (3 TERBANYAK YANG DINILAI)</td>
        <td height="25" valign="middle" align="center">RESOLUSI</td>
        <td height="25" valign="middle" align="center">RATA-RATA HASIL (P1)</td>
        <td height="25" valign="middle" align="center">TANGGAL PELAKSANAAN (P1)</td>
        <td height="25" valign="middle" align="center">RATA-RATA HASIL (P2)</td>
        <td height="25" valign="middle" align="center">TANGGAL PELAKSANAAN (P2)</td>
    </tr>
    @php
        $index = 0;
    @endphp
    @foreach ($liquidWithData as $liquid)
        @foreach ($liquid as $data)
            <tr>
                <td rowspan="3" width="8">{{ $index += 1 }}</td>
                <td rowspan="3">{{ $data['atasan_snapshot']['nama'] }}</td>
                <td rowspan="3">{{ $data['atasan_snapshot']['nip'] }}</td>
                <td rowspan="3">{{ app_format_kelompok_jabatan($data['atasan_snapshot']['kelompok_jabatan']) }}</td>
                <td rowspan="3">{{ $data['atasan_snapshot']['jabatan'] }}</td>
                <td rowspan="3">{{ $data['atasan_snapshot']['business_area'] }} - {{ $data['unit_name'] }}</td>
                @if(is_unit_pusat(request('unit_code', auth()->user()->getKodeUnit())))
                    <td rowspan="3">{{ $data['atasan_snapshot']['divisi'] }}</td>
                @endif
                <td>{{ ! empty($data['kelebihan']) ? $data['kelebihan'][0] : '' }}</td>
                <td>{{ ! empty($data['resolusi'][0]) ? $data['resolusi'][0] : '' }}</td>
                <td>{{ ! empty($data['pengukuran_pertama']['rata_rata']) ? app_format_skor_penilaian($data['pengukuran_pertama']['rata_rata'][0]) : '' }}</td>
                <td rowspan="3">{{ $data['pengukuran_pertama']['tanggal'] }}</td>
                <td>{{ ! empty($data['pengukuran_kedua']['rata_rata']) ? app_format_skor_penilaian($data['pengukuran_kedua']['rata_rata'][0]) : '' }}</td>
                <td rowspan="3">{{ $data['pengukuran_kedua']['tanggal'] }}</td>
            </tr>
            @for ($i = 1; $i < 3; $i++)
                <tr>
                    <td>{{ ! empty($data['kelebihan'][$i]) ? $data['kelebihan'][$i] : '' }}</td>
                    <td>{{ ! empty($data['resolusi'][$i]) ? $data['resolusi'][$i] : '' }}</td>
                    <td>{{ ! empty($data['pengukuran_pertama']['rata_rata'][$i]) ? app_format_skor_penilaian($data['pengukuran_pertama']['rata_rata'][$i]) : '' }}</td>
                    <td>{{ ! empty($data['pengukuran_kedua']['rata_rata'][$i]) ? app_format_skor_penilaian($data['pengukuran_kedua']['rata_rata'][$i]) : '' }}</td>
                </tr>
            @endfor
        @endforeach
    @endforeach
</table>
</body>
</html>
