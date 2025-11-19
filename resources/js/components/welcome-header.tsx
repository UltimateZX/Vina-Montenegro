import { Link } from '@inertiajs/react';

export function WelcomeHeader() {
    return (
        <header className="sticky top-0 z-50 w-full bg-primary text-white shadow-md" style={{ height: '60px' }}>
            <div className="container mx-auto flex h-full items-center justify-between px-4">
                <Link href="/" className="text-xl font-bold">
                    Viña Montenegro
                </Link>

                <div className="flex items-center gap-5">
                    <Link href="login" className="font-medium">
                        Log in
                    </Link>
                    <Link href="register" className="font-medium">
                        Register
                    </Link>
                </div>
            </div>
        </header>
    );
}
