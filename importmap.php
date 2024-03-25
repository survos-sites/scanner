<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    '@picocss/pico' => [
        'version' => '2.0.6',
    ],
    '@picocss/pico/css/pico.min.css' => [
        'version' => '2.0.6',
        'type' => 'css',
    ],
    'html5-qrcode' => [
        'version' => '2.3.8',
    ],
    '@ericblade/quagga2' => [
        'version' => '1.8.4',
    ],
];
