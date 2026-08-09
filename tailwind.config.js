import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palette tirée de la maquette de la page d'accueil
                primary: {
                    50: '#fbf2f2',
                    100: '#f4dcdd',
                    200: '#e6b3b5',
                    300: '#d1868a',
                    400: '#b4535a',
                    500: '#8f2a32',
                    600: '#7a1f2b', // bordeaux principal (boutons, header)
                    700: '#621925',
                    800: '#4f151f',
                    900: '#3a0f17',
                },
            gold: {
                    50:  '#fdf8ed',
                    100: '#faefd0',
                    300: '#e8c273',
                    400: '#d9a441',
                    500: '#c8912e',
                    600: '#a97722',
                    700: '#8a6019',
                },
                cream: '#f7f3ee', // fond de page
            },
        },
    },

    plugins: [forms, require('@tailwindcss/typography')],
};
