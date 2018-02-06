<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'AuditLog' => $baseDir . '/plugins/AuditLog/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'CakePdf' => $baseDir . '/vendor/friendsofcake/cakepdf/',
        'Cake/ElasticSearch' => $baseDir . '/vendor/cakephp/elastic-search/',
        'Crud' => $baseDir . '/vendor/friendsofcake/crud/',
        'CsvView' => $baseDir . '/vendor/friendsofcake/cakephp-csvview/',
        'DatabaseLog' => $baseDir . '/vendor/dereuromark/cakephp-databaselog/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'WyriHaximus/TwigView' => $baseDir . '/vendor/wyrihaximus/twig-view/',
        'audit-stash' => $baseDir . '/plugins/audit-stash/'
    ]
];