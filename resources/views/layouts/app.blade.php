<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viña Montenegro</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; margin: 0; }
        header { 
            background: #b42a6a; color: white; padding: 15px 30px; 
            display: flex; justify-content: space-between; align-items: center;
            height: 70px; box-sizing: border-box;
            position: sticky; top: 0; z-index: 10;
        }
        header .header-left { display: flex; align-items: center; gap: 20px; }
        header h1 { margin: 0; font-size: 1.5em; }
        header a { color: white; text-decoration: none; font-weight: bold; }
        header nav { display: flex; align-items: center; gap: 15px; }
        header nav .auth-links a { font-size: 0.9em; }
        header nav .auth-links form { display: inline; }
        header nav .admin-link { /* Estilo para el nuevo link de Admin */
            background: #fff;
            color: #b42a6a;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        #toggleMiniCart { 
            background: none; border: none; color: white; font-size: 1.8em;
            cursor: pointer; padding: 0 10px;
        }
        .main-container { display: flex; }
        .content-wrapper { flex-grow: 1; padding: 20px; }
        .sidebar-wrapper {
            position: fixed; top: 0; right: 0; height: 100vh; 
            z-index: 101; display: none;
        }
        #cartBackdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 100; display: none;
        }
    </style>
</head>
<body>

    <header>
        <div class="header-left">
            <h1><a href="/">Viña Montenegro</a></h1>
            <button id="toggleMiniCart" title="Mostrar/Ocultar Carrito">&#9776;</button>
        </div>
        
        <nav>
            @php $cartCount = count(session()->get('cart', [])); @endphp
            <a href="{{ route('cart.index') }}">
                Carrito ({{ $cartCount }})
            </a>
            
            <div class="auth-links">
                @guest
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Registro</a>
                @endguest
                
                @auth
                    @if(auth()->user()->rol == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="admin-link">
                            Panel Admin
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
        </nav>
    </header>

    <div class="main-container">
        <main class="content-wrapper">
            @yield('content')
        </main>
    </div>
    
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
                backdrop.style.display = 'block';
                miniCart.style.display = 'block';
            }

            function hideCart() {
                backdrop.style.display = 'none';
                miniCart.style.display = 'none';
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