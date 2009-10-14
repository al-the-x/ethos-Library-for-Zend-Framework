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
    public function testHas ( )
    {
        $this->assertMethodExists($this->fixture, 'has');

        $this->assertType('boolean', $this->fixture->has());
    } // END testHas

    public function testGet ( )
    {
        $this->assertMethodExists($this->fixture, 'get');
    } // END testGet
} // END Test_Model_AbstractTest