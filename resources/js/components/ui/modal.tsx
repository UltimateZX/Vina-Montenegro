import { X } from 'lucide-react';
import { ReactNode } from 'react';

interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    title: string;
    description: string;
    children: ReactNode;
}

export function Modal({ isOpen, onClose, title, description, children }: ModalProps) {
    if (!isOpen) return null;

    return (
        // Overlay
        <div
            onClick={onClose}
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
        >
            {/* Contenedor del Modal */}
            <div
                onClick={(e) => e.stopPropagation()} // Evita que el clic dentro del modal lo cierre
                className="relative w-full max-w-md p-8 m-4 bg-white rounded-2xl shadow-xl dark:bg-gray-800"
            >
                {/* Botón de cierre */}
                <button
                    onClick={onClose}
                    className="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white"
                >
                    <X size={24} />
                </button>

                {/* Encabezado */}
                <div className="text-center mb-6">
                    <h2 className="text-2xl font-bold text-gray-900 dark:text-white">{title}</h2>
                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">{description}</p>
                </div>

                {/* Contenido del formulario */}
                {children}
            </div>
        </div>
    );
}
