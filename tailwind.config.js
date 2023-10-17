/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./public/*.php"],
  theme: {
    extend: {
      colors: {
        'main':'#6334d7',
        'main-2':'#8a67e1',
        'secondary':'#9230ee',
        'third':'#6517e7',
      }
    },
  },
  plugins: [],
}