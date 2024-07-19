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
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="5">No</th>
{{--        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">ADMIN</th>--}}
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">UNIT</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">JUMLAH COC CANCEL</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">JUMLAH COC OPEN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">JUMLAH COC COMPLETE</th>
{{--        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH RENCANA PESERTA</th>--}}
{{--        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH CHECK IN</th>--}}
{{--        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">% CHECK IN</th>--}}
{{--        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH BACA MATERI</th>--}}
{{--        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">% BACA MATERI</th>--}}

    </tr>

    {{--<tbody>--}}
    <?php
    $x=1;
    $total_coc_open = 0;
    $total_coc_cancel = 0;
    $total_coc_comp = 0;
    ?>
    @foreach($cc_selected->businessArea()->orderBy('business_area','asc')->get() as $business_area)
        <?php
        $coc_cancel = $business_area->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'CANC');
        $coc_open = $business_area->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'OPEN');
        $coc_comp = $business_area->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'COMP');

        $total_coc_cancel += $coc_cancel;
        $total_coc_open += $coc_open;
        $total_coc_comp += $coc_comp;
        ?>
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$business_area->business_area.' - '.$business_area->description}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$coc_cancel}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$coc_open}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$coc_comp}}</td>
        </tr>
        <?php
        $x++;
        ?>
    @endforeach

    {{--</tbody>--}}

    {{--<tfoot>--}}
    <tr>
        <th height="25" valign="middle" style="border: 1px solid #000; text-align: center" ></th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >TOTAL</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_coc_cancel}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_coc_open}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_coc_comp}}</th>

    </tr>
    {{--</tfoot>--}}
</table>
</body>
</html>
