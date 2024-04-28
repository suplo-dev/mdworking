@php
    use App\Enums\Menu;
@endphp
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
        .wrapper {
            display: grid;
            grid-template-areas:
        "sidebar header"
        "sidebar main";
            grid-template-columns: 280px 1fr;
            grid-template-rows: auto 1fr;
            height: 100vh;

            .header {
                grid-area: header;
            }

            .sidebar {
                grid-area: sidebar;
                padding: 15px;
                height: 100%;

                i {
                    width: 20px;
                    height: 20px;
                }
            }

            .main {
                grid-area: main;
                background-color: #ececec;
                overflow-y: auto;
            }

        }
    </style>
    @stack('css')
    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>
<body>
<div class="wrapper fw-medium">
    <!-- Header with user information -->
    <header class="header bg-light shadow-sm z-3">
        <div class="d-flex align-items-center bg-light p-3 shadow-sm">
            <ul class="d-flex list-unstyled ms-auto mb-0">
                <li>
                    <div class="dropdown">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                            <img width="32" height="32" class="rounded-circle" src="{{ asset('assets/img/background.jpg') }}" alt="">
                            <span class="mx-2">{{ Auth::user()->name }}</span> <!-- Assuming Auth::user() gives the logged-in user -->
                        </button>
                        <ul class="dropdown-menu mt-2">
                            <li class="dropdown-item cursor-pointer">
                                <a class="nav-link text-decoration-none" href="{{ route('profile') }}">
                                    <i class="fa-regular fa-user me-2"></i>
                                    <span>Hồ sơ</span>
                                </a>
                            </li>
                            <li class="border-gray border-bottom my-2"></li>
                            <li class="dropdown-item cursor-pointer">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="nav-link text-decoration-none" type="submit">
                                        <i class="fa-regular fa-sign-out me-2"></i>
                                        <span>Đăng xuất</span>
                                    </button>
                                </form>

                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </header>

    <!-- Sidebar with navigation links -->
    <aside class="sidebar shadow">
        <a class="d-flex justify-content-center p-3" href="{{ route('dashboard') }}">
            <img width="150" src="{{ asset('assets/img/logo.svg') }}" alt="Logo">
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
            @foreach(Menu::DASHBOARD as $item)
                @if(isset($item['children']))
                    <a class="nav-link rounded collapsed position-relative" data-bs-toggle="collapse" aria-expanded="true">
                        <i class="fa me-2 {{ $item['iconClass'] }}"></i>
                        {{ $item['label'] }}
                        <i class="fa-regular fa-chevron-down position-absolute end-0"></i>
                    </a>
                    <div class="show mt-1">
                        <ul class="btn-toggle-nav list-unstyled">
                            @foreach($item['children'] as $child)
                                <li>
                                    <a class="nav-link {{request()->routeIs([...$child['routeRelated'], $child['routeName']]) ? 'active' : ''}}" href="{{ route($child['routeName']) }}">
                                        <i class="fa ps-3 me-4 {{ $child['iconClass'] }}"></i>
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(in_array(data_get('permission', $item), Auth::user()->permissions->pluck('name')->toArray() ?? []))
                    <a class="nav-link {{request()->routeIs($item['routeName']) ? 'active' : ''}}" href="{{ route($item['routeName']) }}">
                        <i class="fa me-2 {{ $item['iconClass'] }}"></i>
                        {{ $item['label'] }}
                    </a>
                @else
                    <a class="nav-link {{request()->routeIs($item['routeName']) ? 'active' : ''}}" href="{{ route($item['routeName']) }}">
                        <i class="fa me-2 {{ $item['iconClass'] }}"></i>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main">
        @yield('content') <!-- Allows other Blade templates to extend this -->
    </main>
</div>
</body>
</html>
