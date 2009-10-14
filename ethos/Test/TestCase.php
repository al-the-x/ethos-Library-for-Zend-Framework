<?php
/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Test
 * @category Unit_Testing
 */

/**
 * The ethos_Test package requires the PHPUnit Unit Testing framework.
 * @link http://phpunit.de/
 */
require_once 'PHPUnit/Framework.php';

/**
 * Exclude the TestCase class from code coverage reports for a standard unit
 * testing suite. When unit testing the TestCase class, this filter will have to
 * be disabled, to allow code coverage.
 */
PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'UNIT_TESTS');

/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Test
 * @category Unit_Testing
 */
class ethos_Test_TestCase
extends PHPUnit_Framework_TestCase
{
    /**
     * @var string cached classname of $fixture
     */
    protected $_fixtureClass = null;


    /**
     * Return a classname that can be instantiated as a Test Fixture by using a
     * conventional inflection. Descendent classes should be named according to
     * the fixture they are testing, i.e. Test_Some_ClassTest contains test cases
     * for the Test_Some_Class fixture.
     */
    public function getFixtureClass ( )
    {
        if ( is_null($this->_fixtureClass) )
        {
            if ( !preg_match('/(?<=[a-z])Test$/', get_class($this)) )
            {
                throw new ethos_Test_Exception(
                    'Descendent classes of ethos_Test_TestCase should have a class ' .
                    'name that corresponds to the fixture they test.'
                ); // END ethos_Test_Exception
            }

            // @todo Trade this preg_replace for a Zend_Filter_Inflector instance.
            $this->_fixtureClass = preg_replace('/(?<=[a-z])Test$/', '', get_class($this));
        } // END is_null

        return $this->_fixtureClass;
    } // END getFixtureClass


    /**
     * A common assertion that starts a single-method test case is that the
     * method even exists. If using Test-Driven Development, this is usually the
     * first assertion made. To save typing, use assertMethodExists().
     *
     * @param Object $fixture to test
     * @param string $method to check for on $fixture
     * @param string $message to report on failure (default if NULL)
     */
    public function assertMethodExists ( $fixture, $method, $message = null )
    {
        $this->assertTrue(method_exists($fixture, $method), ( is_null($message) ?
            sprintf('The $fixture should have a %s() method.', $method) : $message )
        ); // END assertTrue
    } // END assertMethodExists


    /**
     * The setUp() method for ethos_Test_TestCase assumes some sane defaults to
     * abstract fixture generation. If the descendent Test Case has a method
     * named "testConstructor", then we assume that construction of the object
     * is non-trivial and don't attempt to create the fixture. Otherwise, the
     * fixture should be straight-forward to generate, based on the conventional
     * inflection in getFixtureClass().
     *
     * @see getFixtureClass()
     */
    public function setUp ( )
    {
        /**
         * We're assuming that if you create a test case for the constructor,
         * the construction of the $fixture is non-trivial.
         */
        if ( !method_exists($this, 'testConstructor') )
        {
            $fixtureClass = $this->getFixtureClass();
            $this->fixture = new $fixtureClass;
        }
    } // END setUp

} // END ethos_Test_TestCase
