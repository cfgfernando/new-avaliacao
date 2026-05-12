import forms from '@tailwindcss/forms';
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
                    DEFAULT: '#0f172a', // Sidebar Bg (Slate 900 - More modern/darker navy)
                    dark: '#020617',    // Deep Dark Text (Slate 950 - Maximum contrast)
                    light: '#94a3b8',   // Sidebar Inactive Text (Slate 400 - Better readability)
                },
                accent: {
                    DEFAULT: '#ea580c', // Orange 600 (More vibrant than amber, better contrast)
                    hover: '#c2410c',   // Orange 700
                },
                background: {
                    DEFAULT: '#f8fafc', // Main Content Bg (Slate 50 - Cleaner)
                }
            },
            boxShadow: {
                'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            }
        },
    },

    plugins: [forms],
};
