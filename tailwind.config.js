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
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['"Instrument Serif"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brillance: {
                    bg: '#f7f5f3',
                    paper: '#eeece8',
                    charcoal: '#37322f',
                    muted: '#605a57',
                    subtle: '#716a64',
                    lime: '#d7f56b',
                    limeHover: '#e2ff88',
                    moss: '#849451',
                    olive: '#71834f',
                },
            },
        },
    },

    plugins: [forms],
};
