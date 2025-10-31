<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Admin - Viña Montenegro</title>
    <style>
        body { font-family: sans-serif; margin: 0; background: #f4f4f4; }
        .admin-layout { display: flex; }
        .admin-sidebar {
            width: 220px;
            background: #333;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        .admin-sidebar h2 { margin: 0 0 20px 0; }
        .admin-sidebar ul { list-style: none; padding: 0; }
        .admin-sidebar ul li a {
            display: block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .admin-sidebar ul li a:hover { background: #555; }
        
        /* Estilo para el separador y el enlace de "Ver Tienda" */
        .admin-sidebar .store-link {
            margin-top: 20px;
            border-top: 1px solid #555;
            padding-top: 10px;
        }
        
        .admin-content { flex-grow: 1; padding: 30px; }
        .admin-header {
            background: white;
            border-bottom: 1px solid #ddd;
            padding: 20px 30px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>Admin</h2>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                
                <li><a href="{{ route('admin.productos.index') }}">Productos</a></li>
                
                <li><a href="{{ route('admin.pagos.index') }}">Validar Pagos</a></li>

                <li><a href="{{ route('admin.pedidos.index') }}">Historial de Pedidos</a></li>

                <li><a href="{{ route('admin.usuarios.index') }}">Gestionar Usuarios</a></li>
                
                <li class="store-link">
                    <a href="{{ route('home') }}">
                        &#8592; Ver Tienda
                    </a>
                </li>
            </ul>
        </aside>
        
        <main class="admin-content">
            @yield('content')
        </main>
    </div>
</body>
</html>