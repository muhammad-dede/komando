<tr>
    <td id="tema"><a href="{{url('master-data/tema-coc/'.$data->id.'/edit')}}">{{$data->tema->tema}}</a></td>
    <td id="start_date" align="center">{{@$data->start_date->format('Y-m-d')}}</td>
    <td id="end_date" align="center">{{@$data->end_date->format('Y-m-d')}}</td>
    <td id="updated_by" align="center">{{@$data->updatedBy->name}}</td>
    <td id="updated_at" align="center">{{@$data->updated_at->format('Y-m-d H:i')}}</td>
</tr>
