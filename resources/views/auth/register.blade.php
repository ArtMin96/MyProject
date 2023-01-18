@extends('front.layouts.app')

@section('content')
<div class="loreg_page">
    <div class="container vert_mid" >
            <div class="row w100 w100m">
            <div class="col-12 col-md-6 col-lg-4 md_mid_col">
            <div class="loreg_form">
                <div class="login">
                    <div class="text-center login_title">
                    <a href="">
                        <h3 class="heading">
                            @lang('messages.register')
                        </h3>
                    </a>
                </div>
                <form class="register_form" method="POST" action="{{ route('register') }}">
                        @csrf
                        @if(session('val_mess'))
                            <span class="alert-text text-danger">{{session()->get('val_mess')}}</span>
                        @endif

                        <input id="name" type="text" class="loreg_input w100 form-control @error('name') is-invalid @enderror" oninvalid="this.setCustomValidity('Տվյալ դաշտը լրացնելը պարտադիր է')" oninput="this.setCustomValidity('');this.blur();this.focus();"
                       name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Անուն" autofocus>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <input id="last_name" type="text" class="loreg_input w100 form-control @error('last_name') is-invalid @enderror" oninvalid="this.setCustomValidity('Տվյալ դաշտը լրացնելը պարտադիր է')" oninput="this.setCustomValidity('');this.blur();this.focus();"
                       name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" placeholder="Ազգանուն" autofocus>
                @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <input id="email" type="email" class="loreg_input w100 form-control @error('email') is-invalid @enderror" name="email" oninvalid="this.setCustomValidity('Տվյալ դաշտում պարտադիր են @ ․ նշանները')" oninput="this.setCustomValidity('');this.blur();this.focus();"
                value="{{ old('email') }}" autocomplete="email" placeholder=" Էլ. հասցե" aria-placeholder="">
                @error('email')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror

                <input id="phone" type="phone" class="loreg_input w100 form-control @error('phone') is-invalid @enderror" name="phone" oninvalid="this.setCustomValidity('Տվյալ դաշտը լրացնելը պարտադիր է')" oninput="this.setCustomValidity('');this.blur();this.focus();"
                value="{{ old('phone') }}" autocomplete="phone" placeholder="{{__('messages.phone')}}" aria-placeholder="">
                @error('phone')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror

                <input id="password" type="password" class="loreg_input w100 form-control @error('password') is-invalid @enderror" oninvalid="this.setCustomValidity('Տվյալ դաշտը լրացնելը պարտադիր է')" oninput="this.setCustomValidity('');this.blur();this.focus();"
                                            name="password" required autocomplete="new-password" placeholder="Գաղտնաբառ">
                @error('password')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror

                <input id="password-confirm" type="password" class="loreg_input w100 form-control" name="password_confirmation" oninvalid="this.setCustomValidity('Տվյալ դաշտը լրացնելը պարտադիր է')" oninput="this.setCustomValidity('');this.blur();this.focus();"
                       required autocomplete="new-password" placeholder="Հաստատել գաղտնաբառը">

                        <div class=" text-center mt-4 modal_registr_button">
                            <button type="submit" class="red_button w100">
                                @lang('messages.register')
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
                    {{__('messages.already_registered')}} <a href="#"> <span class="">{{__('messages.login_to')}}</span></a>
                </p>
            </div>
        </div>
        <div class="col-12 col-md-8 d-flex loreg_right_bar">
            <div class="vert_mid" style="justify-content: right;width: 100%;"><img class="img-fluid" src="/front/assets/img/logo_huge.png"></div>
        </div>

        </div>
    </div>
</div>


@endsection
