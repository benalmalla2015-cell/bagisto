/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        container: {
            center: true,

            screens: {
                "2xl": "1920px",
            },

            padding: {
                DEFAULT: "16px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1920px",
        },

        extend: {
            colors: {
                enab: {
                    bg: '#0A0A0A',
                    surface: '#111111',
                    surface2: '#151515',
                    text: '#F5F5F5',
                    muted: '#A3A3A3',
                    burgundy: '#5E1B3C',
                    burgundyDark: '#4A0E2E',
                    emerald: '#1B4D3E',
                    emeraldDark: '#0F3B2F',
                },
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156',
            },

            fontFamily: {
                tajawal: ['Tajawal', 'Poppins', 'Inter', 'sans-serif'],
                inter: ['Inter'],
                icon: ['icomoon']
            }
        },
    },
    
    darkMode: 'class',

    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        }
    ]
};
