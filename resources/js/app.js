import './bootstrap';
import '@hotwired/turbo';
import mask from '@alpinejs/mask';
import persist from '@alpinejs/persist';
import morph from '@alpinejs/morph';
// AlpineJS dan plugin-plugin
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

// Daftarkan plugin satu per satu
Alpine.plugin(collapse);
Alpine.plugin(intersect);
Alpine.plugin(mask);
Alpine.plugin(persist);
Alpine.plugin(morph);
// Pasang Alpine ke window
window.Alpine = Alpine;

// Mulai Alpine
Alpine.start();
