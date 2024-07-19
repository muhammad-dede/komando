<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table>
    {{-- <tr>
        <td></td>
        <td>PT PLN (PERSERO)</h4></td>
    </tr> --}}
    <tr>
        <td></td>
        <td><h3>Persentase Baca Materi {{ $bulan_list[$selected_bulan].' '.$selected_tahun }}</h3></td>
        <td></td>
    </tr>
</table>
<table border="1">
    {{-- <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NAME</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NIP</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">BUSINESS AREA</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">BIDANG</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">JABATAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">BACA MATERI</th>
    </tr> --}}
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">Divisi / Unit Kerja</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">Target</th>
        @for($i=1; $i<=5; $i++)
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">
            Minggu {{ $i }} ({{ $arr_date_week[$i]['start'] }} - {{ $arr_date_week[$i]['end'] }})
        </th>
        @endfor
    </tr>
    <?php
    $x=1;
    ?>
    @foreach($unit_monitoring as $unit)
    @php
        $unit = App\UnitMonitoring::where('orgeh', $unit->orgeh)->first();
        $arr_percentage = $unit->getPersentaseReadMateriWeeks($selected_bulan, $selected_tahun);
    @endphp
    <tr>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;">{{ $x++ }}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;">{{strtoupper($unit->nama_unit)}}</td>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;">{{ $unit->target_realisasi_coc }}%</td>
        @for($i=1; $i<=5; $i++)
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;">
            {{ number_format($arr_percentage[$i],2,',','.') }}%
        </td>
        @endfor
    </tr>
    @endforeach
</table> 
</body>
</html>
