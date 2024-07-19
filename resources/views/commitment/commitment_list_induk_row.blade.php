<?php
$tahun = date('Y');
if ($tahun < 2019) {
    $jml_perilaku = 18;
} else {
    $jml_perilaku = env('JML_PEDOMAN', 14);
}
?>
<tr>
    <td id="name">{{strtoupper($data->sname)}}</td>
    <td id="nip">{{@$data->pa0032->nip}}</td>
    <td id="jabatan">{{@$data->jabatan->strukturPosisi->stext}}</td>
    <td id="unit">{{@$data->businessArea->description}}</td>
    @for($y = $tahun-1;$y<=$tahun;$y++)
        <td id="progress_{{ $y }}" align="center">
            @if($data->pa0032->user!=null)
                <?php
                $jml_jawaban = @$data->pa0032->user->perilakuPegawai->where('tahun', strval($y))->count();
                $persen = ($jml_jawaban / $jml_perilaku) * 100;
                if ($persen > 100) $persen = 100;
                ?>
                {{number_format($persen)}}%
            @else
                0%
            @endif
        </td>
    @endfor
    <?php
    if ($data->pa0032->user != null) {
        $commit = $data->pa0032->user->komitmenPegawai->where('tahun', $tahun)->first();
    } else {
        $commit = null;
    }
    ?>
    <td id="commitment">
        @if($data->pa0032->user!=null)
            {!!($commit!=null)? $commit->created_at->format('Y-m-d H:i').'<br><small class=\'text-muted\'>'.$commit->created_at->diffForHumans().'</small>' : ''!!}
        @else
            -
        @endif
    </td>
</tr>
