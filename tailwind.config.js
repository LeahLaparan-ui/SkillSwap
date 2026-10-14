import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                sage: {
                    50: '#f5f7f2',
                    100: '#e9efe2',
                    200: '#d3dfc6',
                    300: '#b3c69e',
                    400: '#90a875',
                    500: '#718a55',
                    600: '#586e42',
                    700: '#455736',
                    800: '#39482e',
                    900: '#313d29',
                    950: '#1a2114',
                },
                beige: {
                    50: '#faf8f3',
                    100: '#f4f0e6',
                    200: '#e7dfcd',
                    300: '#d6c7a8',
                    400: '#c1ab80',
                    500: '#ac9260',
                    600: '#94794c',
                    700: '#7a6240',
                    800: '#655139',
                    900: '#544532',
                    950: '#2d241a',
                },
            },
        },
    },

    plugins: [forms],
};
