<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Model
 * @category Models
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package ethos_Model
 * @category Models
 */
abstract class ethos_Model_Abstract
{
    /**
     * @var ethos_Model_Storage_Interface to use as $_Storage adapter
     */
    protected $_Storage = null;


    /**
     * @var array of options for this instance
     */
    protected $_defaults = array(
    ); // END $_defaults

    /**
     * @var array map of field names to ethos_Model_Field instances
     */
    protected $_fields = array(
    ); // END $_fields

    /**
     * @var array map of field names to (altered) values
     */
    protected $_values = array();


    public function __construct ( $options = array() )
    {
        $this->_setOptions((array) $options);
    } // END __construct


    protected function _setOptions ( array $options )
    {
        if ( isset($options['storage']) )
        {
            if ( $options['storage'] instanceof ethos_Model_Storage_Interface )
            {
                $this->_Storage = $options['storage'];

                $options['storage'] = get_class($options['storage']);
            }
        } // END $options[storage]

        $this->_Options = new ethos_Pattern_Options(
            $options, $this->_defaults
        );

        return $this; // for method chaining
    } // END _setOptions


    /**
     * The getOptions() method accepts an option $name in dotted syntax
     * and returns the value for that key. It proxies to the $_Options
     * object, which is an instance of ethos_Pattern_Options.
     *
     * @param string $name of the option to return
     * @throws ethos_Pattern_Exception if $name is invalid
     * @return mixed value of the option
     * @see ethos_Pattern_Options::get()
     */
    public function getOption ( $name )
    {
        return $this->_Options->get($name);
    } // END getOption


    /**
     * Return a boolean indicator of whether $this Model has() the requested $field
     * by checking {@link $_fields} for a matching key.
     *
     * @param string $field to test
     * @return boolean if $field exists in $this Model's $_fields.
     */
    public function has ( $field )
    {
        return array_key_exists($field, $this->_fields);
    } // END has


    /**
     * The _require() method enforces the existence of a $field by throwing an
     * appropriate Exception if it doesn't exist.
     *
     * @param string $field to test
     * @return ethos_Model_Abstract for method chaining
     * @throws ethos_Model_Exception if $field does not exist
     * @see has()
     */
    public function _require ( $field )
    {
        if ( !$this->has($field) )
        {
            throw new ethos_Model_Exception(
                'The required field does not exist in this model: ' . $field
            );
        }

        return $this; // for method chaining
    } // END _require


    /**
     * The get() method returns the value of the $field requested or throws an
     * appropriate Exception if $field doesn't exist.
     *
     * @param string $field name to get()
     * @return mixed value of $field
     * @throws ethos_Model_Exception if $field doesn't exist
     * @see _require()
     */
    public function get ( $field )
    {
        if ( $this->_require($field)->__isset($field) )
        {
            /**
             * If the $field has been set(), then the interesting value will be
             * in the $_values container.
             */
            return $this->_values[$field];
        }

        /**
         * Otherwise, return the default value from the $_fields container.
         */
        return $this->_fields[$field];
    } // END get


    /**
     * The _validate() method checks the $value passed against a set of validation
     * rules specified for $field and returns an appropriate boolean to indicate
     * pass or fail. It generally doesn't care whether the $field exists, so this
     * method can be used for any validity test the Model needs.
     *
     * @param string $field to _validate()
     * @param mixed $value to _validate()
     * @return boolean if $value _validate()s for $field
     */
    protected function _validate ( $field, $value )
    {
        return true;
    } // END _validate


    /**
     * The _filter() method alters the $value passed based on the filtering rules
     * setup in the Model for the $field specified. While _filter() is used by
     * set(), by itself it generally doesn't care whether the $field exists,
     * so this method can be used for any value sanitation that the Model might need.
     *
     * @param string $field name of the _filter()ing rules
     * @param mixed $value to _filter()
     * @return mixed _filter()ed $value
     */
    protected function _filter ( $field, $value )
    {
        return $value;
    } // END _filter


    /**
     * The set() method sets $field to the specified $value and returns the Model
     * object for method chaining or throws an appropriate Exception if $field
     * doesn't exist. It first attempts to _validate() the $value passed based
     * on the rules for $field (by default, none) and _filter()s the $value
     * appropriately.
     *
     * @param string $field name to set()
     * @param mixed $value of $field
     * @return ethos_Model_Abstract for method chaining
     * @throws ethos_Model_Exception if $field doesn't exist
     *
     * @see _require()
     * @see _validate()
     * @see _filter()
     */
    public function set ( $field, $value )
    {
        if ( $this->_require($field)->_validate($field, $value) )
        {
            $this->_values[$field] = $this->_filter($field, $value);
        }

        return $this; // for method chaining
    } // END set


    /**
     * The __isset() (magic) method returns a boolean TRUE / FALSE if the requested
     * $field exists via has() AND has been set() by looking at the $_values
     * property of the Model.
     *
     * @param string $field to test
     * @return boolean if $field exists and has been previously set()
     */
    public function __isset ( $field )
    {
        return ( $this->has($field) and isset($this->_values[$field]) );
    } // END __isset


    /**
     * The __unset() (magic) method will destroy a the value of the $field
     * specified, removing it from the $_values container.
     *
     * @param string $field to __unset()
     * @return ethos_Model_Abstract for method chaining
     */
    public function __unset ( $field )
    {
        /**
         * If we attempt to unset() an array key that doesn't exist, then PHP
         * will complain with a WARNING. Rather than suppressing errors with the
         * shaddap (@), which is poor practice, we check that the $field __isset()
         * first.
         */
        if ( $this->__isset($field) )
        {
            unset($this->_values[$field]);
        }

        return $this; // for method chaining
    } // END __unset


    /**
     * The _getStorage method returns the $_Storage adapter, after ensuring that
     * it has been correctly instantiated. Please don't access the $_Storage
     * property directly; use _getStorage() instead.
     *
     * @return ethos_Model_Storage_Interface
     */
    public function _getStorage ( )
    {
        /**
         * @var string "storage" option, which contains the classname of the $_Storage adapter to use
         */
        $storageClass = $this->getOption('storage');

        if ( !class_exists($storageClass) )
        {
            throw new ethos_Model_Storage_Interface(
                'The storage class supplied does not exist: ' . $storageClass
            );
        }

        /**
         * Instantiate the $_Storage adapter if it doesn't already exist (or
         * if it's an object that it shouldn't be.
         */
        if ( !($this->_Storage instanceof $storageClass) )
        {
            $this->Storage = new $storageClass;
        }

        return $this->_Storage;
    } // END _getStorage


    /**
     * The save() method passes the changed $_values to the $_Storage adapter
     * so that it can persist the data appropriately.
     */
    public function save ( )
    {
        $this->_getStorage()->save((array) $this->_values);

        return $this;
    } // END save


    public function load ( )
    {
        return $this;
    } // END load

} // END ethos_Model_Abstract
