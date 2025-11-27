export function WelcomeFooter() {
    return (
        // Contenedor principal del footer con un fondo oscuro unificado
        <footer className="w-full bg-[#333] text-gray-300">
            {/* Contenido principal del footer */}
            <div className="container mx-auto px-6 py-3">
                {/* Contenedor flexible para los enlaces, centrado y responsivo */}
                <div className="flex flex-col sm:flex-row justify-center items-center gap-x-6 gap-y-2 text-sm">
                    <span className="text-white uppercase text-sm p-4 text-center tracking-wider">Tomar bebidas alcohólicas en exceso es dañino</span>
                    <span>© 2024 Viña Montenegro</span>
                    <span>Contacto: 999 999 999</span>
                    <a href="#" className="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" className="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </footer>
    );
}
