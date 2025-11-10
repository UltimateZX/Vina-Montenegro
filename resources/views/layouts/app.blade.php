<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viña Montenegro</title>
    
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background: #f8f9fa; /* Tono gris claro */
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex-grow: 1; /* Empuja el footer hacia abajo */
        }
        
        /* --- Header (¡LA PARTE MÁS IMPORTANTE!) --- */
        header {
            background: #b42a6a;
            color: white;
            padding: 0 25px; /* Más padding lateral */
            display: grid; /* ¡Usamos CSS Grid! */
            
            /* Define 3 columnas:
               1. Izquierda: '1fr' (espacio flexible)
               2. Centro: 'auto' (lo que ocupe la barra de búsqueda)
               3. Derecha: '1fr' (espacio flexible)
            */
            grid-template-columns: 1fr auto 1fr;
            
            align-items: center;
            height: 70px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        header a { color: white; text-decoration: none; }
        
        .header-left { /* Columna 1 */
            grid-column: 1;
        }
        .header-left .logo { font-size: 1.5em; font-weight: bold; }
        
        .header-search { /* Columna 2 */
            grid-column: 2;
            width: 500px; /* Ancho fijo para la barra */
            margin: 0 auto; /* Centra la barra en su columna */
        }
        .header-search form { display: flex; }
        .header-search input {
            width: 100%;
            padding: 10px 15px;
            border: none;
            border-radius: 20px 0 0 20px;
            outline: none;
            font-size: 0.9em;
        }
        .header-search button {
            border: none;
            padding: 0 15px;
            background: #fff;
            cursor: pointer;
            border-radius: 0 20px 20px 0;
            font-size: 1.2em;
        }
        
        .header-nav { /* Columna 3 */
            grid-column: 3;
            display: flex;
            justify-content: flex-end; /* Pega los items al final (derecha) */
            align-items: center; 
            gap: 20px; /* Espacio entre los elementos */
        }
        .header-nav a { font-weight: 500; }
        .header-nav .admin-link {
            font-size: 0.9em;
            font-weight: bold;
            text-decoration: underline;
        }
        .header-nav .cart-toggle { /* Botón Hamburguesa */
            background: none;
            border: none;
            color: white;
            font-size: 1.8em;
            cursor: pointer;
            padding: 0 5px;
        }
        .user-menu {
            position: relative;
            display: inline-block;
        }
        .user-menu-btn {
            background: none;
            border: none;
            color: white;
            font-weight: 500;
            cursor: pointer;
            font-size: 1em;
        }
        .user-menu-content {
            display: none; /* Oculto por defecto */
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-radius: 5px;
            right: 0;
            z-index: 101;
        }
        .user-menu-content a, .user-menu-content form {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 0.9em;
        }
        .user-menu-content a:hover { background-color: #f1f1f1; }
        .user-menu-content form button {
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-family: inherit;
            font-size: 1em;
        }

        /* --- Mini Carrito (Offcanvas) --- */
        #miniCartBackdrop {
            display: none; /* Oculto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        #miniCartPartial {
            display: block;
            position: fixed;
            top: 0;
            right: -400px; /* Oculto fuera de la pantalla */
            width: 380px;
            height: 100%;
            background: white;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            z-index: 1001;
            transition: right 0.3s ease;
        }
        
        /* --- Footer --- */
        footer {
            background: #343A40; /* Gris oscuro */
            color: #f8f9fa;
            padding: 40px 20px;
            text-align: center;
        }
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
            <a href="{{ route('cart.index') }}" title="Ver Carrito">
                🛒 Carrito ({{ $cartCount }})
            </a>
            
            @guest
                <a href="{{ route('login') }}">👤 Login</a>
                <a href="{{ route('register') }}">Registro</a>
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
            <div style="padding: 20px; text-align: center;">Tu carrito está vacío.</div>
        @endif
    </div>
    
    <script>
        // Lógica para el menú de usuario
        function toggleUserMenu() {
            var dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }
        // Cierra el menú si se hace clic fuera
        window.onclick = function(event) {
            if (!event.target.matches('.user-menu-btn')) {
                var dropdowns = document.getElementsByClassName("user-menu-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].style.display = 'none';
                }
            }
        }
        
        // Lógica para el mini-carrito
        function toggleMiniCart() {
            var cart = document.getElementById('miniCartPartial');
            var backdrop = document.getElementById('miniCartBackdrop');
            var cartCount = {{ $cartCount }};
            
            if (cart.style.right === '0px') {
                cart.style.right = '-400px';
                backdrop.style.display = 'none';
            } else if (cartCount > 0) { // Solo se abre si el carrito no está vacío
                cart.style.right = '0px';
                backdrop.style.display = 'block';
            }
        }
    </script>
</body>
</html>