@extends('layout')

@section('css')
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>

@section('title')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h4 class="page-title">Master Data Manual Book & FAQ</h4>
        </div>
    </div>
@stop

@section('content')

    @include('components.flash')

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <div class="tab-content comp-tab-content">
                    <form method="POST" action="{{ route('faq-manual-book.update') }}">
                        {!! csrf_field() !!}
                        {{--{!! method_field('PUT') !!}--}}
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label>Manual Book Untuk Admin Liquid Root</label>
                                    <div>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   name="manual-book-admin-root"  placeholder="Link manual book untuk admin liquid root"
                                                   value="{{ $manual_book_root }}">
                                            <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-book-open"></em></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Manual Book Untuk Admin Liquid Unit Pelaksana</label>
                                    <div>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   name="manual-book-admin-unit-pelaksana"  placeholder="Link manual book untuk admin unit pelaksana"
                                                   value="{{ $manual_book_unit }}">
                                            <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-book-open"></em></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Manual Book Untuk Atasan</label>
                                    <div>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   name="manual-book-dashboard-atasan"  placeholder="Link manual book untuk atasan"
                                                   value="{{ $manual_book_atasan }}">
                                            <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-book-open"></em></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Manual Book Untuk Bawahan</label>
                                    <div>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   name="manual-book-dashboard-bawahan"  placeholder="Link manual book untuk Bawahan"
                                                   value="{{ $manual_book_bawahan }}">
                                            <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-book-open"></em></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>FAQ</label>
                                    <div>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   name="faq"  placeholder="Link FAQ"
                                                   value="{{ $faq }}">
                                            <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-question"></em></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg">
                                    <i aria-hidden="true" class="fa fa-arrow-right"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop


