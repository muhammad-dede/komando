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
        <td><h4>LIQUID REKAP KEKURANGAN</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>{{ $title }}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >NO</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >KEKURANGAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >PRESENTASE</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH KEKURANGAN</th>
    </tr>
    @foreach ($dataKekurangan['kekurangan'] as $index => $kekurangan)
        <tr>
            <td>{{ $index+=1 }}</td>
            <td>{{ $kekurangan['kekurangan'] }}</td>
            <td>{{ round(($kekurangan['jml_data']*100)/$dataKekurangan['voter'], 2) }} %</td>
            <td>{{ $kekurangan['jml_data'] }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
