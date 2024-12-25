@use('App\Models\Roles')

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="index3.html" class="brand-link">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> --}}
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->image ?? 'https://via.placeholder.com/150' }}" class="img-circle elevation-2"
                    style="height: 30px; width: 30px;" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('profile') }}" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if (auth()->user()->role_id == Roles::ROLE_LIBRARIAN || auth()->user()->role_id == Roles::ROLE_ADMIN)
                    @if (auth()->user()->role_id == Roles::ROLE_ADMIN)
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->path() == 'dashboard' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Thống kê, báo cáo
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.list') }}"
                                class="nav-link {{ request()->path() == 'users' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Quản lý người dùng
                                </p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('book.list') }}"
                            class="nav-link {{ request()->path() == 'books' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Quản lý sách
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('genres.list') }}"
                            class="nav-link {{ request()->path() == 'genres' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>
                                Quản lý thể loại
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('author.list') }}"
                            class="nav-link {{ request()->path() == 'author' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-edit"></i>
                            <p>
                                Quản lý tác giả
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('borrow.history.list') }}"
                            class="nav-link {{ request()->path() == 'borrow-histories' ? 'active' : '' }}">
                            <i class="nav-icon far fa-calendar-alt"></i>
                            <p>
                                Quản lý mượn trả
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('publiser.list') }}"
                            class="nav-link {{ request()->path() == 'publisers' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-print"></i>
                            <p>
                                Quản lý nhà xuất bản
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('book.language.list') }}"
                            class="nav-link {{ request()->path() == 'book-language' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-language"></i>
                            <p>
                                Danh sách ngôn ngữ
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('late.return.list') }}"
                            class="nav-link {{ request()->path() == 'late-returns' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>
                                Danh sách trả muộn
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fines.list') }}"
                            class="nav-link {{ request()->path() == 'fines' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>
                                Danh sách phạt
                            </p>
                        </a>
                    </li>
                    @if (auth()->user()->role_id == Roles::ROLE_ADMIN)
                        <li class="nav-item">
                            <a href="{{ route('membership.plan.list') }}"
                                class="nav-link {{ request()->path() == 'membership-plans' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-id-card"></i>
                                <p>
                                    Danh sách gói hội viên
                                </p>
                            </a>
                        </li>
                    @endif
                @endif
                @if (auth()->user()->role_id == Roles::ROLE_USER)
                    <li class="nav-item">
                        <a href="{{ route('book.search') }}"
                            class="nav-link {{ request()->path() == 'books/search-book' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Tra cứu sách
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('borrow.history.list') }}"
                            class="nav-link {{ request()->path() == 'borrow-histories' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                Lịch sử mượn trả
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('late.return.list') }}"
                            class="nav-link {{ request()->path() == 'late-returns' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>
                                Danh sách trả muộn
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fines.list') }}"
                            class="nav-link {{ request()->path() == 'fines' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>
                                Danh sách phạt
                            </p>
                        </a>
                    </li>
                @endif
        </nav>
    </div>
</aside>
