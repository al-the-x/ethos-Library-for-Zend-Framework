<?php
/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Model
 * @category Test_Cases
 */

/**
 * Exclude this file from the code coverage reports when running simple unit tests
 * on test fixtures.
 */
PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'UNIT_TESTS');

/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Model
 * @category Test_Cases
 */
class Test_Model_AbstractTest
extends ethos_Test_TestCase
{
    /**
     * Return a $field name and $expected boolean value returned by has()
     *
     * @return array of arguments for testHas()
     * @see testHas()
     */
    public static function provideFields ( )
    {
        return array(
            /**
             * The default dataset is defined on the method signature: $field is
             * NULL and $expected is FALSE. Requesting nothing for $field should
             * return a negative.
             */
            'default' => array(),
            /**
             * The test fixture for ethos_Model_Abstract, Test_Model_Abstract,
             * has a single field named 'test', which should return a positive.
             */
            'existing field' => array( 'test', true ),
            /**
             * A non-existent field should return a negative.
             */
            'non-existent field' => array( 'foobar', false ),
        ); // END datasets
    } // END provideFields

    /**
     * @dataProvider provideFields
     * @param string $field name to test
     * @param boolean $expected return value of has()
     * @return Test_Model_AbstractTest for method chaining
     */
    public function testHas ( $field = null, $expected = false )
    {
        $this->assertMethodExists($this->fixture, 'has');

        $this->assertType('boolean', $this->fixture->has($field),
            'The return value of has() should be a boolean.'
        );

        $this->assertEquals($expected, $this->fixture->has($field),
            'The has() method should return the expected value for the provided field.'
        );

        return $this;
    } // END testHas


    /**
     * The _require() method is technically a protected method of ethos_Model_Abstract
     * but is exposed via the test fixture, Test_Model_Abstract, so that it can
     * be interrogated. Since "require" is a reserved word in PHP, the method
     * name is surrounded in underscores.
     *
     * @dataProvider provideFields
     * @param string $field name to test
     * @param boolean $expected return value of has(), triggers Exception
     * @return Test_Model_AbstractTest for method chaining
     */
    public function testRequire ( $field = null, $expected = false )
    {
        /**
         * The _require() method should throw an appropriate Exception if the
         * $fixture doesn't has() (bad English, I know) the requested $field.
         * This is mostly used internally to _require() a $field before performing
         * an operation with it.
         */
        if ( !$expected )
        {
            $this->setExpectedException('ethos_Model_Exception');
        }

        $this
            ->assertMethodExists($this->fixture, '_require_')
            ->assertFluentInterface($this->fixture, '_require_', array($field))
        ; // END $this
    } // END testRequire


    public function testGet ( )
    {
        $this->assertMethodExists($this->fixture, 'get');
    } // END testGet
} // END Test_Model_AbstractTest