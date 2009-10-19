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
     * The _require() method enforces the existence of a $field by throwing an
     * appropriate Exception if it doesn't exist.
     *
     * @param string $field to test
     * @return ethos_Model_Abstract for method chaining
     * @throws ethos_Model_Exception if $field does not exist
     * @see has()
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
     * The _validate() method checks the $value passed against a set of validation
     * rules specified for $field and returns an appropriate boolean to indicate
     * pass or fail. It generally doesn't care whether the $field exists, so this
     * method can be used for any validity test the Model needs.
     *
     * @param string $field to _validate()
     * @param mixed $value to _validate()
     * @return boolean if $value _validate()s for $field
     */
    protected function _validate ( $field, $value )
    {
        return true;
    } // END _validate


    /**
     * The _filter() method alters the $value passed based on the filtering rules
     * setup in the Model for the $field specified. While _filter() is used by
     * set(), by itself it generally doesn't care whether the $field exists,
     * so this method can be used for any value sanitation that the Model might need.
     *
     * @param string $field name of the _filter()ing rules
     * @param mixed $value to _filter()
     * @return mixed _filter()ed $value
     */
    protected function _filter ( $field, $value )
    {
        return $value;
    } // END _filter


    /**
     * The set() method sets $field to the specified $value and returns the Model
     * object for method chaining or throws an appropriate Exception if $field
     * doesn't exist. It first attempts to _validate() the $value passed based
     * on the rules for $field (by default, none) and _filter()s the $value
     * appropriately.
     *
     * @param string $field name to set()
     * @param mixed $value of $field
     * @return ethos_Model_Abstract for method chaining
     * @throws ethos_Model_Exception if $field doesn't exist
     *
     * @see _require()
     * @see _validate()
     * @see _filter()
     */
    public function set ( $field, $value )
    {
        if ( $this->_require($field)->_validate($field, $value) )
        {
            $this->_fields[$field] = $this->_filter($field, $value);
        }

        return $this;
    } // END set

} // END ethos_Model_Abstract
