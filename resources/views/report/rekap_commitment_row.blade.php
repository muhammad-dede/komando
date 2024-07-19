<tr>
    <td id="company_code" align="center">{{$data->company_code}}</td>
    <td id="description">{{$data->description}}</td>
    <?php
    $arr_komit = $arr_komitmen[$data->company_code];
    ?>
    @for($y=env('START_YEAR',2018);$y<=date('Y');$y++)
        <td id="commitment_{{ $y }}" align="right">{{number_format($arr_komit[$y],0,',','.')}}</td>
    @endfor
</tr>
