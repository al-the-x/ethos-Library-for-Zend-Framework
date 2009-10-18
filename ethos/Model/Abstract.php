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


    /**
     * The get() method returns the value of the $field requested or throws an
     * appropriate Exception if $field doesn't exist.
     *
     * @param string $field name to get()
     * @return mixed value of $field
     * @throws ethos_Model_Exception if $field doesn't exist
     * @see _require()
     */
    public function get ( $field )
    {
        return $this->_require($field)->_fields[$field];
    } // END get


    /**
     * The set() method sets $field to the specified $value and returns the Model
     * object for method chaining or throws an appropriate Exception if $field
     * doesn't exist.
     *
     * @param string $field name to set()
     * @param mixed $value of $field
     * @return ethos_Model_Abstract for method chaining
     * @throws ethos_Model_Exception if $field doesn't exist
     * @see _require()
     */
    public function set ( $field, $value )
    {
        $this->_require($field)->_fields[$field] = $value;

        return $this;
    } // END set


    /**
     * The _validate() method checks the $value passed against a set of validation
     * rules specified for $field and returns an appropriate boolean to indicate
     * pass or fail. If the $field doesn't exist, it throws an appropriate Exception.
     *
     * @param string $field to _validate()
     * @param mixed $value to _validate()
     * @return boolean if $value _validate()s for $field
     * @throws ethos_Model_Exception if $field doesn't exist.
     */
    protected function _validate ( $field, $value )
    {
        return true;
    } // END _validate


    /**
     * The _filter() method alters the $value passed based on the filtering rules
     * setup in the Model for the $field specified. It returns the _filter()ed
     * $value or throws an appropriate Exception if the $field requested doesn't
     * exist.
     *
     * @param string $field name of the _filter()ing rules
     * @param mixed $value to _filter()
     * @return mixed _filter()ed $value
     * @throws ethos_Model_Exception if $field doesn't exist
     */
    protected function _filter ( $field, $value )
    {
        return $value;
    } // END _filter

} // END ethos_Model_Abstract
