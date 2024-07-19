<tr>
    <td id="avatar">
        <a href="{{ url('user-management/user/' . $data->id) }}">
            <img src="{{ app_user_avatar($data->nip) }}" alt="user" class="img-fluid img-thumbnail" width="64">
        </a>
    </td>
    <td id="username"><a href="{{ url('user-management/user/' . $data->id) }}">{{ $data->username }}</a></td>
    <td id="nip" class="hidden-xs">{{ $data->nip }}</td>
    <td id="name" class="hidden-xs">{{ $data->name }}</td>
    <td id="email">{{ $data->email }}</td>
    <td id="ad_company">{{ $data->ad_company }}</td>
    <td id="company_code">
        @if ($data->unitKerjas->isEmpty())
            @if ($data->company_code != null)
                {{ $data->company_code }} - {{ $data->companyCode->description }}
            @endif
        @else
            @foreach ($data->unitKerjas as $item)
                {{ $item->company_code }} - {{ $item->businessArea->companyCode->description }} <br>
            @endforeach
        @endif
    </td>
    <td id="business_area" class="hidden-xs">
        @if ($data->unitKerjas->isEmpty())
            @if ($data->business_area != null)
                {{ $data->business_area }} - {{ $data->businessArea->description }}
            @endif
        @else
            @foreach ($data->unitKerjas as $item)
                {{ $item->business_area }} - {{ $item->businessArea->description }} <br>
            @endforeach
        @endif
    <td id="roles">
        @if ($data->roles->count() > 0)
            @foreach ($data->roles as $role)
                <span class="label label-sm label-info">{{ $role->display_name }}</span>
            @endforeach
        @endif
    </td>
    <td id="action">
        @if (Auth::user()->can('edit_user'))
            <a href="{{ url('user-management/user/' . $data->id . '/edit') }}"
                class="btn btn-success btn-xs waves-effect waves-light" title="Edit">
                <i class="fa fa-pencil"></i>
            </a>

            @if (Auth::user()->hasRole('root'))
                <a href="{{ url('impersonation/' . $data->id) }}" class="btn btn-primary btn-xs waves-effect waves-light"
                    title="Impersonate">
                    <i class="fa fa-external-link"></i>
                </a>
            @endif
        @endif
    </td>
</tr>
