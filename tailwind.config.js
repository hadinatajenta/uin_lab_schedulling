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
                sans: ['"IBM Plex Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                background: "rgb(var(--color-bg) / <alpha-value>)",
                surface: "rgb(var(--color-surface) / <alpha-value>)",
                'surface-muted': "rgb(var(--color-surface-muted) / <alpha-value>)",
                default: "rgb(var(--color-border) / <alpha-value>)", // border color
                foreground: "rgb(var(--color-text) / <alpha-value>)",
                'foreground-muted': "rgb(var(--color-text-muted) / <alpha-value>)",
                primary: {
                    DEFAULT: "rgb(var(--color-primary) / <alpha-value>)",
                    foreground: "rgb(var(--color-primary-foreground) / <alpha-value>)",
                    soft: "rgb(var(--color-primary-soft) / <alpha-value>)",
                },
                secondary: {
                    DEFAULT: "rgb(var(--color-secondary) / <alpha-value>)",
                    soft: "rgb(var(--color-secondary-soft) / <alpha-value>)",
                },
                danger: {
                    DEFAULT: "rgb(var(--color-danger) / <alpha-value>)",
                    soft: "rgb(var(--color-danger-soft) / <alpha-value>)",
                },
                warning: {
                    DEFAULT: "rgb(var(--color-warning) / <alpha-value>)",
                    soft: "rgb(var(--color-warning-soft) / <alpha-value>)",
                },
                success: {
                    DEFAULT: "rgb(var(--color-success) / <alpha-value>)",
                    soft: "rgb(var(--color-success-soft) / <alpha-value>)",
                },
                tooltip: {
                    DEFAULT: "rgb(var(--color-tooltip-bg) / <alpha-value>)",
                    text: "rgb(var(--color-tooltip-text) / <alpha-value>)",
                },
                nav: {
                    DEFAULT: "rgb(var(--color-nav-text) / <alpha-value>)",
                    hover: "rgb(var(--color-nav-hover-bg) / <alpha-value>)",
                },
                ring: "rgb(var(--color-ring) / <alpha-value>)",
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
