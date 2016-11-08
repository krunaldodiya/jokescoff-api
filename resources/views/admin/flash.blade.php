<style>
    .message {
        padding: 10px;
        margin: 0px !important;
        border-radius: 0px !important;
    }

    .message-info {
        background: deepskyblue;
        color: white;
    }

    .message-danger {
        background: #d43f3a;
        color: white;
    }

    .message-warning {
        background: coral;
        color: white;
    }

    .message-default, .message-success {
        background: green;
        color: white;
    }
</style>

@if(session('message'))
    <div class="navbar-fixed-bottom text-center alert message message-{{ getMessage('type') }}" data-important="false">
        <button type="button" class="close" id="dismissMessage" data-dismiss="alert" area-hidden="true">&times;</button>
        <span>{!! getMessage() !!}</span>
    </div>

    <script type="text/javascript">
        setTimeout(function () {
            $(".message").fadeOut();
        }, 5000);
    </script>
@endif