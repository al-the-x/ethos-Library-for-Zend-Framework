<?php
/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Model
 * @category Test_Fixtures
 */

/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Model
 * @category Test_Fixtures
 */
class Test_Model_Abstract
extends ethos_Model_Abstract
{
    /**
     * Overridden from parent to process the "fields" and "values" options, which
     * expects a replacement container for the fixture's $_fields and $_values
     * properties, respectively.
     */
    public function __construct ( $options = array() )
    {
        $this->_fields = isset($options['fields']) ?
            $options['fields'] : array();

        $this->_values = isset($options['values']) ?
            $options['values'] : array();

        parent::__construct($options);
    } // END __construct


    /**
     * The _require_() method exposes the protected _require() method of the
     * parent class for testing purposes.
     *
     * @param string $field to _require()
     * @return Test_Model_Abstract for method chaining
     * @throws ethos_Model_Exception if $field does not exist
     * @see parent::_require()
     */
    public function _require_ ( $field )
    {
        return $this->_require($field);
    } // END _require_


    /**
     * The _validate_() method exposes the protected _validate() method of the
     * parent class for testing purposes.
     *
     * @param string $field name to select validation for
     * @param mixed $value to _validate()
     * @return boolean if $value _validate()s for $field
     * @see parent::_validate()
     */
    public function _validate_ ( $field, $value )
    {
        return $this->_validate($field, $value);
    } // END _validate_


    /**
     * The _filter_() method exposes the protected _filter() method of the
     * parent class for testing purposes.
     *
     * @param string $field name to select filtering for
     * @param mixed $value to _filter()
     * @return mixed _filter()ed $value
     * @see parent::_filter()
     */
    public function _filter_ ( $field, $value )
    {
        return $this->_filter($field, $value);
    } // END _filter_

} // END Test_Model_Abstract
