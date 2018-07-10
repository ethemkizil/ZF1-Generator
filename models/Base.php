<?php

class Application_Model_Base extends stdClass
{

    const INVALID_PROPERTY = "Invalid property";
    protected $_mapper = null;
    protected $_mapperClassName = "Mapper";
    
    // properties camelCased
    protected $_properties = array();

    public function __construct (array $options = null)
    {
        if (is_array($options)) {
            
            $this->setOptions($options);
        }
    }

    /**
     *
     * Set property
     *
     * @param $name string            
     *
     * @return itself
     *
     */
    public function __set ($name, $value)
    {
        $method = 'set' . ucfirst($name);
        
        // if trying to set mapper, then throw exception
        if ('mapper' == $name) {
            throw new Exception(self::INVALID_PROPERTY);
        }
        
        // if setter method
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }         // else if array key with that name exists in object properties
        
        else 
            if (array_key_exists($name, $this->_properties)) {
                $this->_properties[$name] = $value;
                return $this;
            } else { // else throw exception
                throw new Exception(self::INVALID_PROPERTY);
            }
    }

    /**
     * Get property
     *
     * @param $name string            
     *
     * @return mixed
     *
     */
    public function __get ($name)
    {
        $method = 'get' . ucfirst($name);
        $value = null;
        
        // if getter method exists
        if (method_exists($this, $method)) {
            $value = $this->$method();
        }         // else if array key with that name exists in object properties
        
        else 
            if (array_key_exists($name, $this->_properties)) {
                $value = $this->_properties[$name];
            } else { // else throw exception
                throw new Exception(self::INVALID_PROPERTY);
            }
        
        return $value;
    }

    public function setOptions (array $options)
    {
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->_properties) === true) {
                $this->_properties[$key] = $value;
            }
        }
        
        return $this;
    }

    /**
     * Add new property to object
     *
     * @param $name property
     *            name
     *            
     * @return itself
     *
     */
    public function addProperty ($name)
    {
        if (array_key_exists($name, $this->_properties) === false) {
            $this->_properties[$name] = null;
        }
        
        return $this;
    }

    public function __isset ($name)
    {
        return isset($this->_properties[$name]);
    }

    public function __unset ($name)
    {
        if (isset($this->_properties[$name])) {
            unset($this->_properties[$name]);
        }
    }

    /**
     * Magic function calls
     *
     *
     *
     * @param $method string            
     *
     * @param $args array            
     *
     */
    public function __call ($method, array $args)
    {
        
        // calls to findAllByProperty($objectClassName, $value);
        if (strlen($method) > 9 && strpos($method, "findAllBy") === 0 && count($args) > 0) {
            return $this->_getMapper()->$method($this, $args[0]);
        }
    }

    /**
     *
     *
     * Set mapper object to model
     *
     *
     *
     * @return itself
     *
     */
    protected function _setMapper ($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    /**
     *
     *
     * Get model mapper
     *
     *
     *
     * @return mapper
     *
     */
    protected function _getMapper ()
    {
        if (null === $this->_mapper) {
            // if mapper class was set explicitly
            if (! is_null($this->_mapperClassName)) {
                $this->_setMapper(new $this->_mapperClassName());
            } else {
                return null;
            }
        }
        
        return $this->_mapper;
    }

    public function toArray ()
    {
        // return properties as array.
        return $this->_properties;
    }

    /**
     *
     *
     * Clears (sets to null) all properties of the object
     *
     *
     *
     * @return void
     *
     */
    public function clear ()
    {
        foreach ($this->_properties as $key => $value) {
            $this->_properties[$key] = null;
        }
    }
}