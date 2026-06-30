import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    'node_modules/preline/dist/*.js'
  ],

  theme: {
    screens: {
      sm: '480px',
      md: '768px',
      lg: '976px',
      xl: '1440px',
      "2xl": '1636px',
    },
    colors: {
      'transparent': 'transparent',
      'win-charcoal': "#231F20",
      'win-lavender': "#D5C6E7",
      'win-red': "#F85705",
      'win-pink': "#F1D3D3",
      'win-purple': "#6432C8",
      'win-orange': "#FB962F",
      'win-peach': "#EABDA8",
      'win-beige': "#FCF6F3",
      'win-blue': "#5A7EFF",
      'win-light': "#FDF6F3",
      'blue': '#1fb6ff',
      'dark-blue-win': '#171C27',
      'blue-win': '#1D67CD',
      'pink-win': '#F9C7CE',
      'light-purple-win': '#C6B9CD',
      'orange': '#ff7849',
      'green': '#13ce66',
      'yellow': {
        DEFAULT: '#ffc82c',
        400: '#FACC15',
      },
      'gray-dark': '#273444',
      'gray': {
        DEFAULT: '#8492a6',
        300: '#D1D5DB'
      },
      'gray-light': '#d3dce6',
      'white': '#FFFFFF',
      'black': '#000000',
      'dark-purple-win': '#A238FF',
      'purple-win': '#f1e1ff',
      'dark-grey-win': '#222323',
      "red": {
        DEFAULT: "#ef4444",
        50: "#fef2f2",
        100: "#fee2e2",
        200: "#fecaca",
        500: "#FEF2F2",
        800: "#991b1b",
      },
      'teal': {
        DEFAULT: '#14B8A6',
        100: '#CCFBF1',
        500: '#14b8a6',
        800: '#115e59'
      },
      'grey-faint': '#f7fafc',
      'grey': {
        50: '#f9fafb',
        100: '#f3f4f6'
      }
    },
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('preline/plugin'),
    require("@tailwindcss/line-clamp"),
  ],
};
