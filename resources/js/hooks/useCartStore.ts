import { create } from 'zustand';
import axios from 'axios';

interface CartItem {
    id: number;
    name: string;
    price: number;
    quantity: number;
    image?: string;
}

interface Producto {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    url_imagen: string;
}

interface CartState {
    items: CartItem[];
    addToCart: (product: Producto) => void;
    fetchCart: () => void;
    clearCart: () => void;
    removeFromCart: (productId: number) => void;
    updateQuantity: (productId: number, quantity: number) => void;
}

const useCartStore = create<CartState>((set, get) => ({
    items: [],
    fetchCart: async () => {
        try {
            const response = await axios.get('/cart');
            const cartData = response.data;
            const items = Object.values(cartData) as CartItem[];
            set({ items });
        } catch (error) {
            console.error('Error fetching cart:', error);
        }
    },
    addToCart: async (product) => {
        try {
            await axios.post('/cart/add', { product_id: product.id });
            get().fetchCart(); // Recargar el carrito desde el servidor
        } catch (error) {
            console.error('Error adding to cart:', error);
        }
    },
    removeFromCart: async (productId) => {
        try {
            await axios.post('/cart/remove', { product_id: productId });
            get().fetchCart();
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    },
    updateQuantity: async (productId, quantity) => {
        if (quantity <= 0) {
            get().removeFromCart(productId);
        } else {
            try {
                await axios.post('/cart/update', { product_id: productId, quantity });
                get().fetchCart();
            } catch (error) {
                console.error('Error updating quantity:', error);
            }
        }
    },
    clearCart: async () => {
        try {
            await axios.post('/cart/clear');
            set({ items: [] });
        } catch (error) {
            console.error('Error clearing cart:', error);
        }
    },
}));

export default useCartStore;
