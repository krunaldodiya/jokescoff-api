@extends('layouts.master')

@section('content')

    <div class="container">
        <form role="form" name="signin" id="signin" method="POST">

            <fieldset style="width: 300px; margin: 20px auto auto;">

                <div class="form-group">
                    <label for="email" class="control-label">Email / Mobile:</label>

                    <div class="input-icon">
                        <input id="email" name="email" type="text" placeholder="Email or Mobile"
                               class="form-control" required="required">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="control-label">Password:</label>

                    <div class="input-icon">
                        <input type="password" class="form-control" placeholder="Password"
                               id="password" name="password" required="required" minlength="4">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block" id="loginButton">
                        Log In
                    </button>
                </div>

            </fieldset>

        </form>
    </div>

@stop