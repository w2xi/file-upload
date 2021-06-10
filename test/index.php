<?php 

$file = new SplFileObject(__FILE__);

var_dump('realpath: ' . $file->getRealPath());
var_dump('pathname: ' . $file->getPathname());
var_dump('extension: ' . $file->getExtension());

