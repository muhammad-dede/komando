@inject('liquidService', 'App\Services\LiquidService')

<select class="select2 form-control form-control-danger"
        name="unit_code"
        tabindex="-1"
        aria-hidden="true"
        disabled
>
    @foreach ($liquidService->listUnitKerja(auth()->user()) as $unit)
        <option data-company="{{ $unit->company_code }}" value="{{ $unit->business_area }}" {{ auth()->user()->business_area == $unit->business_area ? 'selected' : '' }}>
            {{ $unit->description }}
        </option>
    @endforeach
</select>
