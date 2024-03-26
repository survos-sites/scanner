import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import '@picocss/pico';
import '@picocss/pico/css/pico.min.css';
import 'htmx.org';
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

import '@khmyznikov/pwa-install';
