<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Pattern
 * @category Test_Fixtures
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Pattern
 * @category Test_Fixtures
 */
class Test_Pattern_Options
extends ethos_Pattern_Options
{
    protected $_defaults = array(
        'option' => 'value',
        'nested' => array(
            'option' => 'value',
        ), // END nested
    ); // END $_options


    public function __construct ( $options = array(), $defaults = array() )
    {
        return parent::__construct(array_merge(
            (array) $options,
            $this->_defaults
        ), $defaults);
    } // END __construct


    /**
     * The public _findOption_() method exposes the protected method
     * ethos_Pattern_Options::_findOption() for unit testing.
     */
    public function _findOption_ ( $option )
    {
        return parent::_findOption($option);
    } // END _findOption_

} // END Test_Pattern_Options

