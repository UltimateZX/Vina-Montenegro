import { useEffect } from 'react';
import { PageProps, Producto } from '@/types';
import ProductCard from '@/components/ProductCard';
import useCartStore from '@/hooks/useCartStore';
import GuestLayout from '@/layouts/guest-layout'; // <-- 1. Importa el nuevo layout

export default function Welcome({ productos }: PageProps<{ productos: Producto[] }>) {
    const fetchCart = useCartStore((state) => state.fetchCart);

    useEffect(() => {
        fetchCart();
    }, [fetchCart]);

    return (
        <>
            {/* Ya no necesitamos el header, footer o modales aquí. Solo el contenido. */}
            <section
                className="relative flex items-center justify-center h-[60vh] bg-cover bg-center text-white"
                style={{ backgroundImage: "url('/images/vinedo-bg.jpg')" }} // Asumo una ruta de ejemplo
            >
                <div className="absolute inset-0 bg-black/40"></div>
                <div className="relative z-10 text-center">
                    <h1 className="text-5xl md:text-7xl font-serif font-bold">Viña Antonio Montenegro</h1>
                    <p className="mt-4 text-xl md:text-2xl">Donde la Tradición Florece en Cada Gota</p>
                    <button className="mt-8 bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-primary-dark transition-colors">
                        Comprar
                    </button>
                </div>
            </section>

            <div className="py-8 bg-[#F8F6F1] dark:bg-gray-800 flex justify-center">
                <img src="/images/separador.png" alt="Decoración" className="h-16" /> {/* Asumo una ruta de ejemplo */}
            </div>

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
        </>
    );
}

// --- 2. Asigna el layout persistente a la página ---
Welcome.layout = (page: React.ReactElement) => <GuestLayout>{page}</GuestLayout>;
