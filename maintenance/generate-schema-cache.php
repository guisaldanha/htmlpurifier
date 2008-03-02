#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
require_once '../library/HTMLPurifier.auto.php';
assertCli();

/**
 * @file
 * Generates a schema cache file from the contents of
 * library/HTMLPurifier/ConfigSchema/schema.ser
 */

$target = '../library/HTMLPurifier/ConfigSchema/schema.ser';
$FS = new FSTools();

$files = $FS->globr('../library/HTMLPurifier/ConfigSchema/schema', '*.txt');

$namespaces = array();
$directives = array();

// Generate string hashes
$parser = new HTMLPurifier_ConfigSchema_StringHashParser();
foreach ($files as $file) {
    $hash = $parser->parseFile($file);
    if (strpos($hash['ID'], '.') === false) {
        $namespaces[] = $hash;
    } else {
        $directives[] = $hash;
    }
}

$adapter = new HTMLPurifier_ConfigSchema_StringHashAdapter();
$schema  = new HTMLPurifier_ConfigSchema();

foreach ($namespaces as $hash) $adapter->adapt($hash, $schema);
foreach ($directives as $hash) $adapter->adapt($hash, $schema);

echo "Saving schema... ";
file_put_contents($target, serialize($schema));
echo "done!\n";