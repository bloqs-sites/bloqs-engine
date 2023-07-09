"use strict";

export default (ctx) => ({
    map: ctx.options.map,
    plugins: {
        "postcss-import": {},
        "webp-in-css/plugin": {
            modules: true
        },
        "avif-in-css": {
          modules: true
        },
        "postcss-preset-env": {
            stage: 1,
        },
        autoprefixer: {},
        //cssnano: {
        //    preset: "advanced",
        //},
    },
});
