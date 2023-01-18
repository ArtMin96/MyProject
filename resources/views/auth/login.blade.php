@extends('front.layouts.app',['mid_body'=>true])

@section('content')
    <div class="loreg_page">
        <div class="container vert_mid">
            <div class="row w100 w100m">
                <div class="col-12 col-md-6 col-lg-4 md_mid_col">
                    <div class="loreg_form">

                        <div class="login">
                            <div class="text-center login_title">
                                <a href="">
                                    <h3 class="heading">
                                        @lang('messages.login')
                                    </h3>
                                </a>

                            </div>
                            <form method="post" action="{{ route('login') }}">
                                @csrf
                                @if (session('val_mess'))
                                    <span class="alert-text text-danger">{{ session()->get('val_mess') }}</span>
                                @endif
                                <input class="loreg_input w100" type="email" name="email"
                                    placeholder="@lang('messages.email')" required="required" />
                                <input type="password" class="loreg_input w100" name="password"
                                    placeholder="@lang('messages.password')" required="required" />

                                <input type="hidden" name="next_url" value="{{ session('next_url') }}">
                                <div class=" text-center login_btn modal_registr_button">
                                    <button type="submit" class="red_button w100">
                                        @lang('messages.login')
                                    </button>
                                </div>
                            </form>

                            {{-- <div class="separator">{{__('messages.or')}}</div>
                        <div class="social_login_buttons">
                            <a href="{{route('social.login',['google'])}}"><i class="fab fa-google"></i> Sign in with google</a>
                        </div>
                        <div class="social_login_buttons">
                            <a href="{{route('social.login',['facebook'])}}"><i class="fab fa-facebook-f"></i> Sign in with facebook</a>
                        </div>
                        <div class="social_login_buttons mb-0">
                            <a href="{{route('social.login',['apple'])}}"><i class="fab fa-apple"></i> Sign in with Apple</a>
                        </div> --}}

                        </div>
                        <p class="loreg_footer">
                            <a href="{{ route('register') }}"> <span
                                    class="">@lang('messages.register')</span></a>

                            @if (Route::has('password.request'))
                                <a class="d-block" href="{{ route('password.request') }}">
                                    @lang('messages.forgot_password')
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-8 d-flex loreg_right_bar">
                    <div class="vert_mid" style="justify-content: right;width: 100%;"><img class="img-fluid"
                            src="/front/assets/img/logo_huge.png"></div>
                </div>

            </div>
        </div>
    </div>

@stop
