@php
    use App\Enums\Menu;
@endphp

<div class="d-lg-flex flex-lg-column-reverse">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container position-lg-relative">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <img width="200" src="{{ Vite::asset('resources/images/logo.svg') }}" alt="Logo">
            </a>
            <div class="position-lg-absolute end-lg-0 me-lg-4">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fa fa-sign-in me-2"></i>
                    <span class="d-none d-lg-inline">Đăng nhập</span>
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown p-3">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Sản phẩm
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Item 1</a></li>
                            <li><a class="dropdown-item" href="#">Item 2</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown p-3">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Công cụ
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Tool 1</a></li>
                            <li><a class="dropdown-item" href="#">Tool 2</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="border-top border-light"></div>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <div class="collapse flex-row-reverse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    @foreach (Menu::LANDING_PAGE as $item)
                        <li class="nav-item">
                            <a href="{{ route($item['routeName']) }}">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                    <li>
                        <a href="tel:0865756088" alt="Hotline" rel="nofollow"><i class="fa fa-phone me-2"></i> 086
                            575 6088</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
