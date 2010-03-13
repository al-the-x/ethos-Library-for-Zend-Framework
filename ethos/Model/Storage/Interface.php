<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Model
 * @subpackage Storage
 * @category Interfaces
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Model
 * @subpackage Storage
 * @category Interfaces
 */
interface ethos_Model_Storage_Interface
{
    public function save ( array $data );

    public function load ( array $data );

} // END ethos_Model_Storage_Interface