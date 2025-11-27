import { create } from 'zustand';
import axios from 'axios';

// Interfaz para los items DENTRO del carrito (consistente con el backend)
interface CartItem {
    id: number;
    name: string;
    price: number;
    quantity: number;
    image?: string;
}

// Interfaz para un producto que se va a AÑADIR (desde ProductCard)
interface Producto {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    url_imagen: string;
}

interface CartState {
    items: CartItem[];
    addToCart: (product: Producto, quantity: number) => void;
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
            const items = response.data as CartItem[];
            set({ items });
        } catch (error) {
            console.error('Error fetching cart:', error);
            set({ items: [] }); // En caso de error, asegurar que el carrito esté vacío
        }
    },
    addToCart: async (product, quantity) => {
        try {
            // Usamos los nombres de campo que el backend espera
            await axios.post('/cart/add', {
                product_id: product.id,
                quantity: quantity
            });
            // Después de añadir, volvemos a pedir el carrito actualizado al backend
            get().fetchCart();
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
            // Si la cantidad es 0 o menos, simplemente lo eliminamos
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
            // Vaciamos el estado local inmediatamente para una respuesta visual rápida
            set({ items: [] });
        } catch (error) {
            console.error('Error clearing cart:', error);
        }
    },
}));

export default useCartStore;
