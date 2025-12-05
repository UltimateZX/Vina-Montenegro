import AdminLayout from '@/layouts/AdminLayout';
import { type User, type Paginated } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { PlusCircle } from 'lucide-react';

interface Props {
    users: Paginated<User>;
}

export default function UsersIndex({ users }: Props) {
    return (
        <AdminLayout>
            <Head title="Usuarios" />

            <div className="flex items-center justify-between">
                <h1 className="text-2xl font-bold text-gray-800 dark:text-white">
                    Gestión de Usuarios
                </h1>
                <Button asChild>
                    <Link href="/admin/users/create">
                        <PlusCircle className="mr-2 h-4 w-4" />
                        Crear Usuario
                    </Link>
                </Button>
            </div>

            <div className="mt-6 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <table className="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead className="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nombre</th>
                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Rol</th>
                            <th scope="col" className="relative px-6 py-3"><span className="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                        {users.data.map((user) => (
                            <tr key={user.id}>
                                <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{user.name}</td>
                                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{user.role}</td>
                                <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <Link href={`/admin/users/${user.id}/edit`} className="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Editar
                                    </Link>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            {/* Aquí iría la paginación */}
        </AdminLayout>
    );
}
