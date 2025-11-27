import useCartStore from '@/hooks/useCartStore';
import { X, Trash2, Minus, Plus } from 'lucide-react';

interface CartSidebarProps {
    isOpen: boolean;
    onClose: () => void;
}

export function CartSidebar({ isOpen, onClose }: CartSidebarProps) {
    const { items, removeFromCart, updateQuantity, clearCart } = useCartStore();

    const subtotal = items.reduce((acc, item) => acc + item.price * item.quantity, 0);

    return (
        <>
            {/* Overlay */}
            <div
                className={`fixed inset-0 bg-black/50 transition-opacity duration-300 z-40 ${
                    isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'
                }`}
                onClick={onClose}
            />

            {/* Sidebar */}
            <div
                className={`fixed right-0 top-0 h-full w-full sm:w-96 bg-white dark:bg-[#1a1a1a] shadow-2xl transform transition-transform duration-300 ease-in-out z-50 flex flex-col ${
                    isOpen ? 'translate-x-0' : 'translate-x-full'
                }`}
            >
                {/* Header */}
                <div className="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div className="flex items-center gap-2">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                            Carrito
                        </h2>
                        <span className="bg-[#A42C6C] text-white text-xs rounded-full px-2 py-1">
                            {items.length}
                        </span>
                    </div>
                    <button
                        onClick={onClose}
                        className="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors"
                        aria-label="Cerrar carrito"
                    >
                        <X className="w-6 h-6" />
                    </button>
                </div>

                {/* Lista de productos */}
                <div className="flex-1 overflow-y-auto p-6">
                    {items.length === 0 ? (
                        <div className="flex flex-col items-center justify-center h-full text-gray-500 dark:text-gray-100">
                            <p className="text-lg">Tu carrito está vacío</p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {items.map(item => (
                                <div
                                    key={item.id}
                                    className="flex gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg"
                                >
                                    <img
                                        src={item.image || '/img/default-product.jpg'}
                                        alt={item.name}
                                        className="w-20 h-20 object-cover rounded-md"
                                    />
                                    <div className="flex-1">
                                        <h3 className="font-semibold text-sm dark:text-gray-100">{item.name}</h3>
                                        <p className="text-xs text-gray-600 dark:text-gray-400">
                                            S/{new Intl.NumberFormat('es-PE', {minimumFractionDigits: 2}).format(item.price)}
                                        </p>
                                        <div className="flex items-center gap-2 mt-2">
                                            <button onClick={() => updateQuantity(item.id, item.quantity - 1)} className="p-1 bg-gray-200 dark:bg-gray-400 rounded-full">
                                                <Minus className="w-4 h-4" />
                                            </button>
                                            <span className="text-gray-800 dark:text-gray-100">{item.quantity}</span>
                                            <button onClick={() => updateQuantity(item.id, item.quantity + 1)} className="p-1 bg-gray-200 dark:bg-gray-400 rounded-full">
                                                <Plus className="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                    <div className="flex flex-col items-end justify-between">
                                        <p className="font-bold text-sm dark:text-gray-100">
                                            TOTAL: S/{new Intl.NumberFormat('es-PE', {minimumFractionDigits: 2}).format(item.price * item.quantity)}
                                        </p>
                                        <button onClick={() => removeFromCart(item.id)} className="text-red-500 hover:text-red-700">
                                            <Trash2 className="w-5 h-5" />
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                {/* Footer */}
                {items.length > 0 && (
                    <div className="p-6 border-t border-gray-200 dark:border-gray-700">
                        <div className="flex justify-between items-center mb-4 bg-gray-200 dark:bg-gray-800 p-4 rounded-lg">
                            <span className="text-lg font-semibold text-[#A42C6C]">TOTAL:</span>
                            <span className="text-xl font-bold text-[#A42C6C]">
                                S/{new Intl.NumberFormat('es-PE', {minimumFractionDigits: 2}).format(subtotal)}
                            </span>
                        </div>
                        <button className="w-full bg-[#A42C6C] text-white py-3 rounded-lg font-semibold hover:bg-[#92265b] transition-colors duration-300">
                            Finalizar Compra
                        </button>
                        <button
                            onClick={clearCart}
                            className="w-full mt-2 text-center text-sm text-gray-600 dark:text-gray-200 hover:text-red-500 border border-gray-300 hover:border-red-500 rounded-lg py-2 transition-colors duration-300"
                        >
                            Vaciar Carrito
                        </button>
                    </div>
                )}
            </div>
        </>
    );
}
