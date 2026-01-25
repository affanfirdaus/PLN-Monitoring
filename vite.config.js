import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                // HAPUS INI BIAR NGGAK KEBUILD:
                // "resources/css/filament/admin-pelayanan/theme.css"
            ],
            refresh: true,
        }),
    ],
});
