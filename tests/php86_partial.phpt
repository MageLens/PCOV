--TEST--
PHP 8.6 partial function application preserves covered and uncovered lines
--SKIPIF--
<?php
if (!extension_loaded("pcov")) die("skip pcov required");
if (PHP_VERSION_ID < 80600) die("skip PHP 8.6 required");
?>
--INI--
pcov.enabled = 1
--FILE--
<?php
function partialTarget($prefix, $value) {
    return $prefix . $value;
}
function unusedTarget($value) {
    return $value;
}
\pcov\start();
$partial = partialTarget('prefix-', ?);
$result = $partial('value');
\pcov\stop();
$lines = \pcov\collect()[__FILE__];
var_dump($result === 'prefix-value');
var_dump($lines[3] === 1);
var_dump($lines[6] === -1);
var_dump($lines[9] === 1 && $lines[10] === 1);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
