<tr>
    <td id="unit_code">{{ $data['unit_code'] }} - {{ $data['unit_name'] }}</td>
    <td id="nama">{{ $data['nama'] }}</td>
    <td id="nip">{{ $data['nip'] }}</td>
    <td id="jabatan">{{ $data['jabatan'] }}</td>
    <td id="kelebihan_lainnya">
        <ul>
            @foreach($data['feedback']['kelebihan_lainnya'] as $item)
                @if(trim($item))
                <li>{{ $item }}</li>
                @endif
            @endforeach
        </ul>
    </td>
    <td id="kekurangan_lainnya">
        <ul>
            @foreach($data['feedback']['kekurangan_lainnya'] as $item)
                @if(trim($item))
                <li>{{ $item }}</li>
                @endif
            @endforeach
        </ul>
    </td>
    <td id="harapan">
        <ul>
            @foreach($data['feedback']['harapan'] as $item)
                @if(trim($item))
                <li>{{ $item }}</li>
                @endif
            @endforeach
        </ul>
    </td>
    <td id="saran">
        <ul>
            @foreach($data['feedback']['saran'] as $item)
                @if(trim($item))
                <li>{{ $item }}</li>
                @endif
            @endforeach
        </ul>
    </td>
</tr>
