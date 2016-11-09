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
                            <label class="col-md-3 control-label" for="name">Title
                                <sup>*</sup></label>

                            <div class="col-md-8">
                                <input type="text" name="title" value="" class="form-control"
                                       placeholder="Title">
                                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group required {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label class="col-md-3 control-label" for="description">Description
                                <sup>*</sup></label>

                            <div class="col-md-8">
                            <textarea name="description" class="form-control"
                                      placeholder="Description"></textarea>
                                {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group required {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            <label class="col-md-3 control-label" for="parent_id">Category
                                <sup>*</sup></label>

                            <div class="col-md-8">
                                <select name="category_id" class="form-control">
                                    @foreach($root_categories as $selected_category)
                                        <option value="{{ $selected_category->id }}">
                                            {{ $selected_category->name }}
                                        </option>

                                        @foreach($selected_category->child as $selected_category)
                                            <option value="{{ $selected_category->id }}">
                                                {{ $selected_category->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                                {!! $errors->first('parent_id', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group required {{ $errors->has('post_image') ? 'has-error' : '' }}">
                            <label class="col-md-3 control-label" for="post_image">Image
                                <sup>*</sup></label>

                            <div class="col-md-8">
                                <input type="file" name="post_image">
                                {!! $errors->first('post_image', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-8">
                                <button type="submit" class="btn btn-md btn-primary">Update</button>
                                <a class="btn btn-md btn-warning" href="{{ route('admin-show-posts') }}">Go Back</a>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

@stop