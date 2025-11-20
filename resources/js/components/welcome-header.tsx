import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js'; // <-- ¡Importante!
import { ShoppingCart, User } from 'lucide-react'; // <-- Importamos iconos

export function WelcomeHeader() {
    return (
        <header className="sticky top-0 z-50 flex h-[70px] w-full items-center bg-[#b42a6a] p-4 text-white shadow-md">
            {/* Logo a la izquierda */}
            <div className="flex-1">
                <Link href={route('home')} className="text-2xl font-bold">
                    Viña Montenegro
                </Link>
            </div>

            {/* Contenedor para los elementos de la derecha */}
            <nav className="flex flex-1 items-center justify-end gap-4">
                <Link href={route('cart.index')} className="flex items-center gap-2 rounded-xl border border-white p-2 transition-all duration-300 hover:bg-white hover:text-[#b42a6a]">
                    <ShoppingCart size={20} />
                    <span>Carrito (0)</span>
                </Link>
                <Link href={route('login')} className="flex items-center gap-2 rounded-xl border border-white p-2 transition-all duration-300 hover:bg-white hover:text-[#b42a6a]">
                    <User size={20} />
                    <span>Login</span>
                </Link>
                <Link href={route('register')} className="flex items-center gap-2 rounded-xl border border-white p-2 transition-all duration-300 hover:bg-white hover:text-[#b42a6a]">
                    <span>Registro</span>
                </Link>
            </nav>
        </header>
    );
}
