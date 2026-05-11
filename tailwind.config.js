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
                    DEFAULT: '#0A0A0A',
                    dark: '#000000',
                    light: '#171717',
                    card: '#111111',
                },
                accent: {
                    DEFAULT: '#10B981', // Emerald Neon
                    glow: '#34D399',
                    dark: '#065F46',
                },
                surface: {
                    50: '#F9FAFB',
                    100: '#F3F4F6',
                    200: '#E5E7EB',
                    900: '#111827',
                },
            },
            boxShadow: {
                'emerald-glow': '0 0 20px rgba(16, 185, 129, 0.2)',
                'contact': '0 25px 50px -12px rgba(0, 0, 0, 0.5)',
            },
            borderRadius: {
                'none': '0',
                'sm': '2px',
                'md': '4px',
                'lg': '8px',
                'xl': '12px',
                '2xl': '0px', // KILLING 2XL
                '3xl': '0px', // KILLING 3XL
            },
            animation: {
                'reveal-up': 'revealUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'reveal-left': 'revealLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
            },
            keyframes: {
                revealUp: {
                    '0%': { transform: 'translateY(40px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                revealLeft: {
                    '0%': { transform: 'translateX(40px)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};

