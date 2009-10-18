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
    public function __construct ( $options = array() )
    {
        if ( isset($options['fields']) )
        {
            $this->_fields = $options['fields'];
            unset($options['fields']);
        }

        parent::__construct($options);
    } // END __construct


    public function _require_ ( $field )
    {
        return $this->_require($field);
    } // END _require_

} // END Test_Model_Abstract