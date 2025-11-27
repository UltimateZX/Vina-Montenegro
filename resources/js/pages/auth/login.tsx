import { useEffect, FormEventHandler } from 'react';
import { useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/input-error';
import { Label } from '@/components/ui/label';

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
    onSwitchToRegister: () => void;
    onSuccess?: () => void;
}

export function LoginForm({ status, canResetPassword, onSwitchToRegister, onSuccess }: LoginProps) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, [reset]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), {
            onSuccess: () => {
                reset();
                if (onSuccess) {
                    onSuccess();
                }
            },
            onError: () => {
                reset('password');
            },
        });
    };

    return (
        <>
            {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        autoComplete="username"
                        autoFocus
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div>
                    <Label htmlFor="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="flex items-center justify-between">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onCheckedChange={(checked) => setData('remember', !!checked)}
                        />
                        <span className="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    {canResetPassword && (
                        <a
                            href={route('password.request')}
                            className="text-sm text-primary underline-offset-4 hover:underline"
                        >
                            Forgot your password?
                        </a>
                    )}
                </div>

                <Button className="w-full" disabled={processing}>
                    Log in
                </Button>

                <div className="mt-4 text-center text-sm">
                    Don't have an account?{' '}
                    <button type="button" onClick={onSwitchToRegister} className="text-primary underline font-semibold">
                        Register
                    </button>
                </div>
            </form>
        </>
    );
}
