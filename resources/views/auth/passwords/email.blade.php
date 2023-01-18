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
                                        @lang('messages.reset')
                                    </h3>
                                </a>

                            </div>

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email"
                                            placeholder="@lang('messages.email')" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class=" text-center login_btn modal_registr_button">
                                    <button type="submit" class="red_button w100">
                                        @lang('messages.next')
                                    </button>
                                </div>
                            </form>

                        </div>

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
