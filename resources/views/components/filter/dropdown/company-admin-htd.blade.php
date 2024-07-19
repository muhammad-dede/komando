@inject('liquidService', 'App\Services\LiquidService')

<select
    class="select2 form-control"
    name="company_code"
    tabindex="-1"
    aria-hidden="true"
>
    <option
        {{ request('company_code', auth()->user()->company_code) == '' ? 'selected="selected"' : '' }}
        value=""
    >
        Semua Company Code
    </option>
    @foreach ($liquidService->listUnitKerja(auth()->user()) as $unit)
        <option
            {{ request('company_code') == $unit->business_area ? 'selected' : '' }}
            value="{{ $unit->business_area }}"
        >
            {{ $unit->description }}
        </option>
    @endforeach
</select>
