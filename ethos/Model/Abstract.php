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

    /**
     * @var array map of field names to initial (default) values
     */
    protected $_fields = array();

    public function __construct ( $options = array() )
    {
    } // END __construct


    /**
     * Return a boolean indicator of whether $this Model has() the requested $field
     * by checking {@link $_fields} for a matching key.
     *
     * @param string $field to test
     * @return boolean if $field exists in $this Model's $_fields.
     */
    public function has ( $field )
    {
        return array_key_exists($field, $this->_fields);
    } // END has


    /**
     * Stronger than has(), _require() enforces the existence of a $field by
     * throwing an appropriate Exception if has() returns FALSE.
     *
     * @param string $field to test
     * @return ethos_Model_Abstract for method chaining
     * @throws ethos_Model_Exception if $field does not exist
     */
    public function _require ( $field )
    {
        if ( !$this->has($field) )
        {
            throw new ethos_Model_Exception(
                'The required field does not exist in this model: ' . $field
            );
        }

        return $this;
    } // END _require

} // END ethos_Model_Abstract
