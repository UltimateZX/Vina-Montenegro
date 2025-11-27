import { Link } from '@inertiajs/react';

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

export default function ProductCard({ producto }: Props) {
    return (
        // Hacemos que toda la tarjeta sea un enlace
        <Link href="#" className="group text-center">
            <div className="bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md transition-transform duration-300 group-hover:scale-105 group-hover:shadow-xl">
                <img
                    src={producto.url_imagen}
                    alt={producto.nombre}
                    className="w-full h-72 object-contain p-4" // object-contain para que se vea completa
                />
            </div>
            <h3 className="mt-4 text-lg font-semibold text-gray-800 dark:text-gray-200">
                {producto.nombre}
            </h3>
        </Link>
    );
};
