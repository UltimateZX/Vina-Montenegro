<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viña Montenegro</title>
    
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #f8f9fa;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main { flex-grow: 1; }
        
        /* --- Header PC --- */
        header {
            background: #b42a6a;
            color: white;
            padding: 10px 25px;
            display: grid;
            grid-template-columns: 1fr auto 1fr; /* 3 Columnas */
            align-items: center;
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        header a { color: white; text-decoration: none; transition: opacity 0.2s; }
        header a:hover { opacity: 0.8; }
        
        .header-left .logo { font-size: 1.5em; font-weight: bold; white-space: nowrap; }
        
        .header-search {
            width: 500px; /* Ancho PC */
        }
        .header-search form { display: flex; }
        .header-search input {
            width: 100%; padding: 10px 15px; border: none; border-radius: 20px 0 0 20px; outline: none;
        }
        .header-search button {
            border: none; padding: 0 15px; background: #fff; cursor: pointer; border-radius: 0 20px 20px 0;
        }
        
        .header-nav {
            display: flex; justify-content: flex-end; align-items: center; gap: 15px;
        }
        .header-nav a { font-weight: 500; font-size: 0.95em; }
        
        .cart-toggle { background: none; border: none; color: white; font-size: 1.5em; cursor: pointer; }

        /* Menú de Usuario */
        .user-menu { position: relative; display: inline-block; }
        .user-menu-btn { background: none; border: none; color: white; font-weight: 500; cursor: pointer; font-size: 1em; }
        .user-menu-content {
            display: none; position: absolute; background-color: white; min-width: 180px; /* Un poco más ancho */
            box-shadow: 0 8px 16px rgba(0,0,0,0.2); border-radius: 5px; right: 0; z-index: 101;
        }
        .user-menu-content a, .user-menu-content button {
            color: black; padding: 12px 16px; text-decoration: none; display: block;
            background: none; border: none; width: 100%; text-align: left; font-size: 0.95em; cursor: pointer;
        }
        .user-menu-content a:hover, .user-menu-content button:hover { background-color: #f1f1f1; }
        
        /* Estilo especial para el enlace de Admin dentro del menú */
        .admin-menu-link { color: #b42a6a !important; font-weight: bold; border-bottom: 1px solid #eee; }

        /* --- 📱 CSS RESPONSIVO (CELULAR) --- */
        @media (max-width: 900px) {
            header {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: auto auto;
                grid-template-areas: 
                    "logo nav"
                    "search search";
                padding: 10px 15px;
                gap: 10px;
            }
            
            .header-left { grid-area: logo; }
            .header-nav { 
                grid-area: nav; 
                gap: 10px; 
                justify-content: flex-end;
            }
            
            .header-search {
                grid-area: search;
                width: 100%;
                margin-top: 5px;
            }
            
            .header-nav a { font-size: 0.85em; white-space: nowrap; }
            
            /* Ocultamos el enlace Admin de la barra superior en celular (ya está en el menú) */
            .header-nav .admin-top-link { display: none; } 
            
            .header-nav a span { display: none; } 
            .header-left .logo { font-size: 1.2em; }
        }

        /* Mini Carrito Offcanvas */
        #miniCartBackdrop { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        #miniCartPartial {
            display: block; position: fixed; top: 0; right: -100%;
            width: 85%; max-width: 380px; 
            height: 100%; background: white; z-index: 1001; transition: right 0.3s ease;
        }
        
        footer { background: #343A40; color: #f8f9fa; padding: 40px 20px; text-align: center; }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <a href="/" class="logo">Viña Montenegro</a>
        </div>
        
        <div class="header-search">
            <form action="{{ route('home') }}" method="GET">
                <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
                <button type="submit">🔍</button>
            </form>
        </div>

        <nav class="header-nav">
            @php $cartCount = count(session()->get('cart', [])); @endphp
            
            <a href="{{ route('cart.index') }}" title="Ver Carrito" style="display: flex; align-items: center; gap: 5px;">
                🛒 <span style="font-size: 0.9em;">({{ $cartCount }})</span>
            </a>
            
            @guest
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" style="border: 1px solid white; padding: 5px 10px; border-radius: 4px;">Registro</a>
            @endguest
            
            @auth
                <!-- Enlace Admin visible SOLO en PC (clase admin-top-link) -->
                @if(auth()->user()->rol == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="admin-top-link">⚙️ Admin</a>
                @endif
                
                <div class="user-menu">
                    <button class="user-menu-btn" onclick="toggleUserMenu()">
                        👤 
                        <span style="display: inline-block; @media(max-width:768px){display:none;}">
                             {{ Str::limit(auth()->user()->nombre_completo, 10) }}
                        </span> &#9662;
                    </button>
                    
                    <div class="user-menu-content" id="userDropdown">
                        
                        <!-- ¡AQUÍ ESTÁ! Enlace Admin dentro del menú (Visible en Celular y PC) -->
                        @if(auth()->user()->rol == 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="admin-menu-link">⚙️ Panel Admin</a>
                        @endif
                        
                        <a href="{{ route('profile.edit') }}">Mi Perfil</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Cerrar Sesión</button>
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
    </footer>

    <div id="miniCartBackdrop" onclick="toggleMiniCart()"></div>
    <div id="miniCartPartial">
        @include('partials._mini-cart')
    </div>
    
    <script>
        function toggleUserMenu() {
            var d = document.getElementById('userDropdown');
            d.style.display = d.style.display === 'block' ? 'none' : 'block';
        }
        window.onclick = function(e) {
            if (!e.target.matches('.user-menu-btn') && !e.target.matches('.user-menu-btn *')) {
                document.getElementById('userDropdown').style.display = 'none';
            }
        }
        function toggleMiniCart() {
            var cart = document.getElementById('miniCartPartial');
            var back = document.getElementById('miniCartBackdrop');
            if (cart.style.right === '0px') {
                cart.style.right = '-100%';
                back.style.display = 'none';
            } else {
                cart.style.right = '0px';
                back.style.display = 'block';
            }
        }
    </script>
</body>
</html>