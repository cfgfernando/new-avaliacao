import defaultTheme from 'tailwindcss/defaultTheme';

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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#1a2332',
                    dark: '#0f151f',
                    light: '#2c3a4f',
                },
                accent: {
                    DEFAULT: '#d4af37',
                    dark: '#b8962d',
                    light: '#fcd34d',
                },
                success: '#10b981',
                warning: '#f59e0b',
                error: '#ef4444',
                info: '#3b82f6',
            },
            boxShadow: {
                'accent': '0 10px 15px -3px rgba(212, 175, 55, 0.2)',
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.5rem',
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
