#!/usr/bin/env php
<?php
define('DS', DIRECTORY_SEPARATOR);
$time = time();

$spliter1 = '-----------------------------------------------------------------------------------' . "\n";
$spliter2 = '***********************************************************************************' . "\n";

$logname = getenv('LOGNAME');
$prefix = '/Users/Ramis/gs';
$server = 'root@v3p:/opt/etpgpb.v3p.ru/';

if (!empty($prefix)) {

    $command =
        'rsync -av --del ' .
        '--exclude .DS_Store ' .
        '--exclude mail ' .
        $prefix . '/common ' . $server;
    echo $spliter1 . $command . "\n";
    passthru($command);

    $command =
        'rsync -av --del ' .
        '--exclude .DS_Store ' .
        '--exclude runtime ' .
        $prefix . '/console ' . $server;
    echo $spliter1 . $command . "\n";
    passthru($command);

    $command =
        'rsync -av --del ' .
        '--exclude .DS_Store ' .
        $prefix . '/test ' . $server;
    echo $spliter1 . $command . "\n";
    passthru($command);

    $command =
        'rsync -av --del ' .
        '--exclude .DS_Store ' .
        '--exclude runtime ' .
        '--exclude config/main-local.php ' .
        '--exclude config/params-local.php ' .
        '--exclude web/sitemap.xml ' .
        '--exclude web/markt.yml ' .
        '--exclude web/index.php ' .
        '--exclude web/search.yml ' .
        '--exclude migrations ' .
        '--exclude session ' .
        '--exclude web/assets ' .
        $prefix . '/frontend ' . $server;
    echo $spliter1 . $command . "\n";
    passthru($command);

    $command =
        'rsync -av --del ' .
        $prefix . '/composer.json ' . $server;
    echo $spliter1 . $command . "\n";
    passthru($command);

    $command =
        'rsync -av --del ' .
        $prefix . '/composer.phar ' . $server;
    echo $spliter1 . $command . "\n";
    passthru($command);

}
