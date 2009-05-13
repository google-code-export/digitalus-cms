<?php
abstract class DSF_Model_Abstract implements IteratorAggregate
{
    protected $_params = array();
    protected $_protectedParams = array();

    public function __get($parameter)
    {
        if (isset($this->_params[$parameter])) {
            return $this->_params[$parameter];
        } else {
            return false;
        }
    }

    public function __set($paremeter, $value)
    {
        if (isset($this->_params[$parameter])) {
            $this->_params[$parameter] = $value;
        }
    }

    public function __call($method, $parameters)
    {
        // For this to be a setSomething or getSomething, the name has to have
        // at least 4 chars as in, setX or getX
        if (strlen($method) < 4) {
            throw new Exception('Method does not exist');
        }
        // Take first 3 chars to determine if this is a get or set
        $prefix = substr($method, 0, 3);

        // Take last chars and convert first char to lower to get required property
        $suffix = substr($method, 3);
        $suffix{0} = strtolower($suffix{0});
        if ('get' == $prefix) {
            if ($this->_hasProperty($suffix) && count($parameters) == 0) {
                return $this->_params[$suffix];
            } else {
                throw new Exception('Getter does not exist');
            }
        }

        if ('set' == $prefix) {
            if ($this->_hasProperty($suffix) && count($parameters) == 1) {
                $this->_params[$suffix] = $parameters[0];
            } else {
                throw new Exception('Setter does not exist');
            }
        }
    }

    public function getIterator()
    {
        return new ArrayObject($this->_params);
    }

    protected function _hasProperty($name)
    {
        return array_key_exists($name, $this->_params);
    }

    protected function _isProtected($key)
    {
        if (in_array($key, $this->_protectedParams)) {
            return true;
        } else {
            return false;
        }
    }

    public function fromArray(array $array)
    {
        foreach ($array as $key => $value) {
            // We need to convert first char of key to upper to get the correct
            // format required in the setter method name
            $property = ucfirst($key);

            $method = 'set' . $property;
            $this->$method($value);
        }
    }

    public function toArray()
    {
        return $this->_params;
    }
}