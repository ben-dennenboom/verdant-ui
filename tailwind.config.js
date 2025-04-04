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
          DEFAULT: '#E9500E', // cc_orange
          50: '#FEF2EC',
          100: '#FDE5D9',
          200: '#FBCBB3',
          300: '#F8B18D',
          400: '#F69767',
          500: '#F47D41',
          600: '#F1631B',
          700: '#E9500E', // Original cc_orange
          800: '#B83F0B',
          900: '#872E08',
        },
        secondary: {
          DEFAULT: '#2d3441', // cc_gray
          50: '#F7F8F9',
          100: '#EFF1F3',
          200: '#DFE2E7',
          300: '#CFD4DB',
          400: '#BFC6CF',
          500: '#AFB8C3',
          600: '#8E9CAD',
          700: '#6D7F97',
          800: '#4C6381',
          900: '#2d3441', // Original cc_gray
        },
      }
    }
  },
  plugins: [
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
    },
  ],
  corePlugins: {
    preflight: false,
  }
}
