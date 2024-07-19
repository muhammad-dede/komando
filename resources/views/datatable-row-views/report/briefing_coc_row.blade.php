<tr>
    <td id="tema">{{@$data->coc->tema->tema}}</td>
    <td id="organisasi" align="center">{{($data->level!='')? 'Level '.$data->level:''}}</td>
    <td id="jenjang_jabatan">{{@$data->jenjangJabatan->jenjang_jabatan}}</td>
    <td id="judul_coc"><a href="{{url('coc/event/'.$data->coc_id)}}">{{@$data->coc->judul}}</a></td>
    <td id="narasumber">
        {{@$data->leader->name}}<br>
        <small class="text-muted">{{@$data->leader->nip}} / {{@$data->leader->strukturPosisi->stext}}</small>
    </td>
    <td id="unit">{{@$data->business_area}} - {{@$data->businessArea->description}}</td>
    <td id="admin_coc">
        {{$data->coc->admin->name}}<br>
        <small class="text-muted">{{@$data->coc->admin->nip}}</small>
    </td>
    <td id="target_pelaksanaan">{{@$data->coc->tanggal_jam->format('Y-m-d')}}</td>
    <td id="realisasi">{{@$data->realisasi->format('Y-m-d')}}</td>
</tr>
