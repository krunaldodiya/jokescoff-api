@extends('layouts.master')

@section('content')

    <div class="main-container">
        <div class="container">
            <div class="row">

                <div class="col-sm-3 page-sidebar">
                    @include('admin.admin_left_bar')
                </div>

                <div class="col-sm-9" style="margin-top: 60px">

                    <form method="POST" class="form-horizontal" enctype="multipart/form-data">

                        <div class="form-group required {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="col-md-3 control-label" for="name">Category Name
                                <sup>*</sup></label>

                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control"
                                       placeholder="Category Name">
                                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group required {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            <label class="col-md-3 control-label" for="parent_id">Parent Category
                                <sup>*</sup></label>

                            <div class="col-md-8">
                                <select name="parent_id" class="form-control">
                                    <option value="0">None</option>
                                    @foreach($root_categories as $selected_category)
                                        <option value="{{ $selected_category->id }}">{{ $selected_category->name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('parent_id', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group required {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            <label class="col-md-3 control-label" for="parent_id">Category Image
                                <sup>*</sup></label>

                            <div class="col-md-8">
                                <input type="file" name="category_image">
                                {!! $errors->first('category_image', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-8">
                                <button type="submit" class="btn btn-md btn-primary">Create</button>
                                <a class="btn btn-md btn-warning" href="{{ route('admin-show-category') }}">Go Back</a>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

@stop