const mix = require("laravel-mix");
const tailwindcss = require("tailwindcss");

mix
  .ts("resources/assets/js/app.tsx", "public/assets/js")
  .react()
  .postCss("resources/assets/css/app.css", "public/assets/css", [
    // tailwindcss("./tailwind.config.js"),
    require("tailwindcss")
  ]);
  
// .sass("resources/assets/sass/app.scss", "public/assets/css");
