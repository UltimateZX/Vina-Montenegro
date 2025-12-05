import AdminLayout from '@/layouts/AdminLayout';
import { Head } from '@inertiajs/react';

// Componente para las tarjetas de estadísticas
function StatCard({ title, value, icon }: { title: string; value: string; icon: React.ReactNode }) {
    return (
        <div className="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div className="flex items-center">
                <div className="flex-shrink-0 rounded-md bg-indigo-500 p-3 text-white">
                    {icon}
                </div>
                <div className="ml-4">
                    <p className="text-sm font-medium text-gray-500 dark:text-gray-400">{title}</p>
                    <p className="text-2xl font-bold text-gray-900 dark:text-white">{value}</p>
                </div>
            </div>
        </div>
    );
}

// 1. DEFINIR LA FORMA DE LAS PROPS QUE LLEGAN
interface Stats {
    totalRevenue: number;
    newOrders: number;
    newUsers: number;
    activeProducts: number;
}

interface Props {
    stats: Stats;
}

export default function Dashboard({ stats }: Props) {
    // Formateador para mostrar el dinero correctamente
    const formattedRevenue = new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN',
    }).format(stats.totalRevenue);

    return (
        <AdminLayout>
            <Head title="Dashboard" />

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {/* 2. USAR LOS DATOS DINÁMICOS */}
                <StatCard title="Ingresos Totales" value={formattedRevenue} icon={<span className="text-2xl font-bold">S/</span>} />
                <StatCard title="Nuevos Pedidos (Mes)" value={stats.newOrders.toString()} icon={<span className="text-2xl font-bold">📦</span>} />
                <StatCard title="Nuevos Usuarios (Mes)" value={stats.newUsers.toString()} icon={<span className="text-2xl font-bold">👤</span>} />
                <StatCard title="Productos Activos" value={stats.activeProducts.toString()} icon={<span className="text-2xl font-bold">🛍️</span>} />
            </div>

            <div className="mt-8">
                <div className="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                    <h3 className="text-lg font-semibold">Actividad Reciente</h3>
                    <p className="mt-4 text-gray-600 dark:text-gray-400">
                        Aquí irá una lista o un gráfico con la actividad reciente del sitio...
                    </p>
                </div>
            </div>
        </AdminLayout>
    );
}
