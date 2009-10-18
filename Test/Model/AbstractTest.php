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
    public function setUp ( )
    {
        $this->setSharedFixture(new ArrayObject(array(
            'test' => null,
        )));

        $this->_fixtureOptions = array(
            'fields' => $this->sharedFixture,
        );

        parent::setUp();
    } // END setUp


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
            'default' => array( null, false ),
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

        return $this; // for method chaining
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

        return $this; // for method chaining
    } // END testRequire


    public static function provideFieldsAndValues ( )
    {
        return array(
            'default' => array( ),
            'valid field, null value' => array(
                'field' => 'test', true,
            ), // END dataset
            'valid field, non-null value' => array(
                'field' => 'test', true,
                'raw value' => 'value',
                'filtered value' => 'value',
            ), // END dataset
            'invalid field, null value' => array(
                'field' => 'foobar', false,
            ), // END dataset
            'invalid field, non-null value' => array(
                'field' => 'foobar', false,
                'raw value' => 'value',
            ), // END dataset
        ); // END datasets
    } // END provideFieldsAndValues

    /**
     * @dataProvider provideFieldsAndValues
     * @param string $field name to test
     * @param boolean $validField if $field is a valid field name
     * @param mixed $expected return value of get()
     * @return Test_Model_AbstractTest for method chaining
     */
    public function testGet ( $field = null, $validField = false, $expected = null )
    {
        $this->assertMethodExists($this->fixture, 'get');

        /**
         * Fields set in the $sharedFixture, which is just a simple ArrayObject,
         * should be reflected in the $fixture, due to the trickery used in the
         * setUp() method.
         */
        if ( $validField )
        {
            $this->sharedFixture[$field] = $expected;
            $this->assertEquals($expected, $this->sharedFixture[$field],
                'The $sharedFixture should have the expected value in the specified $field.'
            );
        }

        /**
         * The get() method _require()'s the existence of the $field requested
         * to operate. If $field isn't a $validField, we expect an Exception.
         */
        if ( !$validField )
        {
            $this->setExpectedException('ethos_Model_Exception');
        }

        $this->assertEquals($expected, $this->fixture->get($field),
            'The get() method should return the expected value for the specified $field.'
        );

        return $this; // for method chaining
    } // END testGet


    /**
     * @dataProvider provideFieldsAndValues
     * @param string $field to test
     * @param boolean $validField if $field exists
     * @param mixed $value to pass to set()
     * @param mixed $expected value returned from get()
     * @return Test_Model_AbstractTest for method chaining
     */
    public function testSet ( $field = null, $validField = false, $value = null, $expected = null )
    {
        $this->assertMethodExists($this->fixture, 'set');

        /**
         * The set() method _require()'s the existence of the $field requested
         * to operate. If $field isn't a $validField, we expect an Exception.
         */
        if ( !$validField )
        {
            $this->setExpectedException('ethos_Model_Exception');
        }

        $this->assertFluentInterface($this->fixture, 'set', array( $field, $value ));

        $this->assertEquals($expected, $this->sharedFixture[$field],
            'After set() the $expected value should reside in the $sharedFixture.'
        );

        return $this; // for method chaining
    } // END testSet

} // END Test_Model_AbstractTest