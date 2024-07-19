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
        <td><h4>LIQUID REKAP KELEBIHAN</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>{{ $title }}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >NO</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >KELEBIHAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >PRESENTASE</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH KELEBIHAN</th>
    </tr>
    @foreach ($dataKelebihan['kelebihan'] as $index => $kelebihan)
        <tr>
            <td>{{ $index+=1 }}</td>
            <td>{{ $kelebihan['kelebihan'] }}</td>
            <td>{{ round(($kelebihan['jml_data']*100)/$dataKelebihan['voter'], 2) }} %</td>
            <td>{{ $kelebihan['jml_data'] }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
