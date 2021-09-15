<?php

use Doctum\Doctum;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('public')
    ->exclude('vendor')
    ->exclude('storage')
    ->exclude('doctum.config.php')
    ->in('.');

return new Doctum($iterator, [
    'language'  => 'en',
    'title'     => 'Affiliates',
    'build_dir' => __DIR__ . '/public/docs',
    'cache_dir' => __DIR__ . '/.cache',
]);