import { useState, useEffect } from 'react';
import { PageProps, Producto, Categoria } from '@/types';
import { WelcomeHeader } from '@/components/welcome-header';
import { WelcomeFooter } from '@/components/welcome-footer';
import { CartSidebar } from '@/components/cart-sidebar';
import ProductCard from '@/components/ProductCard';
import useCartStore from '@/hooks/useCartStore';
// --- NUEVAS IMPORTACIONES ---
import { Modal } from '@/components/ui/modal';
import { LoginForm } from './auth/login';
import { RegisterForm } from './auth/register';

export default function Welcome(props: PageProps<{ productos: Producto[], categorias: Categoria[] }>) {
    const { auth, productos, categorias } = props; // Desestructuramos las props aquí
    const [isCartOpen, setIsCartOpen] = useState(false);
    // --- NUEVO ESTADO PARA LOS MODALES ---
    const [authModal, setAuthModal] = useState<'login' | 'register' | null>(null);

    const fetchCart = useCartStore((state) => state.fetchCart);

    useEffect(() => {
        fetchCart();
    }, [fetchCart]);

    const filteredProductos = productos; // Este error desaparecerá

    return (
        <div className="bg-gray-50 min-h-screen dark:bg-gray-800">
            {/* PASAMOS LAS FUNCIONES AL HEADER */}
            <WelcomeHeader
                onCartClick={() => setIsCartOpen(true)}
                onLoginClick={() => setAuthModal('login')}
                onRegisterClick={() => setAuthModal('register')}
            />
            <CartSidebar isOpen={isCartOpen} onClose={() => setIsCartOpen(false)} />

            {/* --- RENDERIZADO DE MODALES --- */}
            <Modal
                isOpen={authModal === 'login'}
                onClose={() => setAuthModal(null)}
                title="Log in to your account"
                description="Enter your email and password below to log in"
            >
                <LoginForm
                    canResetPassword={true} // Asumimos que sí se puede
                    onSwitchToRegister={() => setAuthModal('register')}
                    onSuccess={() => setAuthModal(null)}
                />
            </Modal>

            <Modal
                isOpen={authModal === 'register'}
                onClose={() => setAuthModal(null)}
                title="Create an account"
                description="Enter your information to create an account"
            >
                <RegisterForm
                    onSwitchToLogin={() => setAuthModal('login')}
                    onSuccess={() => setAuthModal(null)}
                />
            </Modal>

            <main>
                {/* --- INICIO: SECCIÓN HERO --- */}
                {/*
                    INSTRUCCIÓN PARA LA IMAGEN DE FONDO:
                    1. Coloca tu imagen de viñedo en la carpeta `public/images/`. Por ejemplo: `public/images/vinedo-bg.jpg`
                    2. Reemplaza `URL_DE_TU_IMAGEN` con la ruta correcta: `/images/vinedo-bg.jpg`
                */}
                <section
                    className="relative flex items-center justify-center h-[60vh] bg-cover bg-center text-white"
                    style={{ backgroundImage: "url('URL_DE_TU_IMAGEN')" }}
                >
                    <div className="absolute inset-0 bg-black/40"></div> {/* Overlay oscuro para legibilidad */}
                    <div className="relative z-10 text-center">
                        <h1 className="text-5xl md:text-7xl font-serif font-bold">Viña Antonio Montenegro</h1>
                        <p className="mt-4 text-xl md:text-2xl">Donde la Tradición Florece en Cada Gota</p>
                        <button className="mt-8 bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-primary-dark transition-colors">
                            Comprar
                        </button>
                    </div>
                </section>
                {/* --- FIN: SECCIÓN HERO --- */}

                {/* --- INICIO: SEPARADOR DECORATIVO --- */}
                {/*
                    INSTRUCCIÓN PARA EL SEPARADOR:
                    1. Coloca tu imagen de separador en `public/images/`. Por ejemplo: `public/images/separador.png`
                    2. Reemplaza `URL_DE_TU_SEPARADOR` con la ruta correcta: `/images/separador.png`
                */}
                <div className="py-8 bg-[#F8F6F1] dark:bg-gray-800 flex justify-center">
                    <img src="URL_DE_TU_SEPARADOR" alt="Decoración" className="h-16" />
                </div>
                {/* --- FIN: SEPARADOR DECORATIVO --- */}


                {/* --- INICIO: SECCIÓN DE PRODUCTOS --- */}
                <section className="bg-[#F8F6F1] dark:bg-gray-800 py-16">
                    <div className="container mx-auto px-4">
                        <h2 className="text-4xl font-bold text-center text-gray-800 dark:text-white mb-12">
                            Nuestros Vinos Destacados
                        </h2>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                            {productos.map((producto) => (
                                <ProductCard key={producto.id} producto={producto} />
                            ))}
                        </div>
                    </div>
                </section>
                {/* --- FIN: SECCIÓN DE PRODUCTOS --- */}
            </main>
            <WelcomeFooter />
        </div>
    );
}
