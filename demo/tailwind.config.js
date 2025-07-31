/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "../resources/views/**/*.blade.php", // Include parent Verdant UI components
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Karla', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}