<tr>
    <?php
    $coc_cancel = $data->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'CANC');
    $coc_open = $data->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'OPEN');
    $coc_comp = $data->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'COMP');
    ?>
    <td id="unit">{{$data->business_area.' - '.$data->description}}</td>
    <td id="cancel" align="center">
        {{$coc_cancel}}
    </td>
    <td id="open" align="center">
        {{$coc_open}}
    </td>
    <td id="complete" align="center">
        {{$coc_comp}}
    </td>
</tr>
