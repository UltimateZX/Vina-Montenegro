import { useState, useEffect } from 'react';
import { type Product } from '@/types';
import useCartStore from '@/hooks/useCartStore';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Minus, Plus } from 'lucide-react';

interface ProductDetailModalProps {
    product: Product | null;
    onClose: () => void;
}

export function ProductDetailModal({ product, onClose }: ProductDetailModalProps) {
    const [quantity, setQuantity] = useState(1);
    const addToCart = useCartStore((state) => state.addToCart);

    // Resetea la cantidad a 1 cada vez que se abre el modal con un nuevo producto
    useEffect(() => {
        if (product) {
            setQuantity(1);
        }
    }, [product]);

    if (!product) {
        return null;
    }

    const handleAddToCart = () => {
        addToCart(product, quantity);
        onClose(); // Cierra el modal después de añadir al carrito
    };

    const formattedPrice = new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN',
    }).format(parseFloat(product.price));

    return (
        <Dialog open={!!product} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-3xl">
                <div className="items-center grid grid-cols-1 gap-8 md:grid-cols-2">
                    {/* Columna de la Imagen */}
                    <div className="justify-center flex">
                        <img
                            src={product.image_url || 'https://via.placeholder.com/400x300'}
                            alt={product.name}
                            className="scale-75 rounded-lg object-cover"
                        />
                    </div>

                    {/* Columna de Detalles */}
                    <div className="flex flex-col">
                        <DialogHeader>
                            <DialogTitle className="text-3xl font-bold">{product.name}</DialogTitle>
                            <DialogDescription className="pt-2 text-base">
                                {product.description || 'No description available.'}
                            </DialogDescription>
                        </DialogHeader>

                        <div className="my-6">
                            <p className="text-3xl font-bold text-gray-900 dark:text-white">
                                {formattedPrice}
                            </p>
                            <p className="mt-1 text-sm text-gray-500">
                                Stock: {product.stock > 0 ? `${product.stock} available` : 'Out of stock'}
                            </p>
                        </div>

                        {/* Selector de Cantidad y Botón */}
                        <div className="mt-auto flex items-center gap-4">
                            <div className="flex items-center rounded-md border">
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                                >
                                    <Minus className="h-4 w-4" />
                                </Button>
                                <Input
                                    type="number"
                                    className="w-16 border-none text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                                    value={quantity}
                                    onChange={(e) => setQuantity(Number(e.target.value))}
                                    min={1}
                                />
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    onClick={() => setQuantity(quantity + 1)}
                                >
                                    <Plus className="h-4 w-4" />
                                </Button>
                            </div>
                            <Button
                                onClick={handleAddToCart}
                                className="flex-grow"
                                disabled={product.stock === 0}
                            >
                                {product.stock > 0 ? 'Add to Cart' : 'Out of Stock'}
                            </Button>
                        </div>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    );
}
