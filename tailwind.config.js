// tailwind.config.js
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      keyframes: {
        'fade-out-collapse': {
          '0%, 80%': {
            opacity: '1',
            maxHeight: '100px',   // ← allow up to 100px height
            marginTop:  '1rem',
            marginBottom: '1rem',
          },
          '100%': {
            opacity: '0',
            maxHeight: '0',
            marginTop:  '0',
            marginBottom: '0',
          },
        },
      },
      animation: {
        'fade-out-collapse': 'fade-out-collapse 3s ease-out forwards',
      },
    },
  },
  plugins: [],
};
