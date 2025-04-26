import './bootstrap';
import '@hotwired/turbo';

import collapse from '@alpinejs/collapse'
import Alpine from 'alpinejs';
Alpine.plugin(collapse)
window.Alpine = Alpine;

Alpine.start();
