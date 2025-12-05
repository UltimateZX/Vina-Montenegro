import { type SharedData, type Product } from '@/types'; // <-- 1. IMPORTAR Product
import { Head, usePage } from '@inertiajs/react';
import { useState } from 'react';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { LoginForm } from '@/components/login-form';
import { RegisterForm } from '@/components/register-form';
import { WelcomeFooter } from '@/components/store/welcome-footer';
import { WelcomeHeader } from '@/components/store/welcome-header'; // <-- 2. IMPORTAR WelcomeHeader
import { Carousel } from '@/components/store/carousel'; // <-- 3. IMPORTAR Carousel
import { ProductCard } from '@/components/store/ProductCard';
import { CartSidebar } from '@/components/store/cart-sidebar';
import { ProductDetailModal } from '@/components/store/ProductDetailModal'; // <-- 1. IMPORTAR EL MODAL

interface WelcomeProps {
    canRegister?: boolean;
    products: Product[];
}

export default function Welcome({ canRegister = true, products }: WelcomeProps) {
    const { auth } = usePage<SharedData>().props;
    const [loginModalOpen, setLoginModalOpen] = useState(false);
    const [registerModalOpen, setRegisterModalOpen] = useState(false);
    const [cartSidebarOpen, setCartSidebarOpen] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState<Product | null>(null); // <-- 2. ESTADO PARA EL PRODUCTO SELECCIONADO

    // Define aquí las rutas a tus imágenes del carrusel
    // Asegúrate de que los nombres de archivo coincidan con los que tienes en `public/img`
    const carouselImages = [
        '/img/business/uvas.jpg',
        '/img/business/uvas2.jpg',
        '/img/business/vinos.jpg',
    ];

    return (
        <>
            <Head title="Welcome" />
            <div className="flex min-h-screen flex-col bg-gray-100 dark:bg-gray-900">
                <WelcomeHeader
                    user={auth.user}
                    onLoginClick={() => setLoginModalOpen(true)}
                    onRegisterClick={() => setRegisterModalOpen(true)}
                    onCartClick={() => setCartSidebarOpen(true)}
                />

                <Dialog open={loginModalOpen} onOpenChange={setLoginModalOpen}>
                    <DialogContent className="sm:max-w-md">
                        <DialogHeader>
                            <DialogTitle>Log in to your account</DialogTitle>
                            <DialogDescription>
                                Enter your email and password below to log in
                            </DialogDescription>
                        </DialogHeader>
                        <LoginForm
                            canResetPassword={true}
                            canRegister={canRegister}
                            onSuccess={() => setLoginModalOpen(false)}
                        />
                    </DialogContent>
                </Dialog>

                <Dialog open={registerModalOpen} onOpenChange={setRegisterModalOpen}>
                    <DialogContent className="sm:max-w-md">
                        <DialogHeader>
                            <DialogTitle>Create an account</DialogTitle>
                            <DialogDescription>
                                Enter your details below to create your account
                            </DialogDescription>
                        </DialogHeader>
                        <RegisterForm onSuccess={() => setRegisterModalOpen(false)} />
                    </DialogContent>
                </Dialog>

                {/* 4. RENDERIZAR EL SIDEBAR DEL CARRITO */}
                <CartSidebar isOpen={cartSidebarOpen} onClose={() => setCartSidebarOpen(false)} />

                {/* 3. RENDERIZAR EL MODAL DE DETALLE */}
                <ProductDetailModal
                    product={selectedProduct}
                    onClose={() => setSelectedProduct(null)}
                />

                {/* 5. REEMPLAZAR EL CONTENIDO DE MAIN */}
                <main className="flex-grow">
                    {/* El carrusel ahora usará tus imágenes estáticas */}
                    <Carousel images={carouselImages} />

                    <section className="py-12">
                        <div className="container mx-auto px-4">
                            <h2 className="mb-8 text-center text-3xl font-bold text-gray-800 dark:text-white">
                                Featured Products
                            </h2>
                            {products.length > 0 ? (
                                <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                                    {products.map((product) => (
                                        // 4. PASAR LA FUNCIÓN ONCLICK
                                        <ProductCard
                                            key={product.id}
                                            product={product}
                                            onClick={() => setSelectedProduct(product)}
                                        />
                                    ))}
                                </div>
                            ) : (
                                <p className="text-center text-gray-500 dark:text-gray-400">
                                    No products available at the moment.
                                </p>
                            )}
                        </div>
                    </section>
                </main>

                <WelcomeFooter />
            </div>
        </>
    );
}
