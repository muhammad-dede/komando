<tr>
    <td id="id"><a href="{{url('report/problem/'.$data->id)}}">{{str_pad($data->id,8,'0',STR_PAD_LEFT)}}</a></td>
    <td id="created_at">{{(@$data->created_at!=null)?@$data->created_at->format('d/m/Y H:i:s'):''}}</td>
    <td id="owner">{{strtoupper(@$data->caseOwner->name)}} / {{@$data->caseOwner->nip}}</td>
    <td id="unit">{{strtoupper(@$data->unit)}}</td>
    <td id="server">{{@$data->server->server}}</td>
    <td id="grup">{{@$data->group->masalah}}</td>
    <td id="deskripsi">{!! $data->deskripsi !!}</td>
    <td id="status" align="center">
        @if($data->status=='1')
            <span class="label label-danger">{{strtoupper($data->statusProblem->status)}}</span>
        @elseif($data->status=='2')
            <span class="label label-warning">{{strtoupper($data->statusProblem->status)}}</span>
        @elseif($data->status=='3')
            <span class="label label-primary">{{strtoupper($data->statusProblem->status)}}</span>
        @else
            <span class="label label-success">{{strtoupper($data->statusProblem->status)}}</span>
        @endif
    </td>
</tr>
