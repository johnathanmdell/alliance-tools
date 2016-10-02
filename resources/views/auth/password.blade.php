@extends('guest')

@section('content')
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <img class="img-circle profile_img" src="https://image.eveonline.com/Character/{{ auth()->user()->characters()->primary()->first()->id }}_128.jpg" alt="EVE Character" style="margin: 0 auto; height: 125px; width: 125px;" />
                <hr />
                <form method="post" action="{{ route('_auth_post_password') }}">
                    {!! csrf_field() !!}
                    <h1>Account Protection</h1>
                    <div>
                        <input type="email_address" id="email_address" name="email_address" class="form-control" placeholder="Email Address" />
                    </div>
                    <br/>
                    <div>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                    </div>
                    <div>
                        <button class="btn btn-default submit" type="submit">Create Account</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@stop