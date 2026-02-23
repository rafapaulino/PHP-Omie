<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\Config\ConfigSingleton;

$config = ConfigSingleton::getInstance()->getConfig();

print_r($config);