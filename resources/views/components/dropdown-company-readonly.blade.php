<select class="select2 form-control"
        name="company_code"
        tabindex="-1"
        aria-hidden="true" disabled>
    @foreach (\App\CompanyCode::all()->pluck('description', 'company_code') as $key => $label)
        <option value="{{ $key }}" {{ auth()->user()->company_code == $key ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
