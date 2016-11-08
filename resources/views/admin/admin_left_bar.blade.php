<style>
    ul.sidemenu {
        list-style: none;
        padding: 0px;
        margin: 30px 0px 0px;
    }

    ul.sidemenu li {
        margin-top: 5px;
        padding: 10px;
        border: 1px solid #dddddd;
    }
</style>

<div style="padding-left: 5px; margin-top: 10px">
    <b style="text-transform: capitalize">Hi, {{ auth()->user()->name }}, <a href="/logout">Logout</b></a>
</div>

<div style="text-align: left">
    <ul class="sidemenu">
        <li class="#"><a
                    href="/admin/dashboard">
                <i class="fa fa-tags"></i> Dashboard </a>
        </li>
        <li class="#"><a
                    href="/admin/category/show">
                <i class="fa fa-tags"></i> Categories </a>
        </li>
        <li class="#">
            <a href="/admin/posts/show">
                <i class="fa fa-edit"></i> Posts </a>
        </li>
    </ul>
</div>