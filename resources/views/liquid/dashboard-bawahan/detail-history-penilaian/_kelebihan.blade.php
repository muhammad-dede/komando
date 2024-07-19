<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">{!! $kelebihan !!}</th>
        </tr>
        </thead>
        <tbody>
			@foreach ($detail['kelebihan'] as $kelebihan)
				<tr>
					<td>
						{!! $kelebihan !!}
					</td>
				</tr>
			@endforeach
        </tbody>
    </table>
</div>
