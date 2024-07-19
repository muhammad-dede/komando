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
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;text-align: center">NO</th>
        <th height="25" valign="middle" align="center"  style="border: 1px solid #000;" width="20">PERNR</th>
        <th height="25" valign="middle" align="center"  style="border: 1px solid #000;" width="20">NIPEG</th>
        <th height="25" valign="middle" align="center"  style="border: 1px solid #000;" width="30">PEGAWAI</th>
        <th height="25" valign="middle" align="center"  style="border: 1px solid #000;" width="30">JABATAN (SEBAGAI PEMATERI)</th>
        <th height="25" valign="middle" align="center"  style="border: 1px solid #000;" width="30">UNIT</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;text-align: center" width="20">TARGET</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;text-align: center" width="20">REALISASI</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;text-align: center" width="20">PERSENTASE</th>
    </tr>

    <?php
    $x=1;
    ?>
    @foreach($pejabat_list as $pejabat)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                {{$pejabat->pernr}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                {{$pejabat->jabatan->nip}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;">
                {{$pejabat->jabatan->cname}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;">
                {{$pejabat->jabatan->strukturPosisi->stext}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;">
                {{strtoupper($pejabat->gsber.' - '.$pejabat->businessArea->description)}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                {{$target}}
            </td>
            <?php
            $realisasi = App\RealisasiCoc::getRealisasiJabatan($pejabat->getPositionDefinitive(), $pejabat->gsber, $tgl_awal, $tgl_akhir);
            ?>
            {{--<td align="center">{{$total_target}}</td>--}}
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                {{$realisasi}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                {{number_format($realisasi/$target*100,2)}}%
            </td>
        </tr>
        <?php $x++?>
    @endforeach
</table>
</body>
</html>
