<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/favicon.png') }}">
    <title>MDWorking</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ Vite::asset('resources/scss/app.scss') }}">
    <!-- Styles -->
    <style lang="scss">
        .main {
            min-height: 100vh;
            object-fit: contain;
            background: url('{{ Vite::asset('resources/images/background.jpg') }}') no-repeat;
            background-size: cover;

            nav {
                .navbar-brand {
                    width: 200px;
                }

                .navbar-collapse {
                    ul {
                        li {
                            padding: 1rem;

                            a {
                                text-decoration: none;
                                text-transform: uppercase;
                                font-weight: 500;
                                color: black;
                            }
                        }
                    }
                }
            }

            > .container {
                min-height: 80vh;
            }
        }

        .footer {
            position: absolute;
            bottom: 0;
            background: #f2f2f2;
            color: #6d6d6d;
            width: 100vw;

            p {
                text-align: center;
                padding: 10px;
                margin: 0;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</head>
<body>
<div class="main">
    @include('layouts.landingPage.navbar')

    <div class="container">

    </div>
    <div class="footer">
        <p>
            © 2024 CÔNG TY TNHH Dịch Vụ Công Nghệ Minh Đức / GPĐKKD số 3002263320. Ngày cấp: 21/2/2023. Cơ quan cấp:
            Phòng
            Đăng ký kinh doanh - Sở Kế Hoạch và Đầu Tư tỉnh Hà Tĩnh.
        </p>
    </div>
</div>
</body>
</html>
