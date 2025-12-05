import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import { type Product } from '@/types';

// Define el tipo para un item en el carrito (un producto con cantidad)
export interface CartItem extends Product {
    quantity: number;
}

// Define la forma del estado y las acciones
interface CartState {
    items: CartItem[];
    addToCart: (product: Product, quantity: number) => void; // <-- MODIFICAR FIRMA
    removeFromCart: (productId: number) => void;
    updateQuantity: (productId: number, quantity: number) => void;
    clearCart: () => void;
}

const useCartStore = create<CartState>()(
    persist(
        (set, get) => ({
            items: [],
            addToCart: (product, quantity) => { // <-- MODIFICAR FIRMA
                const cart = get().items;
                const cartItem = cart.find((item) => item.id === product.id);

                if (cartItem) {
                    // Si el item ya existe, actualiza la cantidad
                    const updatedCart = cart.map((item) =>
                        item.id === product.id
                            ? { ...item, quantity: item.quantity + quantity } // Suma la nueva cantidad
                            : item
                    );
                    set({ items: updatedCart });
                } else {
                    // Si es un item nuevo, añádelo con la cantidad especificada
                    set({ items: [...cart, { ...product, quantity }] });
                }
            },
            removeFromCart: (productId) => {
                set({ items: get().items.filter((item) => item.id !== productId) });
            },
            updateQuantity: (productId, quantity) => {
                if (quantity <= 0) {
                    // Si la cantidad es 0 o menos, elimina el item
                    get().removeFromCart(productId);
                } else {
                    set({
                        items: get().items.map((item) =>
                            item.id === productId ? { ...item, quantity } : item
                        ),
                    });
                }
            },
            clearCart: () => {
                set({ items: [] });
            },
        }),
        {
            name: 'cart-storage', // Nombre para el almacenamiento en localStorage
        }
    )
);

export default useCartStore;
