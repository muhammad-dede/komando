<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">{!! $kekurangan !!}</th>
        </tr>
        </thead>
        <tbody>
			@foreach ($detail['kekurangan'] as $kekurangan)
				<tr>
					<td>
						{!! $kekurangan !!}
					</td>
				</tr>
			@endforeach
        </tbody>
    </table>
</div>
