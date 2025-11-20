<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Admin - Viña Montenegro</title>
    
    <style>
        body { font-family: system-ui, sans-serif; margin: 0; background: #f4f4f4; }
        
        .admin-layout { display: flex; min-height: 100vh; position: relative; }
        
        /* --- SIDEBAR --- */
        .admin-sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .admin-sidebar-header { padding: 20px; background: #212529; }
        .admin-sidebar h2 { margin: 0; font-size: 1.2em; }
        
        .admin-sidebar ul { list-style: none; padding: 0; margin: 0; flex-grow: 1; }
        .admin-sidebar ul li a {
            display: block; padding: 15px 20px; color: #adb5bd;
            text-decoration: none; border-bottom: 1px solid #3d4349;
            transition: all 0.2s;
        }
        .admin-sidebar ul li a:hover, .admin-sidebar ul li a.active {
            background: #495057; color: white; padding-left: 25px;
        }
        
        .admin-sidebar .store-link { margin-top: auto; border-top: 1px solid #495057; }
        .admin-sidebar .store-link a { background: #212529; color: #ffc107; font-weight: bold; text-align: center; }

        /* --- CONTENIDO PRINCIPAL --- */
        .admin-content {
            flex-grow: 1;
            padding: 30px;
            background: #f8f9fa;
            width: 100%; /* Asegura que ocupe el espacio restante */
            overflow-x: hidden; /* Evita scroll horizontal indeseado */
        }

        /* --- BOTÓN MENÚ MÓVIL --- */
        .mobile-menu-btn {
            display: none; /* Oculto en PC */
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: #343a40;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        /* --- OVERLAY (Fondo oscuro al abrir menú) --- */
        #sidebarOverlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        /* --- 📱 RESPONSIVIDAD --- */
        @media (max-width: 900px) {
            .admin-sidebar {
                position: fixed;
                left: -250px; /* Oculto a la izquierda */
            }
            .admin-sidebar.active {
                transform: translateX(250px); /* Se muestra al activar */
            }
            
            .admin-content {
                padding: 20px;
                padding-top: 70px; /* Espacio para el botón de menú */
            }
            
            .mobile-menu-btn { display: block; } /* Mostrar botón */
        }
    </style>
</head>
<body>

    <!-- Botón Menú Móvil -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">☰ Menú</button>

    <!-- Fondo oscuro para cerrar menú -->
    <div id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="admin-layout">
        
        <!-- SIDEBAR -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <h2>Panel Admin</h2>
                <small>Viña Montenegro</small>
            </div>
            
            <ul>
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a></li>
                
                <li><a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">🍷 Productos</a></li>
                
                <li><a href="{{ route('admin.pedidos.index') }}" class="{{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">📦 Pedidos</a></li>
                
                <li><a href="{{ route('admin.pagos.index') }}" class="{{ request()->routeIs('admin.pagos.*') ? 'active' : '' }}">💰 Validar Pagos</a></li>

                <li><a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">👥 Usuarios</a></li>
                
                <li class="store-link">
                    <a href="{{ route('home') }}">
                        ← Volver a la Tienda
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- CONTENIDO -->
        <main class="admin-content">
            
            <!-- Mensajes de alerta globales -->
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Toggle clase 'active' para mostrar/ocultar con animación
            if (sidebar.style.transform === 'translateX(250px)') {
                sidebar.style.transform = 'translateX(0)'; // Ocultar (volver a -250px relativo)
                overlay.style.display = 'none';
            } else {
                sidebar.style.transform = 'translateX(250px)'; // Mostrar
                overlay.style.display = 'block';
            }
        }
        
        // Corrección para que el menú funcione correctamente al redimensionar la ventana
        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) {
                document.getElementById('adminSidebar').style.transform = 'none';
                document.getElementById('sidebarOverlay').style.display = 'none';
            } else {
                 // Reset para móvil
                document.getElementById('adminSidebar').style.transform = 'translateX(0)';
            }
        });
    </script>
</body>
</html>