

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if (document.getElementById('hero3d-canvas')) {
    import('./hero3d.js').then((m) => m.initHero3D());
}
