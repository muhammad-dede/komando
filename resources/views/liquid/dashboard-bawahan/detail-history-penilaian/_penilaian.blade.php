<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col" width="75%">Resolusi</th>
            <th scope="col">Penilaian Pertama</th>
            <th scope="col">Penilaian Kedua</th>
        </tr>
        </thead>
        <tbody>
			@foreach ($detail['penilaian'] as $item)
				<tr>
					<td>{{ $item['resolusi'] }}</td>
					<td>{{ $item['nilai_1'] }}</td>
					<td>{{ $item['nilai_2'] }}</td>
				</tr>
			@endforeach
        </tbody>
    </table>
</div>
