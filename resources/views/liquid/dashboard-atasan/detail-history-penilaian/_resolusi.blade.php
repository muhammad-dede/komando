<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">Kekurangan</th>
            <th scope="col">Resolusi</th>
        </tr>
        </thead>
        <tbody>
			@foreach ($detail['resolusi'] as $resolusi)
				<tr>
					<td>{{ $resolusi['resolusi_kekurangan'] }}</td>
					<td>{{ $resolusi['resolusi'] }}</td>
				</tr>
			@endforeach
        </tbody>
    </table>
</div>
