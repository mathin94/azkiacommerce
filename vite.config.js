import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { viteStaticCopy } from "vite-plugin-static-copy";

export default defineConfig({
    plugins: [
        viteStaticCopy({
            targets: [
                {
                    src: "resources/assets/images",
                    dest: "assets",
                },
                {
                    src: "resources/assets/js",
                    dest: "assets",
                },
            ],
        }),
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
