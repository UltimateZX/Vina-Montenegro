import AdminLayout from '@/layouts/AdminLayout';
import { type Payment, type Paginated } from '@/types';
import { Head, Link } from '@inertiajs/react';

interface Props {
    payments: Paginated<Payment>;
}

export default function PaymentsIndex({ payments }: Props) {
    return (
        <AdminLayout>
            <Head title="Pagos" />
            <h1 className="text-2xl font-bold text-gray-800 dark:text-white">Gestión de Pagos</h1>

            <div className="mt-6 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <table className="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead className="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">ID Pago</th>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Cliente</th>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Monto</th>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Método</th>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Estado</th>
                            <th className="relative px-6 py-3"><span className="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                        {payments.data.map((payment) => (
                            <tr key={payment.id}>
                                <td className="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">#{payment.id}</td>
                                <td className="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{payment.order?.user?.name}</td>
                                <td className="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">S/ {payment.amount}</td>
                                <td className="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{payment.payment_method}</td>
                                <td className="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{payment.status}</td>
                                <td className="px-6 py-4 text-right text-sm font-medium">
                                    <Link href={`/admin/payments/${payment.id}`} className="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Ver
                                    </Link>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AdminLayout>
    );
}
