<?php
/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package Test_Model
 * @category Test_Cases
 */

/**
 * Require the parent class: ethos_Test_TestCase
 */
require_once 'ethos/Test/TestCase.php';

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
     * A common assertion for the Model test cases is that an array $fixture (usually
     * the $sharedFixture) has a particular $field and that the expected $value
     * is set to that position.
     *
     * @param array $fixture to be tested
     * @param string $field to be checked in $fixture
     * @param mixed $value expected for $field
     * @param string $message to return on failure (optional)
     * @return Test_Model_AbstractTest for method chaining
     */
    public function assertFixtureHas ( array $fixture, $field, $value, $message = null )
    {
        $this->assertArrayHasKey($field, $fixture,
            'The $fixture should have the $field specified: ' . print_r($field, true)
        );

        $this->assertEquals($value, $fixture[$field],
            'The $fixture should have the expected $value in the $field specified.'
        );

        if ( $this->hasFailed() and !is_null($message) )
        {
            $this->fail($message);
        }

        return $this; // for method chaining
    } // END assertSharedFixtureHas


    /**
     * Overridden from parent to add setup logic for the $sharedFixture and
     * $_fixtureOptions.
     *
     * @see parent::setUp()
     */
    public function setUp ( )
    {
        $this->setSharedFixture(new ArrayObject(array(
            'fields' => new ArrayObject(array(
                'test' => null,
            )), // END fields
            'values' => new ArrayObject(array(
            )), // END values
        )));

        $this->_fixtureOptions = $this->sharedFixture;

        parent::setUp();
    } // END setUp


    /**
     * Provide values for testHas() and testRequire():
     *
     * - string $field to test
     * - boolean $validField if $field exists
     *
     * @return array of datasets for use in test cases
     *
     * @see testHas()
     * @see testRequire()
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
            'extent field' => array( 'test', true ),
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


    /**
     * Provide values for testGet(), testSet(), testValidate(), and testFilter():
     *
     * - string $field to test
     * - boolean $validField if $field exists
     * - mixed $value (raw)
     * - mixed $expected filtered value
     *
     * @return array of datasets for use in test cases
     */
    public static function provideFieldsAndValues ( )
    {
        return array(
            /**
             * The default test data is defined on each method signature.
             */
            'default' => array( null ),
            /**
             * The test fixture has a single $field named "test". Initially, we
             * test a NULL $value.
             */
            'valid field, null value' => array(
                'field' => 'test', true,
            ), // END dataset
            /**
             * Next, we test a non-null $value. There are no filtering rules on
             * the Abstract, so it should come back unchanged.
             */
            'valid field, non-null value' => array(
                'field' => 'test', true,
                'raw value' => 'value',
                'filtered value' => 'value',
            ), // END dataset
            /**
             * An invalid $field should throw an Exception, in most cases, but
             * _validate() and _filter() don't care about the $field.
             */
            'invalid field, null value' => array(
                'field' => 'foobar', false,
            ), // END dataset
            /**
             * In the case of an invalid $field, _validate() should return TRUE
             * and _filter() should leave the $value unchanged.
             */
            'invalid field, non-null value' => array(
                'field' => 'foobar', false,
                'raw value' => 'value',
                'filtered value' => 'value',
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
         * Since we passed the $sharedFixture to the $fixture's constructor as
         * the container for its "fields", any $value set on the $sharedFixture
         * should be available in the $fixture via the get() method.
         */
        if ( $validField )
        {
            $this->sharedFixture['values'][$field] = $expected;
            $this->assertFixtureHas((array) $this->sharedFixture['values'], $field, $expected);
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
     * @param string $field name to test
     * @param boolean $validField if $field exists
     * @param mixed $value to _validate()
     * @param boolean $validValue boolean if $value is valid for $field
     * @return Test_Model_AbstractTest for method chaining
     */
    public function testValidate ( $field = null, $validField = false, $value = null, $expected = true )
    {
        $this->assertMethodExists($this->fixture, '_validate_');

        /**
         * If $value doesn't _validate() for a $field, set() wil throw an Exception,
         * but _validate() doesn't care, by itself. If we $expect an Exception,
         * then, we should expect a NOT $validValue.
         *
         * @var boolean
         */
        $validValue = !( $expected instanceof Exception );

        /**
         * The _validate() method doesn't care whether the $field exists, so it
         * can run validation rules on other $values of interest.
         */
        $this->assertEquals((boolean) $validValue, $this->fixture->_validate_($field, $value),
            'The _validate() method should return ' . print_r($validValue, true)
        );

        return $this; // for method chaining
    } // END testValidate


    /**
     * @dataProvider provideFieldsAndValues
     * @param string $field to test
     * @param boolean $validField if $field exists
     * @param mixed $value to _filter()
     * @param $expected return value of _filter()
     * @return Test_Model_Abstract for method chaining
     */
    public function testFilter ( $field = null, $validField = false, $value = null, $expected = null )
    {
        $this->assertMethodExists($this->fixture, '_filter_');

        /**
         * The _filter() method doesn't care if the $field exists, so it can be
         * used to transform other $values of interest.
         */
        $this->assertEquals($value, $this->fixture->_filter_($field, $value),
            'The Abstract class will will always return the $passed.'
        );

        return $this; // for method chaining
    } // END testFilter


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
         * to operate and throws an Exception if it's not a $validField.
         */
        if ( !$validField )
        {
            $this->setExpectedException('ethos_Model_Exception');
        }

        /**
         * If $value doesn't _validate() against the rules for $field, then set()
         * should throw the $expected Exception.
         */
        if ( $expected instanceof Exception )
        {
            $this->setExpectedException($expected);
        }

        $this->assertFluentInterface($this->fixture, 'set', array( $field, $value ));

        $this->assertFixtureHas((array) $this->sharedFixture['values'], $field, $expected,
            'The $sharedFixture should the $expected value after set().'
        ); // END assertFixtureHas

        return $this; // for method chaining
    } // END testSet


    /**
     * @dataProvider provideFieldsAndValues
     * @param string $field to test
     * @param boolean $validField if $field exists
     * @param mixed $value to set into $field
     * @return Test_Model_AbstractTest for method chaining
     */
    public function testIsset ( $field = null, $validField = false, $value = null )
    {
        $this->assertMethodExists($this->fixture, '__isset');

        $this->assertType('boolean', $this->fixture->__isset($field),
            'The return value() of __isset() should always be a boolean.'
        );

        $this->assertFalse($this->fixture->__isset($field),
            'The $fixture should always have $field initially unset.'
        );

        if ( $validField )
        {
            $this->sharedFixture['values'][$field] = $value;
            $this->assertFixtureHas((array) $this->sharedFixture['values'], $field, $value);
        }

        $this->assertEquals($validField, $this->fixture->__isset($field),
            'The __isset() method should return TRUE after setting the $field.'
        );

        return $this; // for method chaining
    } // END testIsset


    /**
     * @dataProvider provideFieldsAndValues
     * @param string $field to test
     * @param boolean $validField if $field exists
     * @param mixed $value to be __unset()
     * @param mixed $expected filtered $value
     * @return Test_Model_AbstractTest for method chaining
     */
    public function testUnset ( $field = null, $validField = false, $value = null, $expected = null )
    {
        $this->assertMethodExists($this->fixture, '__unset');

        $this->testSet($field, $validField, $value, $expected);

        $this->assertFluentInterface($this->fixture, '__unset', array( $field ));

        $this->assertFalse(isset($this->sharedFixture['values'][$field]),
            'The $sharedFixture should NOT have a value for the specified $field after unset().'
        );

        return $this; // for method chaining
    } // END testUnset


    /**
     * @dataProvider provideFieldsAndValues
     * @param string $field to test
     * @param boolean $validField if $field exists
     * @param mixed $value to be __unset()
     * @param mixed $expected filtered $value
     * @return Test_Model_AbstractTest for method chaining
     */
    public function testUnsetTwice ( $field = null, $validField = false, $value = null, $expected = null )
    {
        return $this
            ->testUnset($field, $validField, $value, $expected)
            ->assertFluentInterface($this->fixture, '__unset', array( $field ))
        ; // END $this
    } // END testUnsetTwice

} // END Test_Model_AbstractTest