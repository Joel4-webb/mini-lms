import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Nouvelles couleurs (Soft UI)
                'lms-primary': '#4F46E5', 
                'lms-primary-light': '#EEF2FF', 
                'lms-bg': '#F8FAFC', 
                
                // Anciennes couleurs (conservées temporairement pour éviter les erreurs de compilation)
                'lms-beige': '#F5F2EC',
                'lms-red': '#D32F2F',
                'lms-bleu-nuit': '#0A0B6B',
                'lms-dark': '#1C1B1A',
            },
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                pixel: ['"Press Start 2P"', 'monospace'],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};