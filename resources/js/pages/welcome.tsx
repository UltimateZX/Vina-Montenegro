import { useState } from 'react';
import { PageProps, Producto, Categoria } from '@/types';
import { WelcomeHeader } from '@/components/welcome-header';
import { WelcomeFooter } from '@/components/welcome-footer';
import useCartStore from '@/hooks/useCartStore';

export default function Welcome({ productos, categorias }: PageProps<{ productos: Producto[], categorias: Categoria[] }>) {
    const addToCart = useCartStore(state => state.addToCart);
    const [selectedCategory, setSelectedCategory] = useState<number | null>(null);

    const filteredProductos = selectedCategory
        ? productos.filter((p) => p.categoria_id === selectedCategory)
        : productos;

    return (
        <div className="bg-gray-50 min-h-screen">
            <WelcomeHeader />
            <main className="container mx-auto py-8 px-4">
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <aside className="md:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-800">Viña Montenegro</h2>
                            <p className="text-gray-600 mt-2">
                                Somos una viña dedicada a la producción de los mejores vinos y piscos de la región de Cañete. Descubre nuestra variedad de productos y disfruta de la auténtica experiencia vinícola peruana.
                            </p>
                        </div>

                        <div className="mb-8">
                            <h3 className="text-xl font-bold text-gray-800 mb-4">Categorías</h3>
                            <ul>
                                <li className="mb-2">
                                    <a href="#" onClick={(e) => { e.preventDefault(); setSelectedCategory(null); }} className={`block py-2 px-4 rounded transition-colors duration-200 ${selectedCategory === null ? 'bg-primary text-white font-bold' : 'text-gray-700 hover:bg-gray-100'}`}>
                                        Ver Todas
                                    </a>
                                </li>
                                {categorias?.map((categoria: Categoria) => (
                                    <li key={categoria.id} className="mb-2">
                                        <a href="#" onClick={(e) => { e.preventDefault(); setSelectedCategory(categoria.id); }} className={`block py-2 px-4 rounded transition-colors duration-200 ${selectedCategory === categoria.id ? 'bg-primary text-white font-bold' : 'text-gray-700 hover:bg-gray-100'}`}>
                                            {categoria.nombre}
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        <div>
                            <h3 className="text-xl font-bold text-gray-800 mb-4">Contacto</h3>
                            <ul>
                                <li className="flex items-center gap-2 text-gray-700 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19.05 4.94C17.82 3.71 16.21 3 14.5 3C12.26 3 10.1 3.95 8.53 5.51L7.12 4.1L5.71 5.51L8.54 8.34L9.95 6.93L8.71 5.69C9.93 4.47 11.54 3.8 13.25 3.8C14.5 3.8 15.7 4.21 16.69 5.21C18.69 7.21 18.69 10.29 16.69 12.29L12 16.97L7.31 12.29C5.31 10.29 5.31 7.21 7.31 5.21C8.3 4.22 9.5 3.8 10.75 3.8C11.5 3.8 12.23 4 12.88 4.33L14.29 2.91C13.54 2.58 12.73 2.3 11.88 2.3C10.17 2.3 8.56 3 7.34 4.22C4.9 6.66 4.9 10.44 7.34 12.88L12 17.54L16.66 12.88C19.1 10.44 19.1 6.66 16.66 4.22Z"/></svg>
                                    WhatsApp
                                </li>
                                <li className="flex items-center gap-2 text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.32 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/></svg>
                                    Facebook
                                </li>
                            </ul>
                        </div>
                    </aside>

                    <div className="md:col-span-3">
                        <h1 className="text-3xl font-bold text-gray-800 mb-6">Catálogo de Productos</h1>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {filteredProductos.map((producto) => (
                                <div key={producto.id} className="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 transition-transform duration-300 hover:scale-105">
                                    <img src={producto.url_imagen} alt={producto.nombre} className="w-full h-48 object-cover" />
                                    <div className="p-4">
                                        <h3 className="text-lg font-semibold text-gray-800">{producto.nombre}</h3>
                                        <p className="text-gray-600 mt-1">{producto.descripcion}</p>
                                        <div className="flex justify-between items-center mt-4">
                                            <span className="text-xl font-bold text-primary">PEN {producto.precio}</span>
                                            <button
                                                onClick={() => addToCart(producto)}
                                                className="bg-accent text-white px-4 py-2 rounded-full hover:bg-primary transition-colors duration-300"
                                            >
                                                Añadir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </main>
            <WelcomeFooter />
        </div>
    );
}
