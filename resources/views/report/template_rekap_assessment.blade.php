<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">NO</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">PERIODE</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="15">NIP PEGAWAI</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NAMA PEGAWAI</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="15">NIP VERIFIKATOR</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NAMA VERIFIKATOR</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">UNIT</th>
        {{-- @if($cc_selected->company_code == '1000')
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">DIVISI</th>
        @endif --}}
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">JABATAN PEGAWAI</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">HARDSKILL</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">KOMPETENSI</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">LEVEL PROFISIENSI SESUAI KKJ</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">HASIL PENGUKURAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">GAP</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">PENGEMBANGAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">PENGEMBANGAN PRIORITAS</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">TANGGAL PENGUKURAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">STATUS</th>
        

    </tr>
    @php
        $x=1;    
    @endphp
    @foreach($daftar_peserta as $peserta)
        @if($peserta->assessmentPegawai->count()>0)
            @foreach($peserta->assessmentPegawai as $penilaian)
            <tr>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{ $x++ }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ Jenssegers\Date\Date::parse(@$peserta->jadwal->periode.'-'.$peserta->bulan_periode)->format('F Y') }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->nip_pegawai }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->nama_pegawai }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->nip_verifikator }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->nama_verifikator }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ @$peserta->businessArea->description }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->posisi }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->hardskill }}</td>

                <td height="25" valign="middle" style="border: 1px solid #000;">{{ @$penilaian->kompetensi->judul_kompetensi }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{ $penilaian->level_kkj }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{ $penilaian->level_final }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000; {{ ($penilaian->gap_level<0)?'color: #ff4359':'' }};" align="center">{{ $penilaian->gap_level }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $penilaian->usulan_pengembangan }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{ ($penilaian->prioritas)?'Prioritas':'' }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{ Carbon\Carbon::parse($penilaian->tanggal_input)->format('d-m-Y') }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{ $peserta->status_assessment }}</td>
            </tr>
            @endforeach
        @else 
            <tr>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{ $x++ }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ Jenssegers\Date\Date::parse(@$peserta->jadwal->periode.'-'.$peserta->bulan_periode)->format('F Y') }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->nip_pegawai }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->nama_pegawai }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->nip_verifikator }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->nama_verifikator }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ @$peserta->businessArea->description }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->posisi }}</td>
                <td height="25" valign="middle" style="border: 1px solid #000;">{{ $peserta->hardskill }}</td>

                <td height="25" valign="middle" style="border: 1px solid #000;"></td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center"></td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center"></td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center"></td>
                <td height="25" valign="middle" style="border: 1px solid #000;"></td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center"></td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center"></td>
                <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{ $peserta->status_assessment }}</td>
            </tr>
        @endif
    @endforeach
</table>
</body>
</html>
