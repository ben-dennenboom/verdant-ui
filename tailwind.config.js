/** @type {import('tailwindcss').Config} */
export default {
  prefix: 'v-',
  important: false,
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  safelist: [
    {
      pattern: /v-(bg|text|border)-(red|green|blue|orange|yellow|gray|primary|secondary)-[1-9]00/,
    },
  ],
  theme: {
    extend: {
      colors: {
        blue: {
          900: '#0A2540',
          800: '#114B7F',
          600: '#2A76D2',
          400: '#63A9F7',
          200: '#B3D9FF',
          100: '#E6F2FF',
        },
        gray: {
          100: '#F3F4F6',
          200: '#E5E7EB',
          300: '#D1D5DB',
          900: '#1F2937',
        },
        primary: {
          DEFAULT: 'rgb(var(--color-primary) / <alpha-value>)',
          50: 'rgb(var(--color-primary-50) / <alpha-value>)',
          100: 'rgb(var(--color-primary-100) / <alpha-value>)',
          200: 'rgb(var(--color-primary-200) / <alpha-value>)',
          300: 'rgb(var(--color-primary-300) / <alpha-value>)',
          400: 'rgb(var(--color-primary-400) / <alpha-value>)',
          500: 'rgb(var(--color-primary-500) / <alpha-value>)',
          600: 'rgb(var(--color-primary-600) / <alpha-value>)',
          700: 'rgb(var(--color-primary-700) / <alpha-value>)',
          800: 'rgb(var(--color-primary-800) / <alpha-value>)',
          900: 'rgb(var(--color-primary-900) / <alpha-value>)',
        },
        secondary: {
          DEFAULT: 'rgb(var(--color-secondary) / <alpha-value>)',
          50: 'rgb(var(--color-secondary-50) / <alpha-value>)',
          100: 'rgb(var(--color-secondary-100) / <alpha-value>)',
          200: 'rgb(var(--color-secondary-200) / <alpha-value>)',
          300: 'rgb(var(--color-secondary-300) / <alpha-value>)',
          400: 'rgb(var(--color-secondary-400) / <alpha-value>)',
          500: 'rgb(var(--color-secondary-500) / <alpha-value>)',
          600: 'rgb(var(--color-secondary-600) / <alpha-value>)',
          700: 'rgb(var(--color-secondary-700) / <alpha-value>)',
          800: 'rgb(var(--color-secondary-800) / <alpha-value>)',
          900: 'rgb(var(--color-secondary-900) / <alpha-value>)',
        },
      }
    }
  },
  plugins: [
    function({ addBase, theme }) {
      addBase({
        ':root': {
          '--color-primary': '12 94 130',
          '--color-primary-50': '230 246 252',
          '--color-primary-100': '204 237 249',
          '--color-primary-200': '153 219 243',
          '--color-primary-300': '102 201 237',
          '--color-primary-400': '51 183 231',
          '--color-primary-500': '19 156 216',
          '--color-primary-600': '16 125 173',
          '--color-primary-700': '19 156 216',
          '--color-primary-800': '12 94 130',
          '--color-primary-900': '8 63 87',

          '--color-secondary': '45 52 65',
          '--color-secondary-50': '247 248 249',
          '--color-secondary-100': '239 241 243',
          '--color-secondary-200': '223 226 231',
          '--color-secondary-300': '207 212 219',
          '--color-secondary-400': '191 198 207',
          '--color-secondary-500': '175 184 195',
          '--color-secondary-600': '142 156 173',
          '--color-secondary-700': '109 127 151',
          '--color-secondary-800': '76 99 129',
          '--color-secondary-900': '45 52 65',
        }
      });
    },

    function({ addComponents, theme }) {
      const formStyles = {
        '.v-form-input': {
          appearance: 'none',
          backgroundColor: '#ffffff',
          borderColor: theme('colors.gray.300'),
          borderWidth: '1px',
          borderRadius: theme('borderRadius.DEFAULT'),
          paddingTop: theme('spacing.2'),
          paddingRight: theme('spacing.3'),
          paddingBottom: theme('spacing.2'),
          paddingLeft: theme('spacing.3'),
          fontSize: theme('fontSize.sm'),
          lineHeight: theme('lineHeight.normal'),
          '&:focus': {
            outline: 'none',
            boxShadow: `0 0 0 3px ${theme('colors.blue.200')}`,
            borderColor: theme('colors.blue.300'),
          },
        },
        '.v-form-checkbox': {
          appearance: 'none',
          color: theme('colors.white'),
          width: theme('spacing.4'),
          height: theme('spacing.4'),
          border: `1px solid ${theme('colors.gray.300')}`,
          borderRadius: theme('borderRadius.DEFAULT'),
          backgroundColor: '#ffffff',
          '&:focus': {
            outline: 'none',
            boxShadow: `0 0 0 3px ${theme('colors.blue.200')}`,
            borderColor: theme('colors.blue.300'),
          },
          '&:checked': {
            borderColor: 'transparent',
            backgroundColor: 'currentColor',
            backgroundSize: '100% 100%',
            backgroundPosition: 'center',
            backgroundRepeat: 'no-repeat',
          },
        },
      };

      addComponents(formStyles);
    },

    function({ addBase, theme, config }) {
      const scopeClass = 'v-scope';

      addBase({
        [`.${scopeClass}`]: {
          fontFamily: theme('fontFamily.sans', 'ui-sans-serif, system-ui, sans-serif'),
          fontSize: '16px',
          lineHeight: '1.5',
          color: theme('colors.gray.900', '#1a202c'),
        },

        [`.${scopeClass} *`]: {
          boxSizing: 'border-box',
          borderWidth: '0',
          borderStyle: 'solid',
          borderColor: theme('colors.gray.200', '#e2e8f0'),
          WebkitFontSmoothing: 'antialiased',
          MozOsxFontSmoothing: 'grayscale',
        },

        [`.${scopeClass} *::before, .${scopeClass} *::after`]: {
          boxSizing: 'border-box',
          borderWidth: '0',
          borderStyle: 'solid',
          borderColor: 'currentColor',
        },

        [`.${scopeClass} h1, .${scopeClass} h2, .${scopeClass} h3, .${scopeClass} h4, .${scopeClass} h5, .${scopeClass} h6`]: {
          fontSize: 'inherit',
          fontWeight: 'inherit',
          margin: '0',
        },

        [`.${scopeClass} hr`]: {
          height: '0',
          color: 'inherit',
          borderTopWidth: '1px',
        },

        [`.${scopeClass} button`]: {
          background: 'transparent',
          padding: '0',
        },

        [`.${scopeClass} ol, .${scopeClass} ul`]: {
          listStyle: 'none',
          margin: '0',
          padding: '0',
        },

        [`.${scopeClass} input, .${scopeClass} select, .${scopeClass} textarea, .${scopeClass} button`]: {
          fontFamily: 'inherit',
          fontSize: '100%',
          margin: '0',
          padding: '0',
          lineHeight: 'inherit',
          color: 'inherit',
        },

        [`.${scopeClass} table`]: {
          borderCollapse: 'collapse',
          borderColor: 'inherit',
          textIndent: '0',
        },
      });
    }
  ],
  corePlugins: {
    preflight: false,
  }
}
