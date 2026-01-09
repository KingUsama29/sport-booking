<nav class="navbar navbar-expand-lg naisya-nav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="naisya-brand-icon"><i class="bi bi-lightning-charge-fill"></i></span>
            <span class="fw-semibold">Naisya Sport Booking</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            {{-- LEFT --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fields.index') }}">
                        <i class="bi bi-grid-1x2-fill me-1"></i> Lapangan
                    </a>
                </li>

                @auth
                    {{-- USER ONLY --}}
                    @if (auth()->user()->role !== 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.index') }}">
                                <i class="bi bi-calendar-check me-1"></i> Booking Saya
                            </a>
                        </li>
                    @endif

                    {{-- ADMIN MENU --}}
                    @if (auth()->user()->role === 'admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-semibold text-danger" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-shield-lock-fill me-1"></i> Admin Panel
                            </a>
                            <ul class="dropdown-menu shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.fields.index') }}">
                                        <i class="bi bi-building me-2"></i>Kelola Lapangan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.bookings.index') }}">
                                        <i class="bi bi-clipboard-check me-2"></i>Kelola Booking
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endauth
            </ul>

            {{-- RIGHT --}}
            <ul class="navbar-nav ms-auto align-items-lg-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn naisya-btn naisya-btn-primary btn-sm rounded-pill px-3"
                            href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </a>
                    </li>
                @else
                    {{-- DASHBOARD BUTTON --}}
                    <li class="nav-item me-lg-2">
                        @if (auth()->user()->role === 'admin')
                            <a class="btn btn-sm rounded-pill px-3 btn-danger" href="{{ route('admin.fields.index') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Admin Dashboard
                            </a>
                        @else
                            <a class="btn naisya-btn naisya-btn-soft btn-sm rounded-pill px-3"
                                href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        @endif
                    </li>

                    {{-- USER DROPDOWN --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <span class="naisya-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li class="dropdown-item-text small text-muted">
                                Role:
                                <span class="fw-semibold text-capitalize">
                                    {{ auth()->user()->role }}
                                </span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
