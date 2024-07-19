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
        <td><h4>LIQUID REKAP PARTISIPAN</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>{{ $title }}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >NO</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >COMPANY CODE</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >BUSINESS AREA</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >LEVEL</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH ATASAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH BAWAHAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH FEEDBACK</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH % PADA FASE FEEDBACK</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH PENYELARASAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH % PADA FASE PENYELARASAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH PENGUKURAN TAHAP 1</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH % PADA FASE PENGUKURAN TAHAP 1</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH ACTIVITY LOG</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH PENGUKURAN TAHAP 2</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" >JUMLAH % PADA FASE PENGUKURAN TAHAP 2</th>
    </tr>
    @foreach($datas as $index => $data)
        <tr>
            <td>{{ $index+1 }}</td>
            <td>{{ $data->company }}</td>
            <td>{{ $data->business }}</td>
            <td>{{ $data->levell }}</td>
            <td>{{ $data->jml_atasan }}</td>
            <td>{{ $data->jml_bawahan }}</td>
            <td>{{ $data->jml_feedback }}</td>
            <td>{{ $data->persen_feedback }}</td>
            <td>{{ $data->jml_penyelarasan }}</td>
            <td>{{ $data->persen_penyelarasan }}</td>
            <td>{{ $data->jml_pengukuran_pertama }}</td>
            <td>{{ $data->persen_pengukuran_pertama }}</td>
            <td>{{ $data->activity_log }}</td>
            <td>{{ $data->jml_pengukuran_kedua }}</td>
            <td>{{ $data->persen_pengukuran_kedua }} </td>
        </tr>
    @endforeach
</table>
</body>
</html>
