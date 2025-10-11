/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        // Xerxia Brand Colors - Wine & White Smoke
        primary: {
          // Wine shades
          50: '#fdf4f5',
          100: '#fbe8eb',
          200: '#f7d5da',
          300: '#f0b3bd',
          400: '#e68599',
          500: '#d6576f',
          600: '#c13a5a',
          700: '#a02b48',
          800: '#86273f',
          900: '#5B2333', // Base Wine
          950: '#3d1722',
        },
        accent: {
          // Rose Gold & Champagne
          50: '#fef7f3',
          100: '#fdeee5',
          200: '#fbd9ca',
          300: '#f8bea4',
          400: '#f39878',
          500: '#ed7551',
          600: '#db5b39',
          700: '#b74a2f',
          800: '#92402c',
          900: '#753727',
          950: '#3f1b13',
        },
        neutral: {
          // White Smoke & elegant grays
          50: '#F7F4F3', // White Smoke - Base
          100: '#f0ebe9',
          200: '#e3dbd8',
          300: '#cfc3be',
          400: '#b5a49d',
          500: '#9c8981',
          600: '#8a7670',
          700: '#73625d',
          800: '#60524e',
          900: '#514643',
          950: '#2c2523',
        },
        // Burgundy for deeper accents
        burgundy: {
          50: '#fef2f3',
          100: '#fde6e7',
          200: '#fbd0d5',
          300: '#f7aab2',
          400: '#f27a8a',
          500: '#e74c64',
          600: '#d03050',
          700: '#b02344',
          800: '#931f3f',
          900: '#7d1f3b',
          950: '#450c1c',
        },
        // Gold for luxury accents
        gold: {
          50: '#fefcf3',
          100: '#fef9e6',
          200: '#fcf0c7',
          300: '#f9e29d',
          400: '#f5cd6b',
          500: '#f0b746',
          600: '#e19b2b',
          700: '#bc7c21',
          800: '#97611f',
          900: '#7a501e',
          950: '#462a0e',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
        display: ['Playfair Display', 'Georgia', 'serif'], // Elegant display font
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-up': 'slideUp 0.4s ease-out',
        'slide-down': 'slideDown 0.4s ease-out',
        'scale-in': 'scaleIn 0.2s ease-out',
        'shimmer': 'shimmer 2s linear infinite',
        'float': 'float 3s ease-in-out infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideDown: {
          '0%': { transform: 'translateY(-10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        scaleIn: {
          '0%': { transform: 'scale(0.95)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-1000px 0' },
          '100%': { backgroundPosition: '1000px 0' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
      },
      backdropBlur: {
        xs: '2px',
      },
      boxShadow: {
        'soft': '0 2px 15px -3px rgba(91, 35, 51, 0.08), 0 10px 20px -2px rgba(91, 35, 51, 0.04)',
        'soft-lg': '0 10px 40px -10px rgba(91, 35, 51, 0.12)',
        'glow': '0 0 20px rgba(91, 35, 51, 0.25)',
        'glow-lg': '0 0 40px rgba(91, 35, 51, 0.35)',
        'gold': '0 0 30px rgba(240, 183, 70, 0.3)',
      },
    },
  },
  plugins: [],
}
