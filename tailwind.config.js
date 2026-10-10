/** @type {import('tailwindcss').Config} */
export default {
  important: false,
  darkMode: ['class', '[data-theme="dark"]'],
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  safelist: [
    {
      pattern: /(bg|text|border)-(red|green|blue|orange|yellow|purple|gray|primary|secondary|white|black)-(50|100|200|300|400|500|600|700|800|900|950)/,
      variants: ['hover', 'focus', 'dark', 'group-hover', 'sm', 'md', 'lg'],
    },
  ],
  corePlugins: {
    preflight: false,
  }
}
