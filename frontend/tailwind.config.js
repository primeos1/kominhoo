/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  corePlugins: {
    preflight: false,   // don't reset browser defaults — style.css handles that
    container: false,   // disable Tailwind's .container so style.css version wins
  },
  theme: {
    extend: {
      fontFamily: {
        serif: ['"DM Serif Display"', 'Georgia', 'serif'],
      },
      colors: {
        violet: {
          50:  '#f5f3ff',
          100: '#ede9fe',
          200: '#ddd6fe',
          300: '#c4b5fd',
          400: '#a78bfa',
          500: '#8b5cf6',
          600: '#7c3aed',
          700: '#6d28d9',
          800: '#5b21b6',
          900: '#4c1d95',
          950: '#2e1065',
        },
        slate: {
          50:  '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617',
        },
      },
      opacity: {
        3:  '.03',
        6:  '.06',
        8:  '.08',
        12: '.12',
        15: '.15',
        18: '.18',
        22: '.22',
        35: '.35',
        38: '.38',
        45: '.45',
        85: '.85',
      },
    },
  },
  plugins: [],
}
