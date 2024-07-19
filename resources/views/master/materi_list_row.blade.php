@php
    $rate = $data->getArrJmlRate();
    $avg = $rate['avg'];
    $total_review = $rate['total'];
    $arr_rate = $rate['arr_rate'];
@endphp
<tr>
    <td id="judul"><a href="{{url('master-data/materi/'.$data->id.'/edit')}}">{{$data->judul}}</a></td>
    <td id="penulis">{{($data->energize_day=='1')?'Energize Day':@$data->penulis->cname}}</td>
    <td id="tema">{{@$data->tema->tema}}</td>
    <td id="jenis">{{@$data->jenisMateri->jenis}}</td>
    <td id="cluster">
        @if($data->energize_day=='1') Energize Day
        @elseif($data->rubrik_transformasi=='1') Rubrik Transformasi
        @elseif($data->jenis_materi_id=='1') Nasional
        @else Local
        @endif
    </td>
    <td id="company_code">{{@$data->companyCode->description}}</td>
    <td id="business_area">{{@$data->businessArea->description}}</td>
    <td id="struktur_organisasi">{{@$data->strukturOrganisasi->stext}}</td>
    <td id="jumlah_like" align="right">{{@$data->getJumlahLike('number')}}</td>
    <td id="jumlah_dislike" align="right">{{@$data->getJumlahDislike('number')}}</td>
    <td id="rating_1" align="right">{{number_format($arr_rate[5])}}</td>
    <td id="rating_2" align="right">{{number_format($arr_rate[4])}}</td>
    <td id="rating_3" align="right">{{number_format($arr_rate[3])}}</td>
    <td id="rating_4" align="right">{{number_format($arr_rate[2])}}</td>
    <td id="rating_5" align="right">{{number_format($arr_rate[1])}}</td>
    <td id="total_review" align="right">{{number_format($total_review)}}</td>
    <td id="rata_rata" align="center">{{number_format($avg,1)}}</td>
</tr>
