<tr>
    <td id="avatar">
        {{-- <a href="{{ url('user-management/user/' . $data->id) }}"> --}}
            <img src="{{ app_user_avatar($data->nip) }}" alt="user" class="img-fluid img-thumbnail" width="64">
        {{-- </a> --}}
    </td>
    {{-- <td id="username"><a href="{{ url('user-management/user/' . $data->id) }}">{{ $data->username }}</a></td> --}}
    <td id="nip">{{ $data->nip }}</td>
    <td id="name" >{{ $data->name }}</td>
    <td id="business_area" class="hidden-xs">
        @if ($data->unitKerjas->isEmpty())
            @if ($data->business_area != null)
                {{ $data->business_area }} - {{ @$data->businessArea->description }}
            @endif
        @else
            @foreach ($data->unitKerjas as $item)
                {{ $item->business_area }} - {{ @$item->businessArea->description }} <br>
            @endforeach
        @endif
    </td>
    <td id="bidang" class="hidden-xs">{{ $data->bidang }}</td>
    <td id="jabatan" class="hidden-xs">{{ $data->jabatan }}</td>
    <td id="status">
        @php
            $attendance = $coc->checkAtendant($data->id);
            $materi = $coc->materi;
            if($materi!=null)
                $read_materi = $materi->checkReader($data->id, $coc->id);
            else
                $read_materi = null;
        @endphp
        @if($attendance)
        <span class="label label-success"><b>Hadir</b></span>
        @else
        <span class="label label-danger"><b>Tidak Hadir</b></span>
        @endif    
    </td>
    <td id="checkin" class="hidden-xs">
        @if($attendance)
        {{@$attendance->check_in->format('Y-m-d H:i')}}<br>
        <small class="text-muted">{{@$attendance->check_in->diffForHumans()}}</small>
        @endif
    </td>
    <td id="baca_materi" class="hidden-xs">
        @if($read_materi!=null)
        {{@$read_materi->created_at->format('Y-m-d H:i')}}<br>
        <small class="text-muted">{{@$read_materi->created_at->diffForHumans()}}</small>
        @endif
    </td>
</tr>
