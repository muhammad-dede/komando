<tr>
    <td id="rn">{{ $data->rn }}</td>
    <td id="judul">
        <a href="{{url('coc/event/'.$data->id)}}">
            {{$data->judul}}
        </a>
    </td>
    <td id="tema">{{@$data->tema->tema}}</td>
    <td id="jenis">{{@$data->jenis->jenis}}</td>
    <td id="leader">
        @if($data->realisasi!=null)
            {{@$data->realisasi->leader->cname}}
            <br><small class="text-muted">{{@$data->realisasi->leader->nip}} - {{@$data->realisasi->leader->strukturPosisi->stext}}</small>
        @else
            {{$data->leader->cname}}
            <br><small class="text-muted">{{@$data->leader->nip}} - {{@$data->leader->strukturPosisi->stext}}</small>
        @endif
    </td>
    <td id="organisasi">{{@$data->organisasi->stext}}</td>
    <td id="lokasi">{{$data->lokasi}}</td>
    <td id="tanggal_jam">
        @if($data->realisasi!=null)
            {{@$data->realisasi->realisasi->format('H:i')}}
        @else
            {{@$data->tanggal_jam->format('H:i')}}
        @endif
    </td>
    <td id="peserta">{{$data->attendants->count()}}/{{$data->jml_peserta-$data->jml_peserta_dispensasi}} ({{number_format(($data->attendants->count()/($data->jml_peserta-$data->jml_peserta_dispensasi))*100, 2)}}%)</td>
    <td id="status">
        @if($data->status=='OPEN')
            <span class="label label-success">{{$data->status}}</span>
        @elseif($data->status=='CANC')
            <span class="label label-danger">{{$data->status}}</span>
        @else
            <span class="label label-primary">{{$data->status}}</span>
        @endif
    </td>
    <td id="actions">
        @if($data->checkAtendant(Auth::user()->id) || $data->status=='COMP')
            <a href="{{url('coc/event/'.$data->id)}}"
               class="btn btn-primary btn-xs" title=""><i class="fa fa-eye"></i>
                Detail</a>
        @else
            <a href="{{url('coc/check-in/'.$data->id)}}"
               class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i>
                Check-In</a>
        @endif
    </td>
</tr>
