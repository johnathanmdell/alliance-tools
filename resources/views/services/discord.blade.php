@extends('app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Discord</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>Connect to <a href="https://discord.gg/Sd7rYYY">Black Sheep Coalition Discord</a>, once you are in the lobby you can message the bot (Lola) with the authentication code below.</p>
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{ $userService->pivot->auth_key }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop