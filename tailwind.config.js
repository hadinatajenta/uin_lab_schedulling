import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        function ({ addBase, theme }) {
            addBase({
                ".ckeditor-content ul": {
                    listStyleType: "disc",
                    paddingLeft: theme("spacing.5"),
                    marginBottom: theme("spacing.2"),
                },
                ".ckeditor-content ol": {
                    listStyleType: "decimal",
                    paddingLeft: theme("spacing.5"),
                    marginBottom: theme("spacing.2"),
                },
                ".ckeditor-content li": {
                    marginBottom: theme("spacing.1"),
                },
            });
        },
    ],
};
