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
                        <span style="margin-top: 10px; position: absolute;">Total <b>({{ $posts->total() }}
                                )</b> posts</span>

                        <a href="{{ route('admin-create-posts') }}">
                            <button class="btn btn-md btn-primary pull-right">
                                Create
                            </button>
                        </a>
                    </div>

                    @if(count($posts))
                        <table class="table table-bordered table-responsive">
                            <tbody>
                            <thead>
                            <th>No.</th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Time/Date</th>
                            <th class="text-center">Action</th>
                            </thead>
                            </tbody>
                            <tbody>
                            @foreach($posts as $index => $post)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->category->name }}</td>
                                    <td>{{ $post->updated_at->diffForHumans() }}</td>
                                    <td class="text-center" style="width: 120px">
                                        <a href="{{ route('admin-update-posts', [$post->id]) }}">edit</a>
                                        <span>|</span>
                                        <a href="javascript:void(0)"
                                           onclick="deleteConfirm('{{ route('admin-destroy-posts', [$post->id]) }}')">delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="pagination-bar text-center">
                {{ $posts->links() }}
            </div>

        </div>
    </div>

@stop

<script type="text/javascript">
    function deleteConfirm(src) {
        if (confirm("Are you sure want to delete ?")) {
            location.href = src;
        }
    }
</script>