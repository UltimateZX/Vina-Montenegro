<!DOCTYPE html>
<html lang="es">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viña Montenegro</title>

    <style>

    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <a href="/" class="logo">Viña Montenegro</a>
        </div>

        <div class="header-search">
            <form action="{{ route('home') }}" method="GET">
                <input type="text" name="search" placeholder="Buscar en Viña Montenegro..." value="{{ request('search') }}">
                <button type="submit">🔍</button>
            </form>
        </div>

        <nav class="header-nav">
            @php $cartCount = count(session()->get('cart', [])); @endphp
            <a href="{{ route('cart.index') }}"
                    class="p-2 border rounded-2xl border-white transition-all duration-300 hover:bg-white hover:text-[#b42a6a]" title="Ver Carrito">
                🛒 Carrito ({{ $cartCount }})
            </a>

            @guest
                <a href="{{ route('login') }}"
                    class="p-2 border rounded-2xl border-white transition-all duration-300 hover:bg-white hover:text-[#b42a6a]">👤 Login</a>
                <a href="{{ route('register') }}"
                    class="p-2 border rounded-2xl border-white transition-all duration-300 hover:bg-white hover:text-[#b42a6a]">Registro</a>
            @endguest

            @auth
                @if(auth()->user()->rol == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="admin-link">
                        ⚙️ Panel Admin
                    </a>
                @endif

                <div class="user-menu">
                    <button class="user-menu-btn" onclick="toggleUserMenu()">
                        {{ auth()->user()->nombre_completo }} &#9662;
                    </button>
                    <div class="user-menu-content" id="userDropdown">
                        <a href="{{ route('profile.edit') }}">Mi Perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            @endauth

            <button class="cart-toggle" onclick="toggleMiniCart()">&#9776;</button>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>Viña Montenegro © 2025</p>
        <p>Contactos, información, etc.</p>
    </footer>

    <div id="miniCartBackdrop" onclick="toggleMiniCart()"></div>
    <div id="miniCartPartial">
        @if(count(session()->get('cart', [])) > 0)
            @include('partials._mini-cart')
        @else
            <div class="p-20 text-center">Tu carrito está vacío.</div>
        @endif
    </div>


</body>
</html>
