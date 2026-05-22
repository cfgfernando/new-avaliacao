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
                    DEFAULT: '#1c2434', // Fundo da Sidebar (Deep Navy)
                    dark: '#111827',    // Títulos e textos principais
                    light: '#8a99af',   // Texto inativo na Sidebar
                    soft: '#1351b4',    // Primary Soft (Cor institucional para botões e links)
                },
                accent: {
                    DEFAULT: '#2563eb', // Azul Royal
                    hover: '#1d4ed8',   // Hover Azul Royal
                },
                background: {
                    DEFAULT: '#f8fafc', // Fundo Geral (Slipped Light Slate)
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

    safelist: [
        'bg-rose-600', 'bg-amber-500', 'bg-blue-600', 'bg-emerald-500', 'bg-emerald-700',
        'border-rose-700', 'border-amber-600', 'border-blue-700', 'border-emerald-600', 'border-emerald-800',
        'shadow-sm', 'scale-105',
        'text-slate-500', 'hover:text-slate-800', 'border-transparent', 'bg-transparent',
        'bg-rose-50/30', 'border-rose-300/80',
        'border-l-4', 'border-l-rose-500', 'border-l-amber-500', 'border-l-blue-500', 'border-l-emerald-500', 'border-l-emerald-700',
        'bg-rose-50/20', 'bg-amber-50/20', 'bg-blue-50/20', 'bg-emerald-50/20',
        'text-gray-900',
    ],

    plugins: [forms],
};
