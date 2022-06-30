<?php

use Yivic\Wp\Plugin\Services\ViewService;

$textDomain = 'yivic';

$config     = [
    'version'       => YIVIC_PPVP_VERSION,
    'basePath'      => __DIR__,
    'baseUrl'       => plugins_url( null, __FILE__ ),
    'textDomain'    => $textDomain,
    'services'      => [
        ViewService::class => [

        ],
    ],
];

return $config;
