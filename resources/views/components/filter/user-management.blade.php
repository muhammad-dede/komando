<form action="{{ url()->current() }}" method="get">
    <div class="row" style="padding: 1rem 0">
        <div class="col-md-3 col-xs-12">
            <h4 class="">{{ $title }}</h4>
        </div>

        <div class="col-md-1 col-xs-12"></div>

        <div class="col-md-4 col-xs-12">
            @if($user->hasRole('root') || $user->hasRole('admin_ki') || $user->hasRole('admin_liquid_ki'))
                {!!
                    Form::select(
                        'business_area',
                        $bsList,
                        isset($bs_selected) ? $bs_selected->business_area : null,
                        [
                            'class' => 'form-control select2',
                            'id' => 'business_area'
                        ]
                    )
                !!}
            @elseif($user->hasRole('admin_htd'))
                {!!
                    Form::select(
                        'business_area',
                        $bsList,
                        isset($bs_selected) ? $bs_selected->business_area : null,
                        [
                            'class' => 'form-control select2',
                            'id' => 'business_area'
                        ]
                    )
                !!}
            @else
                {!!
                    Form::select(
                        'business_area',
                        $bsList,
                        isset($bs_selected) ? $bs_selected->business_area : null,
                        [
                            'class' => 'form-control select2',
                            'id' => 'business_area',
                            'disabled'
                        ]
                    )
                !!}
            @endif
        </div>

        <div class="col-md-2 col-xs-12">
            {!!
                Form::select(
                    'role',
                    $roles,
                    $role,
                    [
                        'class' => 'form-control select2',
                        'id' => 'filter-role'
                    ]
                )
            !!}
        </div>

        <div class="col-md-1 col-xs-12">
            <button type="submit" class="btn btn-primary width-full">Search</button>
        </div>
    </div>
</form>