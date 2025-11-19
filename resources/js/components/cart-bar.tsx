import { ShoppingCart } from 'lucide-react';
import useCartStore from '@/hooks/useCartStore';

interface CartBarProps {
    onClick: () => void;
}

export function CartBar({ onClick }: CartBarProps) {
    const items = useCartStore((state) => state.items);
    const itemCount = items.reduce((total, item) => total + item.quantity, 0);

    return (
        <button
            onClick={onClick}
            className="fixed right-0 top-1/2 -translate-y-1/2 z-40 bg-[#A42C6C] hover:bg-[#8B2557] text-white p-3 rounded-l-lg shadow-lg transition-all duration-300 flex items-center gap-2"
            aria-label="Abrir carrito"
        >
            <ShoppingCart className="w-6 h-6" />
            {itemCount > 0 && (
                <span className="bg-white text-[#A42C6C] text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                    {itemCount}
                </span>
            )}
        </button>
    );
}
