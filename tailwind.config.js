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
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                primary: {
                    DEFAULT: '#0f172a', // Sidebar Background (Navy Profundo)
                    dark: '#020617',    // Texto Principal / Títulos (Contraste Máximo)
                    light: '#94a3b8',   // Texto Secundário / Inativo
                },
                accent: {
                    DEFAULT: '#2563eb', // Azul Royal (Ações Principais)
                    hover: '#1d4ed8',   // Hover Azul Royal
                },
                background: {
                    DEFAULT: '#f8fafc', // Fundo Principal da Aplicação
                },
                rose: {
                    150: '#ffe4e6',     // Light custom rose
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
