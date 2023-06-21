import colors from "tailwindcss/colors";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import defaultTheme from "tailwindcss/defaultTheme";

export default {
    content: ["./resources/**/*.blade.php", "./vendor/filament/**/*.blade.php"],
    darkMode: "class",
    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                success: colors.green,
                warning: colors.yellow,
            },
        },
    },
    plugins: [forms, typography, require("tailwindcss-tables")()],
};
