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
        <td><h4>REKAP PROGRESS LIQUID</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>{{ $title }}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >ORGANISASI</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >NAMA JABATAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH ATASAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH BAWAHAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >FEEDBACK</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >PENYELARASAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >PENGUKURAN 1</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >PENGUKURAN 2</th>
    </tr>
    @foreach($dataProgressLiquid as $key => $val)
    <tr>
        <td valign="left" style="text-align:left;border:1px solid #000000;">{{ $val['unit_name'] }}</td>
        <td valign="left" style="text-align:left;border:1px solid #000000;">{{ $val['jabatan'] }}</td>
        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $val['jml_atasan'] }}</td>
        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $val['jml_bawahan'] }}</td>
        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $val['has_feedback']."/".$val['jml_bawahan']." (".$val['persent_feedback']."%)" }}</td>
        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $val['has_penyelarasan']."/".$val['jml_atasan']." (".$val['persent_penyelarasan']."%)" }}</td>
        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $val['has_pengukuran_1']."/".$val['jml_bawahan']." (".$val['persent_pengukuran_1']."%)" }}</td>
        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $val['has_pengukuran_2']."/".$val['jml_bawahan']." (".$val['persent_pengukuran_2']."%)" }}</td>
    </tr>
    @endforeach
</table>
</body>
</html>
