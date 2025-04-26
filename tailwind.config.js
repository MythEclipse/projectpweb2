import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'dark-bg': '#0a0a0a',         // Background utama dark mode
                'dark-card': '#1a1a1a',       // Card utama
                'dark-subcard': '#2d2d2d',    // Card di dalam card
                'dark-border': '#3E3E3A',     // Border dark
                'pink-brand': '#EC4899',      // Pink utama
                'pink-brand-dark': '#DB2777', // Pink hover gelap
                'text-light': '#EDEDEC',      // Teks terang
                'text-dark': '#1b1b18',       // Teks gelap
            },
        },
    },

    plugins: [forms],
};
