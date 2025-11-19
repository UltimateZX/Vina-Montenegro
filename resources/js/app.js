import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Lógica para el menú de usuario
window.toggleUserMenu = function() {
    var dropdown = document.getElementById('userDropdown');
    // Usamos una clase para controlar la visibilidad
    dropdown.classList.toggle('is-active');
}

// Cierra el menú si se hace clic fuera
window.onclick = function(event) {
    if (!event.target.matches('.user-menu-btn')) {
        var dropdowns = document.getElementsByClassName("user-menu-content");
        for (var i = 0; i < dropdowns.length; i++) {
            dropdowns[i].style.display = 'none';
        }
    }
}

// Lógica para el mini-carrito
window.toggleMiniCart = function() {
    var cart = document.getElementById('miniCartPartial');
    var backdrop = document.getElementById('miniCartBackdrop');

    // Leemos el contador del carrito directamente desde el HTML
    // Esto asegura que siempre tengamos el valor más reciente
    const cartCounter = document.querySelector('.header-nav a[href*="cart.index"]');
    // Extraemos el número del texto "Carrito (X)"
    const cartCount = parseInt(cartCounter.textContent.match(/\((\d+)\)/)[1], 10);

    // La lógica ahora se basa en una clase, no en estilos inline
    const isVisible = cart.classList.contains('is-visible');

    if (isVisible) {
        cart.classList.remove('is-visible');
        backdrop.classList.remove('is-visible');
    } else if (cartCount > 0) { // Solo se abre si el carrito no está vacío
        cart.classList.add('is-visible');
        backdrop.classList.add('is-visible');
    }
}
