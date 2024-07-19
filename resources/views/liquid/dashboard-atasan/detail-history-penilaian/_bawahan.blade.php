<div class="table-responsive">
    <table class="table table-striped table-bordered datatable">
        <thead>
        <tr>
			<th scope="col" width="5">No</th>
            <th scope="col">Foto</th>
            <th scope="col">Bawahan</th>
            <th>NIP</th>
            <th>Jabatan</th>
        </tr>
        </thead>
        <tbody>
		@php($index = 0)
        @foreach($detail['bawahan'] as $bawahan)
            <tr>
				<td>{{ $index += 1 }}</td>
                <td class="align-center">
                    <img class="img-fluid radius-full align-center img-thumbnail img-50"
                         src="{{ app_user_avatar($bawahan['nip']) }}" alt="">
                </td>
                <td>{{ $bawahan['nama'] }}</td>
                <td>{{ $bawahan['nip'] }}</td>
                <td>{{ $bawahan['jabatan'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
