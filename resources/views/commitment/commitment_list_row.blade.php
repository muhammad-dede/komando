<?php
if ($tahun < 2019) {
    $jml_perilaku = 18;
} else {
    $jml_perilaku = env('JML_PEDOMAN', 14);
}
?>
<?php
$jml_jawaban = $data->perilakuPegawai->where('tahun', $tahun)->count();
$persen = ($jml_jawaban / $jml_perilaku) * 100;
if ($persen > 100) $persen = 100;
?>
<tr>
    <td id="name">{{$data->name}}</td>
    <td id="nip">{{$data->nip}}</td>
    <td id="jabatan">{{@$data->strukturPosisi()->stext}}</td>
    <td id="bidang">{{@$data->strukturOrganisasi()->stext}}</td>
    <td id="tahun">{{$tahun}}</td>
    <td id="jawaban" align="center">{{$jml_jawaban.'/'.$jml_perilaku}}</td>
    <td id="progress" align="center">{{number_format($persen,2)}}%</td>
    <?php
    $commit = $data->komitmenPegawai->where('tahun', $tahun)->first();
    ?>
    <td id="commitment">{!! ($commit!=null)? $commit->created_at->format('Y-m-d H:i').'<br><small class=\'text-muted\'>'.$commit->created_at->diffForHumans().'</small>' : '' !!}</td>
</tr>\
