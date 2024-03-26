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
    'htmx.org' => [
        'version' => '1.9.11',
    ],
    'stimulus-timeago' => [
        'version' => '4.1.0',
    ],
    'date-fns' => [
        'version' => '2.30.0',
    ],
    '@babel/runtime/helpers/esm/typeof' => [
        'version' => '7.23.8',
    ],
    '@babel/runtime/helpers/esm/createForOfIteratorHelper' => [
        'version' => '7.23.8',
    ],
    '@babel/runtime/helpers/esm/assertThisInitialized' => [
        'version' => '7.23.8',
    ],
    '@babel/runtime/helpers/esm/inherits' => [
        'version' => '7.23.8',
    ],
    '@babel/runtime/helpers/esm/createSuper' => [
        'version' => '7.23.8',
    ],
    '@babel/runtime/helpers/esm/classCallCheck' => [
        'version' => '7.23.8',
    ],
    '@babel/runtime/helpers/esm/createClass' => [
        'version' => '7.23.8',
    ],
    '@babel/runtime/helpers/esm/defineProperty' => [
        'version' => '7.23.8',
    ],
    '@khmyznikov/pwa-install' => [
        'version' => '0.3.5',
    ],
    'lit' => [
        'version' => '3.1.2',
    ],
    'lit/decorators.js' => [
        'version' => '3.1.2',
    ],
    'lit/directives/class-map.js' => [
        'version' => '3.1.2',
    ],
    '@lit/reactive-element' => [
        'version' => '2.0.4',
    ],
    'lit-html' => [
        'version' => '3.1.2',
    ],
    'lit-element/lit-element.js' => [
        'version' => '4.0.4',
    ],
    'lit-html/is-server.js' => [
        'version' => '3.1.2',
    ],
    '@lit/reactive-element/decorators/custom-element.js' => [
        'version' => '2.0.4',
    ],
    '@lit/reactive-element/decorators/property.js' => [
        'version' => '2.0.4',
    ],
    '@lit/reactive-element/decorators/state.js' => [
        'version' => '2.0.4',
    ],
    '@lit/reactive-element/decorators/event-options.js' => [
        'version' => '2.0.4',
    ],
    '@lit/reactive-element/decorators/query.js' => [
        'version' => '2.0.4',
    ],
    '@lit/reactive-element/decorators/query-all.js' => [
        'version' => '2.0.4',
    ],
    '@lit/reactive-element/decorators/query-async.js' => [
        'version' => '2.0.4',
    ],
    '@lit/reactive-element/decorators/query-assigned-elements.js' => [
        'version' => '2.0.4',
    ],
    '@lit/reactive-element/decorators/query-assigned-nodes.js' => [
        'version' => '2.0.4',
    ],
    'lit-html/directives/class-map.js' => [
        'version' => '3.1.2',
    ],
    'bootstrap-icons/font/bootstrap-icons.min.css' => [
        'version' => '1.11.3',
        'type' => 'css',
    ],
];
