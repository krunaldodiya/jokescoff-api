@extends('layouts.master')

@section('content')

    <div class="main-container">
        <div class="container">
            <div class="row">

                <div class="col-sm-3 page-sidebar">
                    @include('admin.admin_left_bar')
                </div>

                <div class="col-sm-9">
                    
                    <div class="form-group clearfix" style="margin-top: 10px">
                        <a href="{{ route('admin-create-category') }}">
                            <button class="btn btn-md btn-primary pull-right">
                                Create
                            </button>
                        </a>
                    </div>

                    @if($root_categories)
                        <table class="table table-bordered">
                            @foreach($root_categories as $parent_category)
                                <thead>
                                <tr>
                                    <th data-toggle="collapse" data-target="#cat_{{ $parent_category->id }}">
                                        {{ $parent_category->name }}
                                        <small>({{ $parent_category->child->count() }})</small>
                                    </th>
                                    <th style="width: 120px; text-align: center">
                                        <a href="/admin/category/{{ $parent_category->id }}/edit">edit</a>
                                        <span>|</span>
                                        <a href="/admin/category/{{ $parent_category->id }}/delete">delete</a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="cat_{{ $parent_category->id }}" class="collapse">
                                @foreach($parent_category->child as $child_category)
                                    <tr>
                                        <td style="margin-left: 10px"> - {{ $child_category->name }}</td>
                                        <td style="width: 120px; text-align: center">
                                            <a href="/admin/category/{{ $child_category->id }}/edit">edit</a>
                                            <span>|</span>
                                            <a href="/admin/category/{{ $child_category->id }}/delete">delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @endforeach
                        </table>
                    @endif
                </div>

                @unless($root_categories)
                    <div class="col-sm-9" style="margin-top: 10px">
                        <b>No Categories Found.</b>
                    </div>
                @endunless
            </div>
        </div>
    </div>

@stop