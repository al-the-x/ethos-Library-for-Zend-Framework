<?php
/**
 * @license GPL3.0??
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Model
 * @category Models
 */

/**
 * @license GPL3.0??
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Model
 * @category Models
 */
abstract class ethos_Model_Abstract
{
    /**
     * @var ethos_Model_Storage_Interface
     */
    protected $_Storage = null;


    public function __construct ( $options = array() )
    {
    } // END __construct


    public function has ( )
    {
        return true;
    } // END has
} // END ethos_Model_Abstract
