<tr>
    <td id="name">{{$data->name}}</td>
    <td id="nip">{{$data->nip}}</td>
    <td id="jabatan">{{@$data->strukturPosisi()->stext}}</td>
    <td id="tahun">{{$tahun}}</td>
    <?php
    $commit = $data->komitmenPegawai()->where('tahun', $tahun)->first();
    ?>
    <td id="commitment">{!! ($commit!=null)? $commit->created_at->format('Y-m-d H:i').'<br><small class=\'text-muted\'>'.$commit->created_at->diffForHumans().'</small>' : '' !!}</td>
</tr>
