@extends('layouts.master')

@section('content')

    <div class="main-container">
        <div class="container">
            <div class="row">

                <div class="col-sm-3 page-sidebar">
                    @include('admin.admin_left_bar')
                </div>

                <div class="col-sm-9">
                    <h5 class="title-2">Home</h5>
                </div>

            </div>
        </div>
    </div>

@stop