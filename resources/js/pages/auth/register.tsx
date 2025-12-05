import { Head } from '@inertiajs/react';
import AuthLayout from '@/layouts/auth-layout';
import { RegisterForm } from '@/components/register-form';

export default function Register() {
    return (
        <AuthLayout
            title="Create an account"
            description="Enter your details below to create your account"
        >
            <Head title="Register" />
            <RegisterForm />
        </AuthLayout>
    );
}
