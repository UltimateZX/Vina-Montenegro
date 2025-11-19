import React from 'react';
import useCartStore from '@/hooks/useCartStore';

interface Producto {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    url_imagen: string;
}

interface Props {
    producto: Producto;
}

const ProductCard: React.FC<Props> = ({ producto }) => {
    const addToCart = useCartStore((state) => state.addToCart);

    const handleAddToCart = () => {
        addToCart(producto);
        alert('Producto añadido al carrito');
    };

    return (
        <div className="flex flex-col border rounded-lg p-4 shadow-lg bg-white dark:bg-[#222] transition-all duration-300">
            <img src={`${producto.url_imagen}`} alt={producto.nombre} className="w-full h-48 object-cover rounded-t-lg" />
            <div className="p-4">
                <h3 className="text-lg font-bold">{producto.nombre}</h3>
                <p className="text-sm text-[#555] dark:text-[#DDD]">{producto.descripcion}</p>
                <p className="text-xl font-bold text-pink-600 mt-4">${new Intl.NumberFormat('es-CL').format(producto.precio)}</p>
                <button
                    onClick={handleAddToCart}
                    className="mt-4 w-full bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300"
                >
                    Añadir al Carrito
                </button>
            </div>
        </div>
    );
};

export default ProductCard;
