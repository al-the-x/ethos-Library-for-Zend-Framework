<?php

set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__),
    get_include_path(),
)));

/**
 * The ethos_Test package require the Zend_Loader package from Zend Framework.
 * @link http://framework.zend.com/
 */
require_once 'Zend/Loader/Autoloader.php';

/**
 * Zend_Loader_Autoloader registers underscore-delimited psuedo-namespaces to
 * automatically load classes into memory as they're needed. For unit testing,
 * we expect that test fixtures will be subclassed in the "Test" namespace,
 * while base classes exist in the "ethos" namespace.
 *
 * @see Zend_Loader_Autoloader::registerNamespace()
 */
Zend_Loader_Autoloader::getInstance()
    ->registerNamespace('Test')
    ->registerNamespace('ethos')
; // END Zend_Loader_Autoloader

require_once 'PHPUnit/Framework.php';

PHPUnit_Util_Filter::addDirectoryToFilter('Zend', '.php');
PHPUnit_Util_Filter::addDirectoryToFilter('Test', 'Test.php');
