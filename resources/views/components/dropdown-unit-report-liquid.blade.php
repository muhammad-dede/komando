@inject('liquidService', 'App\Services\LiquidService')

<select
    class="select2 form-control form-control-danger"
    name="unit_code"
    tabindex="-1"
    aria-hidden="true"
>
    <option {{ request('unit_code') == "" ? 'selected' : '' }} value="" data-company="all">Semua Unit</option>
    @foreach ($liquidService->listUnitKerja(auth()->user()) as $unit)
        <option
            {{ isset($unit->company_code) ? 'data-company="' . $unit->company_code . '"' : '' }}
            {{ request('unit_code') == $unit->business_area ? 'selected' : '' }}
            value="{{ $unit->business_area }}"
        >
            {{ $unit->description }}
        </option>
    @endforeach
</select>
