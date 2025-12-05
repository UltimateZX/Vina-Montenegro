import { type Product } from '@/types';

interface ProductCardProps {
    product: Product;
    onClick: () => void; // <-- AÑADIR PROP ONCLICK
}

export function ProductCard({ product, onClick }: ProductCardProps) {
    const formattedPrice = new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN',
    }).format(parseFloat(product.price));

    return (
        // Reemplaza Link por un div con onClick y cursor-pointer
        <div
            onClick={onClick}
            className="group relative block cursor-pointer overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-shadow duration-300 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800"
        >
            <img
                src={product.image_url || 'https://via.placeholder.com/400x300'}
                alt={product.name}
                className="h-64 w-full object-cover transition duration-500 group-hover:scale-105"
            />

            <div className="relative p-4">
                <h3 className="text-lg font-bold text-gray-900 dark:text-white">{product.name}</h3>
                <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {product.category?.name || 'Uncategorized'}
                </p>
                <p className="mt-2 text-lg font-semibold text-gray-800 dark:text-gray-200">
                    {formattedPrice}
                </p>
            </div>
        </div>
    );
}
