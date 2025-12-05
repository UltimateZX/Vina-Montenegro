import { PropsWithChildren, useState, useEffect } from 'react'; // <-- 1. IMPORTAR useState y useEffect
import { Link, usePage } from '@inertiajs/react';
import { Home, ShoppingCart, Package, Users, CreditCard, LogOut, Sun, Moon } from 'lucide-react'; // <-- 2. IMPORTAR Sun y Moon
import { type PageProps, type User } from '@/types';

function SidebarLink({ href, active, children }: PropsWithChildren<{ href: string; active: boolean }>) {
    const activeClasses = 'bg-gray-700 text-white';
    const inactiveClasses = 'text-gray-300 hover:bg-gray-700 hover:text-white';
    return (
        <Link
            href={href}
            className={`flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors ${
                active ? activeClasses : inactiveClasses
            }`}
        >
            {children}
        </Link>
    );
}

export default function AdminLayout({ children }: PropsWithChildren) {
    const { props, url } = usePage<PageProps>();
    const { auth } = props;
    const user = auth.user as User;

    // --- 3. AÑADIR LÓGICA DE MODO OSCURO ---
    const [isDarkMode, setIsDarkMode] = useState(() => {
        if (typeof window !== 'undefined') {
            return localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        }
        return false;
    });

    useEffect(() => {
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        }
    }, [isDarkMode]);

    const toggleDarkMode = () => {
        setIsDarkMode(!isDarkMode);
    };
    // --- FIN DE LA LÓGICA ---

    return (
        <div className="flex h-screen bg-gray-100 dark:bg-gray-900 transition-all duration-300">
            {/* Sidebar */}
            <aside className="flex w-64 flex-col bg-gray-800 text-white">
                <div className="flex h-16 flex-shrink-0 items-center justify-center px-4">
                    <h1 className="text-2xl font-bold text-white">Viña Montenegro</h1>
                </div>
                <nav className="flex-1 space-y-1 px-2 py-4">
                    <SidebarLink href="/dashboard" active={url === '/dashboard'}>
                        <Home className="mr-3 h-5 w-5" />
                        Dashboard
                    </SidebarLink>
                    <SidebarLink href="/admin/products" active={url.startsWith('/admin/products')}>
                        <Package className="mr-3 h-5 w-5" />
                        Productos
                    </SidebarLink>
                    <SidebarLink href="/admin/users" active={url.startsWith('/admin/users')}>
                        <Users className="mr-3 h-5 w-5" />
                        Usuarios
                    </SidebarLink>
                    <SidebarLink href="/admin/orders" active={url.startsWith('/admin/orders')}>
                        <ShoppingCart className="mr-3 h-5 w-5" />
                        Pedidos
                    </SidebarLink>
                    <SidebarLink href="/admin/payments" active={url.startsWith('/admin/payments')}>
                        <CreditCard className="mr-3 h-5 w-5" />
                        Pagos
                    </SidebarLink>
                </nav>
                <div className="p-4">
                     <Link
                        href="/logout"
                        method="post"
                        as="button"
                        className="flex w-full items-center rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white"
                    >
                        <LogOut className="mr-3 h-5 w-5" />
                        Cerrar Sesión
                    </Link>
                </div>
            </aside>

            {/* Main Content */}
            <div className="flex flex-1 flex-col">
                <header className="flex h-16 flex-shrink-0 items-center justify-between bg-white px-6 shadow dark:bg-gray-800">
                    <div /> {/* Div vacío para empujar el contenido a la derecha */}
                    <div className="flex items-center gap-4">
                        <button
                            onClick={toggleDarkMode}
                            className="rounded-full p-2 transition-colors hover:bg-gray-100 dark:hover:bg-gray-700"
                            title={isDarkMode ? 'Activar modo claro' : 'Activar modo oscuro'}
                        >
                            {isDarkMode ? <Sun size={22} className="text-white" /> : <Moon size={22} />}
                        </button>
                        <h2 className="text-xl font-semibold text-gray-800 dark:text-white">
                            Bienvenido, {user.name}
                        </h2>
                    </div>
                </header>
                <main className="flex-1 overflow-y-auto p-6">
                    {children}
                </main>
            </div>
        </div>
    );
}
