<?php
require 'vendor/autoload.php';

use phplcd7segment\messagelcd;

$phpled = new messagelcd();

$phpled->run(isset($argc)?$argc:'',isset($argv)?$argv:'');