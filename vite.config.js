import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
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
                {
                    src: "node_modules/virtual-select-plugin",
                    dest: "assets",
                },
            ],
        }),
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/css/app.css",
                "resources/js/app.js",
            ],
            refresh: [...refreshPaths, "app/Http/Livewire/**"],
        }),
    ],
});
