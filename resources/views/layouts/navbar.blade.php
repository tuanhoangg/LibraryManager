<!-- Navbar -->
@php
    use App\Models\Roles;
@endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="{{ route('profile') }}" class="dropdown-item">
                    Thông tin cá nhân
                </a>
                @if (auth()->user()->role_id == Roles::ROLE_USER)
                    <a href="{{ route('membership.select.plan') }}" class="dropdown-item">
                        Gói hội viên
                    </a>
                @endif
                @if (auth()->user()->role_id == Roles::ROLE_ADMIN)
                    <a href="{{ route('batches.index') }}" class="dropdown-item">
                        Chạy tự động
                    </a>
                @endif
                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-change-password">
                    Đổi mật khẩu
                </a>
                <a href="/logout" class="dropdown-item">
                    Đăng xuất
                </a>
            </div>
        </li>
    </ul>
</nav>
<change-password-modal></change-password-modal>
@pushOnce('scripts')
@endPushOnce
