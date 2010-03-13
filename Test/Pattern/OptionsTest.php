<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Pattern
 * @category Test_Cases
 */

require_once 'ethos/Test/TestCase.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'UNIT_TESTS');

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Pattern
 * @category Test_Cases
 */
class Test_Pattern_OptionsTest
extends ethos_Test_TestCase
{
    public static function provide_options ( )
    {
        return array(
            'default' => array( ),
            'non-existent option' => array(
                'not-an-option', false,
            ), // END non-existent option
            'existent option' => array(
                'option', true, 'value',
            ), // END existent option
            'nested option (dotted syntax)' => array(
                'nested.option', true, 'value',
            ), // END nested option
        ); // END dataset
    } // END provide_options


    /**
     * @dataProvider provide_options
     * @param string $option name
     * @param boolean $valid $option provided?
     * @param mixed $value expected if $valid
     * @returns Test_Pattern_OptionsTest for method chaining
     */
    public function test_findOption ( $option = null, $valid = false, $value = null )
    {
        $this->assertMethodExists($this->fixture, '_findOption');
        $this->assertMethodExists($this->fixture, '_findOption_');

        $expected = ( $valid ? $value : null );

        $this->assertEquals($expected, $this->fixture->_findOption_($option),
            'The _findOption_() method should return the expected value of the $option: ' .
            $option
        );

        return $this; // for method chaining
    } // END test_findOption

    /**
     * @dataProvider provide_options
     * @param string $option name
     * @param boolean $expected value of has($option)
     * @return Test_Pattern_OptionsTest for method chaining
     */
    public function test_has ( $option = null, $expected = false )
    {
        $this->assertMethodExists($this->fixture, 'has');

        $this->assertType('boolean', $this->fixture->has($option),
            'The has() method should return a BOOLEAN value.'
        );

        $this->assertEquals($expected, $this->fixture->has($option),
            'The has() method should return TRUE for the specified option: ' . $option
        );

        return $this; // for method chaining
    } // END test_has


    /**
     * @dataProvider provide_options
     * @param string $option name
     * @param boolean $valid $option provided?
     * @param mixed $expected value returned from get()
     */
    public function test_get ( $option = null, $valid = false, $expected = null )
    {
        $this->assertMethodExists($this->fixture, 'get');

        if ( !$valid ) $this->setExpectedException(
            'ethos_Pattern_Exception'
        );

        $this->assertEquals($expected, $this->fixture->get($option),
            'The get() method should return the $expected value for ' .
            'the specified option: ' . $option
        );
    } // END test_get

    
    /**
     * @dataProvider provide_options
     * @param string $option name
     * @param boolean $valid $option provided?
     * @param mixed $expected value returned from get()
     */
    public function test_magic_get ( $option = null, $valid = false, $expected = null )
    {
        if ( is_null($option) ) $this->markTestSkipped(
            'The magic methods cannot be called with NULL.'
        );

        if ( !$valid ) $this->setExpectedException(
            'ethos_Pattern_Exception'
        );

        $this->assertEquals($expected, $this->fixture->$option,
            'The magic __get() method should return the $expected value for ' .
            'the specified option: ' . $option
        );
    } // END test_magic_get


    /**
     * @dataProvider provide_options
     * @param string $option name
     * @param boolean $valid $option provided?
     */
    public function test_magic_set ( $option = null, $valid = false )
    {
        if ( is_null($option) ) $this->markTestSkipped(
            'The magic methods cannot be called with NULL.'
        );

        $this->setExpectedException(
            'ethos_Pattern_Exception'
        );

        $this->fixture->$option = 'new value';
    } // END test_magic_set

} // END Test_Pattern_OptionsTest

