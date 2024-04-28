<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/favicon.png') }}">

    <title>Đăng nhập</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ Vite::asset('resources/scss/app.scss') }}">
    <!-- Styles -->
    <style lang="scss">
        .main {
            .container {
                display: flex;
                justify-content: space-between;
                gap: 200px;

                .container-left {
                    width: 100%;
                    height: 100vh;
                    background: url({{Vite::asset('resources/images/background.jpg')}}) center no-repeat;
                    background-size: 700px;
                }

                .container-right {
                    width: 100%;
                    margin: auto;

                    .logo {
                        width: 250px;
                        margin: 0 auto;
                    }

                    .title {
                        text-align: center;
                        color: #003469;
                        font-weight: bold;
                        font-size: 1.25rem;
                        margin: 3rem;
                    }

                    a {
                        text-decoration: none;
                    }
                }
            }
        }
    </style>
</head>
<body>
<div class="main">
    <div class="container">
        <div class="container-left">
        </div>
        <div class="container-right">
            <div class="row mt-5">
                <img class="logo" src="{{ Vite::asset('resources/images/logo.svg') }}" alt="logo"/>
            </div>
            <div class="title">Chào mừng bạn đến với MDWorking</div>

            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group my-3">
                    <label class="form-label">Email</label>
                    <x-text-input type="email" class="form-control" name="email" placeholder="Nhập email"
                                  value="{{ old('email') }}"
                                  :error="$errors->get('email')"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>
                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <x-text-input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu"
                                  :error="$errors->get('password')"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                </div>
                <div class="d-flex justify-content-between my-3">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Nhớ tài khoản</label>
                    </div>
                    <div class="form-group">
{{--                        <a href="{{ route('password.request') }}">Quên mật khẩu?</a>--}}
                    </div>
                </div>
                <div class="d-flex justify-content-center my-5">
                    <button class="btn btn-primary" type="submit">Đăng nhập</button>
                </div>
            </form>

            <div class="d-flex justify-content-center">
                <span>Bạn chưa có tài khoản? <a href="{{ route('register') }}">Đăng kí ngay</a></span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
