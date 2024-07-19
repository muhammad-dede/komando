<tr>
    <td id="tema"><a href="{{url('coc/tema/'.$data->id)}}">{{$data->tema->tema}}</a></td>
    <td id="start_date" class="hidden-xs" align="center">{{$data->start_date->format('Y-m-d')}}</td>
    <td id="end_date" class="hidden-xs" align="center">{{$data->end_date->format('Y-m-d')}}</td>
    <td id="count" class="hidden-xs" align="center">{{$data->tema->coc->count()}}</td>
</tr>
