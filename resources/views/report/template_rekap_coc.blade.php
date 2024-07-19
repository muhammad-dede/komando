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
        <td><h4>{{$cc_selected->description}}</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>{{$tgl_awal->format('d F Y')}} - {{$tgl_akhir->format('d F Y')}}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">LEVEL ORGANISASI UNIT</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">JABATAN (SEBAGAI PEMATERI)</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH PEJABAT</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">TARGET</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">TOTAL TARGET</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">REALISASI</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">PERSENTASE</th>
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Realisasi</th>--}}

    </tr>
    <?php
    $x = 1;
    ?>
    @foreach($jenjang_list as $jenjang)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">Unit Level {{$jenjang->level}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;"><a href="{{url('report/rekap-coc/'.$cc_selected->company_code.'/'.$jenjang->id)}}">{{$jenjang->jenjang_jabatan}}</a></td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$arr_jml[$x]}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$arr_target[$x]}}</td>
            <?php
            $total_target = $arr_jml[$x] * $arr_target[$x];
            ?>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$total_target}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$arr_realisasi[$x]}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{($total_target==0)?'0':number_format($arr_realisasi[$x]/$total_target*100,2)}}%</td>
            {{--<td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$realisasi->realisasi->format('Y-m-d')}}</td>--}}
            <?php
            $x++;
            ?>
        </tr>
    @endforeach
</table>
</body>
</html>
