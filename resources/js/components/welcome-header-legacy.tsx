import { dashboard } from '@/routes';
import { type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { LoginModal } from '@/components/login-modal';
import { RegisterModal } from '@/components/register-modal';
import { useState, useEffect } from 'react';
import { Moon, Sun } from 'lucide-react';

export function WelcomeHeader() {
    const { auth } = usePage<SharedData>().props;
    const [loginOpen, setLoginOpen] = useState(false);
    const [registerOpen, setRegisterOpen] = useState(false);
    const [isDark, setIsDark] = useState(false);

    useEffect(() => {
        setIsDark(document.documentElement.classList.contains('dark'));
    }, []);

    const toggleTheme = () => {
        if (isDark) {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
        setIsDark(!isDark);
    };

    return (
        <>
            <header className="h-[10vh] flex w-full z-50 text-sm not-has-[nav]:hidden bg-[#F20055] items-center justify-end p-4 sticky top-0">
                <img
                    src="/img/logo.jpg"
                    alt="Logo Viña Montenegro"
                    className="h-20 mr-auto"
                />
                <nav className="flex items-center justify-end gap-4">

                    <button
                        onClick={toggleTheme}
                        className="inline-flex items-center justify-center rounded-sm border border-white/20 p-2 text-white hover:text-gray-200 hover:bg-white/10 dark:text-black dark:hover:text-gray-200 transition-all duration-300"
                        aria-label="Toggle Dark Mode"
                    >
                        {isDark ? <Sun size={16} /> : <Moon size={16} />}
                    </button>
                    {auth.user ? (
                        <Link
                            href={dashboard()}
                            className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                        >
                            Dashboard
                        </Link>
                    ) : (
                        <>
                            <button
                                onClick={() => setLoginOpen(true)}
                                className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-white hover:border-white/30 hover:bg-white/10 transition-all"
                            >
                                Log in
                            </button>
                            <button
                                onClick={() => setRegisterOpen(true)}
                                className="inline-block rounded-sm border border-white/30 px-5 py-1.5 text-sm leading-normal text-white hover:border-white hover:bg-white/10 transition-all"
                            >
                                Register
                            </button>
                        </>
                    )}
                </nav>
            </header>

            <LoginModal open={loginOpen} onOpenChange={setLoginOpen} />
            <RegisterModal open={registerOpen} onOpenChange={setRegisterOpen} />
        </>
    );
}
