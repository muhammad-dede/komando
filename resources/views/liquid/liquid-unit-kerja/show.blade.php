@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/tree-multiselect/jquery.tree-multiselect.min.css') }}" rel="stylesheet">
    <style>
        div.tree-multiselect > div.selections .auxiliary, div.tree-multiselect > div.selections .section,
        div.tree-multiselect span.remove-selected, .btn.btn-primary.btn-lg {
            pointer-events: none;
        }

    </style>


@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Show Liquid #{{ $liquid->id }}</h4>
        </div>
        <div class="col-md-6 col-xs-12 lh-70 align-right">
            <a href="{{ url('liquid/'.Request::segment(2).'/edit') }}" class="btn btn-warning"><em
                        class="fa fa-pencil"></em> Edit Liquid</a>
            <a href="{{ url('dashboard-admin/liquid-jadwal') }}" class="btn btn-primary"><em class="fa fa-arrow-right"></em> Back Dashboard</a>
        </div>
    </div>
@stop

@section('content')

    @include('components.flash')

    <div class="row card-box">
        <div class="col-md-12">
            @include('components.liquid-tab-show', ['active' => 'unit-kerja'])
            <div class="tab-content comp-tab-content">
                <div class="row">
                    <div class="col-xs-6 mar-b-1rem overflow-v-450">
                        <ul class="list-group">
                            <li class="list-group-item list-selection">Unit Kerja</li>
                            @foreach($businessAreas as $area)
                                @if(in_array($area->business_area, $selected))
                                    <li class="list-group-item">{{ $area->description }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/tree-multiselect/jquery.tree-multiselect.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var options = {
                sortable: true, searchable: true,
                // Enables selection of all or no options
                enableSelectAll: true,

                // Only used if enableSelectAll is active
                selectAllText: 'Select All',

                // Only used if enableSelectAll is active
                unselectAllText: 'Unselect All'
            };
            $("select#unitKerja").treeMultiselect(options);
        });
    </script>
@endpush
