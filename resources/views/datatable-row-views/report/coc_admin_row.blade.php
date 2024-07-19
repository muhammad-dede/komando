<tr>
    <td id="rn">{{ $data->rn }}</td>
    <td id="judul">
        <a href="{{url('coc/event/'.$data->id)}}">
            {{$data->judul}}
        </a>
    </td>
    <td id="tema">{{$data->tema->tema}}</td>
    <td id="cname">{{$data->jenis->jenis}}</td>
    <td id="leader">
        @if($data->realisasi!=null)
            {{@$data->realisasi->leader->cname}}
            <br><small class="text-muted">{{@$data->realisasi->leader->nip}} - {{@$data->realisasi->leader->strukturPosisi->stext}}</small>
        @else
            {{@$data->leader->cname}}
            <br><small class="text-muted">{{@$data->leader->nip}} - {{@$data->leader->strukturPosisi->stext}}</small>
        @endif
    </td>
    <td id="unit">{{@$data->organisasi->stext}}</td>
    <td id="lokasi">{{@$data->lokasi}}</td>
    <td id="tanggal_jam">
        @if(@$data->realisasi!=null)
            {{@$data->realisasi->realisasi->format('Y-m-d H:i')}}
        @else
            {{@$data->tanggal_jam->format('Y-m-d H:i')}}
        @endif
    </td>
    <td id="peserta">
        @if($data->jml_peserta!=0)
            {{$data->attendants->count()}}/{{$data->jml_peserta - $data->jml_peserta_dispensasi}} ({{number_format(($data->attendants->count()/($data->jml_peserta - $data->jml_peserta_dispensasi))*100, 2)}}%)
        @else
            {{$data->attendants->count()}}
        @endif
    </td>
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
        @if(!($data->checkAtendant(Auth::user()->id) || $data->status=='COMP' || $data->tanggal < \Carbon\Carbon::now()))
            <a href="{{url('coc/check-in/'.$data->id)}}"
               class="btn btn-success btn-xs waves-effect waves-light" title="Check-In">
                <i class="fa fa-check-circle"></i>
            </a>
        @endif
        @if($data->status=='OPEN' && (( Auth::user()->can('input_coc_local') && $data->admin_id == Auth::user()->id) || Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_ki')))
            {!! Form::open(['url'=>'']) !!}
            <a href="javascript:" id="post" type="submit"
               class="btn btn-success btn-xs waves-effect waves-light"
               data-toggle="modal"
               data-target="#completeModal" title="Complete" onclick="javascript:ajaxComplete('{{$data->id}}')">
                <i class="fa fa-flag-checkered"></i>
            </a>
            <a href="javascript:" type="submit"
               class="btn btn-danger btn-xs waves-effect waves-light"
               title="Cancel"
               onclick="javascript:cancelCoc('{{$data->id}}')"
            >
                <i class="fa fa-trash"></i>
            </a>
            {!! Form::close() !!}
        @else
            <a href="javascript:" id="post" type="submit"
               class="btn btn-success btn-xs waves-effect waves-light disabled"
               data-toggle="modal"
               data-target="#completeModal" title="Complete">
                <i class="fa fa-flag-checkered"></i>
            </a>
        @endif
    </td>
</tr>
