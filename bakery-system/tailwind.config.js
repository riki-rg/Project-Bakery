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
                display: ['Fraunces', 'Georgia', 'serif'],
                sans: ['Plus Jakarta Sans', 'Inter', 'Arial', 'sans-serif'],
            },
            colors: {
                // R Bread's Brand Colors
                primary: {
                    DEFAULT: '#B95324',
                    hover: '#98401C',
                    active: '#7C3217',
                    soft: '#F9E2D2',
                },
                accent: {
                    DEFAULT: '#D99424',
                    soft: '#FFF0C7',
                },
                // Canvas & Surface
                canvas: '#FFF9F0',
                surface: {
                    DEFAULT: '#FFFEFC',
                    subtle: '#F8F1E7',
                    strong: '#3C2418',
                },
                // Text
                text: {
                    main: '#33221B',
                    muted: '#716057',
                    subtle: '#907D73',
                    inverse: '#FFFCF7',
                },
                // Border
                border: {
                    DEFAULT: '#E8D9C9',
                    strong: '#CBB5A1',
                },
                // Status Colors
                success: {
                    DEFAULT: '#347552',
                    soft: '#E2F2E8',
                },
                warning: {
                    DEFAULT: '#94620F',
                    soft: '#FFF0C9',
                },
                danger: {
                    DEFAULT: '#A33F3A',
                    hover: '#87332F',
                    soft: '#F9E2E0',
                },
                info: {
                    DEFAULT: '#3E7080',
                    soft: '#E1F0F3',
                },
            },
            borderRadius: {
                'sm': '0.5rem',
                'md': '0.75rem',
                'lg': '1rem',
                'xl': '1.5rem',
            },
            boxShadow: {
                'sm': '0 0.25rem 1rem rgba(51, 34, 27, 0.06)',
                'md': '0 0.75rem 2rem rgba(51, 34, 27, 0.10)',
                'focus': '0 0 0 0.25rem rgba(185, 83, 36, 0.28)',
            },
            spacing: {
                '18': '4.5rem',
                '70': '17.5rem',
                '21': '5.25rem',
            },
            maxWidth: {
                'content': '90rem',
                'form': '48rem',
                'auth': '30rem',
            },
            height: {
                'control': '2.75rem',
                'control-lg': '3.25rem',
                'topbar': '4.5rem',
            },
            transitionTimingFunction: {
                'snappy': 'cubic-bezier(0.2, 0.8, 0.2, 1)',
                'smooth': 'cubic-bezier(0.4, 0, 0.2, 1)',
                'bounce': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            },
            transitionDuration: {
                'instant': '120ms',
                'fast': '220ms',
                'base': '320ms',
                'slow': '480ms',
            },
        },
    },

    plugins: [forms],
};
