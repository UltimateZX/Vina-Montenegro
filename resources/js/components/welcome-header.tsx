import { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { ShoppingCart, User, Cog, LogOut, UserCircle, Sun, Moon } from 'lucide-react';
import useCartStore from '@/hooks/useCartStore';
import { PageProps } from '@/types';

export function WelcomeHeader({
    onCartClick,
    onLoginClick,
    onRegisterClick,
}: {
    onCartClick: () => void;
    onLoginClick: () => void;
    onRegisterClick: () => void;
}) {
    const { auth } = usePage<PageProps>().props;
    const user = auth?.user;

    const cartItems = useCartStore((state) => state.items);
    const cartCount = cartItems.reduce((total, item) => total + item.quantity, 0);
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    // --- LÓGICA DEL MODO OSCURO (REINTEGRADA) ---
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
    // --- FIN DE LA LÓGICA DEL MODO OSCURO ---

    return (
        <header className="sticky top-0 z-50 flex h-[80px] w-full items-center justify-between gap-4 bg-white/80 px-6 text-gray-800 shadow-md backdrop-blur-sm dark:bg-gray-900/80 dark:text-white">
            <div className="flex-shrink-0">
                <Link href={route('home')} className="text-2xl font-bold text-primary dark:text-white">
                    Viña Montenegro
                </Link>
            </div>

            <nav className="hidden flex-1 items-center justify-center gap-6 md:flex">
                <Link href="#" className="font-medium hover:text-primary">Inicio</Link>
                <Link href="#" className="font-medium hover:text-primary">Nuestros Vinos</Link>
                <Link href="#" className="font-medium hover:text-primary">Experiencias</Link>
                <Link href="#" className="font-medium hover:text-primary">Blog</Link>
                <Link href="#" className="font-medium hover:text-primary">Sobre Nosotros</Link>
            </nav>

            <div className="flex flex-shrink-0 items-center gap-4">
                <button
                    onClick={onCartClick}
                    className="relative rounded-full p-2 transition-colors hover:bg-gray-200 dark:hover:bg-gray-700"
                    title="Ver Carrito"
                >
                    <ShoppingCart size={22} />
                    {cartCount > 0 && (
                        <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">{cartCount}</span>
                    )}
                </button>

                <button
                    onClick={toggleDarkMode}
                    className="rounded-full p-2 transition-colors hover:bg-gray-200 dark:hover:bg-gray-700"
                    title={isDarkMode ? 'Activar modo claro' : 'Activar modo oscuro'}
                >
                    {isDarkMode ? <Sun size={22} /> : <Moon size={22} />}
                </button>

                {user ? (
                    <div className="relative">
                        <button
                            onClick={() => setIsMenuOpen(!isMenuOpen)}
                            className="rounded-full p-2 transition-colors hover:bg-gray-200 dark:hover:bg-gray-700"
                        >
                            <User size={22} />
                        </button>
                        {isMenuOpen && (
                            <div className="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white text-black shadow-lg ring-1 ring-black ring-opacity-5">
                                <div className="py-1">
                                    {user.rol === 'admin' && (
                                        <Link
                                            href={route('admin.dashboard')}
                                            className="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            <Cog className="mr-2" size={16} />
                                            Panel Admin
                                        </Link>
                                    )}
                                    <Link
                                        href={route('profile.edit')}
                                        className="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        <UserCircle className="mr-2" size={16} />
                                        Mi Perfil
                                    </Link>
                                    <Link
                                        href={route('logout')}
                                        method="post"
                                        as="button"
                                        className="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        <LogOut className="mr-2" size={16} />
                                        Cerrar Sesión
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                ) : (
                    <div className="hidden items-center gap-2 md:flex">
                        <button
                            onClick={onLoginClick}
                            className="rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-gray-200 dark:hover:bg-gray-700"
                        >
                            Iniciar Sesión
                        </button>
                        <button
                            onClick={onRegisterClick}
                            className="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary/90"
                        >
                            Registrarse
                        </button>
                    </div>
                )}
            </div>
        </header>
    );
}
