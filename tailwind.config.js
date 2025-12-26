const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: {
                    bg: "#151823",
                    "eval-1": "#222738",
                    "eval-2": "#2A2F42",
                    "eval-3": "#2C3142",
                    text: "#FFFFFF",
                    "text-secondary": "#A0A0A0",
                    "text-muted": "#6B7280",
                },
                light: {
                    bg: "red-500",
                    "eval-1": "#F7F8FA",
                    "eval-2": "#F0F2F5",
                    "eval-3": "#E8EAED",
                    "eval-4": "#F4F4F4",
                    text: "#000000",
                    "text-secondary": "#4B5563",
                    "text-muted": "#9CA3AF",
                }
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};