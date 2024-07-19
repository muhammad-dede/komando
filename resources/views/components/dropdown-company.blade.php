<select class="select2 form-control"
        name="company_code"
        tabindex="-1"
        aria-hidden="true">
    <option {{ request('company_code', auth()->user()->company_code) == '' ? 'selected="selected"' : '' }} value="">Semua Company</option>
    @foreach (\App\CompanyCode::all()->pluck('description', 'company_code') as $key => $label)
        <option value="{{ $key }}" {{ request('company_code', auth()->user()->company_code) == $key ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
