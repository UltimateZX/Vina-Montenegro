
export function WelcomeFooter() {

    return (
        <footer className="h-[10vh] w-full text-sm not-has-[nav]:hidden items-center bottom-0">
            {/* advertencia */}
            <div className="bg-[#111] text-white uppercase text-[15px] p-5 text-center">
                Tomar bebidas alcoholicas en exceso es dañino
            </div>

            {/* footer */}
            <nav className="flex bg-[#333] gap-4 p-5 justify-end">
                <div className="text-white">© 2024 Viña Montenegro</div>
                <div className="text-white">Contacto: 999 999 999</div>
                <div className="text-white">Privacy Policy</div>
                <div className="text-white">Terms of Service</div>
            </nav>
        </footer>
    );
}
