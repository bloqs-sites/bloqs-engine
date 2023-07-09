import { nodeResolve } from "@rollup/plugin-node-resolve";
import { default as commonjs } from "@rollup/plugin-commonjs";
import { terser } from "rollup-plugin-terser";
import { default as beep } from "@rollup/plugin-beep"
import { default as strip } from "@rollup/plugin-strip";
//import { default as swc } from "@rollup/plugin-swc"
import { default as typescript } from "@rollup/plugin-typescript"
import { wasm } from "@rollup/plugin-wasm"
import { default as json } from "@rollup/plugin-json"
import { defineConfig } from "rollup";

// `npm run build` -> `production` is true
// `npm run dev` -> `production` is false
const production = !process.env["ROLLUP_WATCH"];

const cnf = defineConfig({
    input: "./src/scripts/app.ts",
    output: [
        {
            name: "BloqsApp",
            file: "public/assets/js/bundle.js",
            format: "iife", // immediately-invoked function expression — suitable for <script> tags
            sourcemap: true
        }
    ],
    plugins: [
        beep(),
        nodeResolve(), // tells Rollup how to find date-fns in node_modules
        commonjs(), // converts date-fns to ES modules
        //production && strip(),
        //swc(),
        typescript(),
        wasm(),
        json(),
        production && terser(), // minify, but only in production
    ]
});

export default cnf;
