import { LoginForm } from '@/components/login-form';
import AuthLayout from '@/layouts/auth-layout';
import { Head } from '@inertiajs/react';

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}

export default function Login({
    status,
    canResetPassword,
    canRegister,
}: LoginProps) {
    return (
        <AuthLayout
            title="Inicia sesión en tu cuenta"
            description="Introduce tu correo electrónico y contraseña para iniciar sesión"
        >
            <Head title="Inicia sesión" />

            <LoginForm
                status={status}
                canResetPassword={canResetPassword}
                canRegister={canRegister}
            />

            {status && (
                <div className="mt-4 text-center text-sm font-medium text-green-600">
                    {status}
                </div>
            )}
        </AuthLayout>
    );
}
