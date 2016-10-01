@extends('guest')

@section('content')
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <img class="img-circle profile_img" src="/images/bsc.png" alt="EVE Logo" style="margin: 0 auto; height: 125px; width: 125px;" />
                <form>
                    <h1>Oops.</h1>
                    <p>An error occurred while trying to authenticate you with EVE's Single Sign On, lets try again!</p>
                    <hr />
                    <a href="/auth/redirect">
                        <img class="eve-btn" src="https://images.contentful.com/idjq7aai9ylm/4fSjj56uD6CYwYyus4KmES/4f6385c91e6de56274d99496e6adebab/EVE_SSO_Login_Buttons_Large_Black.png?w=270&h=45" />
                    </a>
                </form>
            </section>
        </div>
    </div>
@stop