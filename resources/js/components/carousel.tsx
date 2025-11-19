import { useState, useEffect, useCallback } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

interface CarouselProps {
    images: string[];
    autoplayInterval?: number; // milisegundos
}

export function Carousel({ images, autoplayInterval = 3000 }: CarouselProps) {
    const [currentIndex, setCurrentIndex] = useState(0);
    const [isTransitioning, setIsTransitioning] = useState(false);
    const itemsToShow = 2;

    const nextSlide = useCallback(() => {
        if (isTransitioning) return;

        setIsTransitioning(true);
        setCurrentIndex((prev) => {
            // Si llegamos al final, volvemos al inicio
            if (prev + itemsToShow >= images.length) {
                return 0;
            }
            return prev + 1;
        });

        setTimeout(() => setIsTransitioning(false), 500);
    }, [isTransitioning, itemsToShow, images.length]);

    const prevSlide = () => {
        if (isTransitioning) return;

        setIsTransitioning(true);
        setCurrentIndex((prev) => {
            // Si estamos al inicio, vamos al final
            if (prev === 0) {
                return Math.max(0, images.length - itemsToShow);
            }
            return prev - 1;
        });

        setTimeout(() => setIsTransitioning(false), 500);
    };

    // Autoplay
    useEffect(() => {
        const interval = setInterval(() => {
            nextSlide();
        }, autoplayInterval);

        return () => clearInterval(interval);
    }, [nextSlide, autoplayInterval]);

    return (
        <div className="relative w-full bg-white rounded-lg shadow-lg p-6 dark:bg-[#2a2a2a] transition-colors duration-300">
            {/* Contenedor de imágenes */}
            <div className="overflow-hidden">
                <div
                    className="flex gap-4 transition-transform duration-500 ease-in-out"
                    style={{
                        transform: `translateX(-${currentIndex * (100 / itemsToShow)}%)`,
                    }}
                >
                    {images.map((image, index) => (
                        <div
                            key={index}
                            className="flex-shrink-0 transition-all duration-500"
                            style={{ width: `calc(${100 / itemsToShow}% - ${(itemsToShow - 1) * 8}px / ${itemsToShow})` }}
                        >
                            <img
                                src={image}
                                alt={`Slide ${index + 1}`}
                                className="w-full h-64 object-cover rounded-lg"
                            />
                        </div>
                    ))}
                </div>
            </div>

            {/* Botón anterior */}
            <button
                onClick={prevSlide}
                disabled={isTransitioning}
                className="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:scale-110"
                aria-label="Anterior"
            >
                <ChevronLeft className="w-6 h-6" />
            </button>

            {/* Botón siguiente */}
            <button
                onClick={nextSlide}
                disabled={isTransitioning}
                className="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:scale-110"
                aria-label="Siguiente"
            >
                <ChevronRight className="w-6 h-6" />
            </button>

        </div>
    );
}
