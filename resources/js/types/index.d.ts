import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    role: 'admin' | 'user'; // <-- AÑADIR ESTA LÍNEA
    avatar?: string;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Category {
    id: number;
    name: string;
    description?: string;
}

export interface Product {
    id: number;
    name: string;
    description?: string;
    price: string;
    stock: number;
    image_url?: string;
    category_id: number;
    category?: Category;
}

export interface Order {
    id: number;
    user_id: number;
    total: string;
    status: 'pending' | 'processing' | 'completed' | 'cancelled';
    shipping_address?: string;
    notes?: string;
    created_at: string;
    user?: User; // La relación que cargamos con ::with('user')
}

export interface Payment {
    id: number;
    order_id: number;
    amount: string;
    payment_method: 'credit_card' | 'paypal' | 'bank_transfer';
    status: 'pending' | 'succeeded' | 'failed' | 'refunded';
    transaction_id?: string;
    created_at: string;
    order?: Order; // La relación que cargamos con ::with('order.user')
}

export interface Paginated<T> {
    data: T[];
    links: { url: string | null; label: string; active: boolean }[];
}
