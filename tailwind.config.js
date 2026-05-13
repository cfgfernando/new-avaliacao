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
                    DEFAULT: '#1c2434', // Sidebar Bg (Deep Navy)
                    dark: '#111827',    // Deep Dark Text
                    light: '#8a99af',   // Sidebar Inactive Text
                },
                accent: {
                    DEFAULT: '#f59e0b', // Amber/Orange
                    hover: '#d97706',   // Darker Orange
                },
                background: {
                    DEFAULT: '#f1f5f9', // Main Content Bg (Off-white)
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
