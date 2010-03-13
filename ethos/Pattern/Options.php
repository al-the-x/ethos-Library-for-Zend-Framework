<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Pattern
 * @category Design_Patterns
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Pattern
 * @category Design_Patterns
 */
class ethos_Pattern_Options
{
    /**
     * Exception message if an invalid option is specified to get()
     */
    const EXCEPTION_OPTION_INVALID = 'The specified option is invalid: ';

    /**
     * Exception message if __set() is attempted.
     */
    const EXCEPTION_OPTION_READONLY = 'This instance provides a read-only interface.';

    /**
     * @var array of $_options for this instance
     */
    protected $_options = array();


    public function __construct ( $options, $defaults = array() )
    {
        $this->_options = array_merge(
            (array) $options,
            (array) $defaults
        );
    } // END __construct


    protected function _findOption ( $option )
    {
        /**
         * @var array of $indexes split from the $segments
         */
        $indexes = array();

        /**
         * @var string $name passed that can be modified
         */
        $segments = $option;

        /**
         * Splitting the $segments on the dot character yields a list
         * of $indexes that can be used to burrow into the $_options
         * array for the desired value. At each iteration, the $segments
         * are trimmed away until only one remains.
         */
        while ( strstr($segments, '.') )
        {
            list($indexes[], $segments ) = explode('.', $segments, 2);
        }

        /**
         * The final segment (or the only one, if no dotted $name was
         * passed) is pushed into the $indexes for use as the final key.
         */
        array_push($indexes, $segments);

        /**
         * @var mixed value of the $option we're after
         */
        $value = $this->_options;

        /**
         * Iterating over the $indexes allows us to drill down into the
         * $_options array recursively.
         */
        while ( !is_null(key($indexes)) )
        {
            /**
             * If we ever encounter an invalid index, we just return NULL
             * and let the client code figure out what to do next. This
             * catches _any_ invalid index.
             */
            if ( !isset($value[current($indexes)]) ) return null;

            /**
             * This is recursive logic: each value of $indexes is another
             * layer we wish to inspect, so we keep around that layer and
             * drill down by index each iteration.
             */
            $value = $value[current($indexes)];

            next($indexes); // don't forget to advance your iterator
        } // END while

        return $value;
    } // END _findOption


    public function has ( $option )
    {
        return ( !is_null($this->_findOption($option)) );
    } // END has


    public function get ( $option )
    {
        $value = $this->_findOption($option);

        if ( is_null($value) )
        {
            throw new ethos_Pattern_Exception(
                self::EXCEPTION_OPTION_INVALID . $option
            );
        }
        
        return $value;
    } // END get

    public function __get ( $option )
    {
        return $this->get($option);
    } // END __get


    public function __set ( $option, $value )
    {
        throw new ethos_Pattern_Exception(
            self::EXCEPTION_OPTION_READONLY
        );
    } // END __set
} // END ethos_Pattern_Options

