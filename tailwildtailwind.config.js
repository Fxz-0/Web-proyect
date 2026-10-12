module.exports = {
  content: [
    "./src/**/*.{html,js}",
  ],
  theme: {
    extend: {
      fontFamily: {
        display: ['Faculty Glyphic', 'Georgia', 'serif'],
        sans: ['Faculty Glyphic', 'Georgia', 'serif']
      },
      colors: {
        purple: {
          10: '#f4eeff',
          20: '#e5d7ff',
          30: '#d0b3ff',
          40: '#bd90fd',
          50: '#ab70f6',
          60: '#8928df',
          70: '#6e00bc',
          80: '#4d0088',
          90: '#300059',
          100: '#1a042a',
          gray: '#2d2331'
        },
        gold: {
          50: '#fffef1',
          100: '#fdf1c8',
          400: '#ffd100',
          700: '#584300'
        },
        graybone: '#2e2e2e'
      }
    }
  },
  plugins: [],
}
