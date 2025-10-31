<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viña Montenegro</title>
    <style>
        body { 
            font-family: sans-serif; 
            background: #f8f9fa; /* Tono gris claro */
            margin: 0; 
        }
        header { 
            background: #b42a6a; color: white; padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; /* <-- Clave para el layout */
            align-items: center;
            height: 70px; box-sizing: border-box;
            position: sticky; top: 0; z-index: 10;
        }
        
        /* 1. LADO IZQUIERDO: Logo */
        header .header-left h1 { margin: 0; font-size: 1.5em; }
        header a { color: white; text-decoration: none; font-weight: bold; }
        
        /* 2. ¡NUEVO! CENTRO: Barra de Búsqueda */
        .header-search {
            flex-grow: 1; /* Ocupa el espacio del medio */
            max-width: 500px; /* Ancho máximo */
            margin: 0 20px;
        }
        .header-search form {
            display: flex;
            background: #fff;
            border-radius: 20px; /* Bordes redondeados */
            overflow: hidden;
        }
        .header-search input {
            border: none;
            padding: 10px 15px;
            width: 100%;
            outline: none;
            color: #333;
        }
        .header-search button {
            background: #f0f0f0; /* Gris claro */
            border: none;
            padding: 0 15px;
            cursor: pointer;
            font-size: 1.2em;
        }

        /* 3. LADO DERECHO: Navegación */
        header nav { 
            display: flex; 
            align-items: center; 
            gap: 20px; /* Espacio entre los íconos/enlaces */
        }
        header nav .auth-links a { font-size: 0.9em; }
        header nav .auth-links {
        display: flex;
        align-items: center;
        gap: 15px; 
        }
        header nav .auth-links form { display: inline; }
        header nav .admin-link {
        font-weight: bold; 
        font-size: 0.9em;  
        }
        #toggleMiniCart { /* Botón de Hamburguesa */
            background: none; border: none; color: white; font-size: 1.8em;
            cursor: pointer; padding: 0 10px;
        }

        /* ... (resto de tus estilos de .main-container, .sidebar-wrapper, etc.) ... */
        .main-container { display: flex; align-items: flex-start; }
        .content-wrapper { flex-grow: 1; padding: 20px; }
        .sidebar-wrapper {
            position: fixed; top: 0; right: 0; height: 100vh; 
            z-index: 101; display: none;
        }
        #cartBackdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 100; display: none;
        }
        footer {
            background: #343A40; color: #f8f9fa;
            padding: 40px 20px; text-align: center; margin-top: 40px;
        }
    </style>
</head>
<body>

    <header>
        
        <div class="header-left">
            <h1><a href="/">Viña Montenegro</a></h1>
        </div>
        
        <div class="header-search">
            <form action="#" method="GET">
                <input type="text" name="search" placeholder="Buscar en Viña Montenegro...">
                <button type="submit">🔍</button>
            </form>
        </div>

        <nav>
            @php $cartCount = count(session()->get('cart', [])); @endphp
            
            <a href="{{ route('cart.index') }}" title="Ver Carrito">
                🛒 Carrito ({{ $cartCount }})
            </a>
            
            <div class="auth-links">
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
                
                    <span>{{ auth()->user()->nombre_completo }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            (Log Out)
                        </a>
                    </form>
                @endauth
            </div>

            <button id="toggleMiniCart" title="Mostrar/Ocultar Carrito">&#9776;</button>
        </nav>
        
    </header>

    <div class="main-container">
        <main class="content-wrapper">
            @yield('content')
        </main>
    </div>
    
    <footer>
        <p>Viña Montenegro © 2025</p>
        <p>Contactos, información, etc.</p>
    </footer>

    <div id="cartBackdrop"></div>
    @if(count(session()->get('cart', [])) > 0)
        <div class="sidebar-wrapper" id="miniCartSidebar">
            @include('partials._mini-cart')
        </div>
    @endif
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleMiniCart');
            const miniCart = document.getElementById('miniCartSidebar');
            const backdrop = document.getElementById('cartBackdrop');
            const closeButton = document.getElementById('closeMiniCart'); 

            function showCart() {
                if (miniCart) {
                    backdrop.style.display = 'block';
                    miniCart.style.display = 'block';
                }
            }

            function hideCart() {
                if (miniCart) {
                    backdrop.style.display = 'none';
                    miniCart.style.display = 'none';
                }
            }

            if (miniCart) {
                toggleButton.addEventListener('click', showCart);
                closeButton.addEventListener('click', hideCart);
                backdrop.addEventListener('click', hideCart);
            } else {
                toggleButton.style.display = 'none';
            }
        });
    </script>
</body>
</html>