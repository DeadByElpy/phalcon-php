<?php
/**
 * Annotations Memory Adapter
 *
 * @author Andres Gutierrez <andres@phalconphp.com>
 * @author Eduar Carvajal <eduar@phalconphp.com>
 * @author Wenzel Pünter <wenzel@phelix.me>
 * @version 0.1
 * @package Phalcon
*/
namespace Phalcon\Annotations\Adapter;

use \Phalcon\Annotations\AdapterInterface,
	\Phalcon\Annotations\Adapter,
	\Phalcon\Annotations\Reflection,
	\Phalcon\Annotations\Exception;

/**
 * Phalcon\Annotations\Adapter\Memory
 *
 * Stores the parsed annotations in memory. This adapter is the suitable development/testing
 * 
 * @see https://github.com/phalcon/cphalcon/blob/master/ext/annotations/adapter/memory.c
 */
class Memory extends Adapter implements AdapterInterface
{
	/**
	 * Annotations
	 * 
	 * @var array|null
	 * @access protected
	*/
	protected $_data = null;

	/**
	 * Reads parsed annotations from memory
	 *
	 * @param string $key
	 * @return \Phalcon\Annotations\Reflection|null
	 * @throws Exception
	 */
	public function read($key)
	{
		if(is_string($key) === false) {
			throw new Exception('Invalid parameter type.');
		}

		$lowercased_key = strtolower($key);

		if(isset($this->_data[$lowercased_key]) === true) {
			return $this->_data[$lowercased_key];
		} else {
			return null;
		}
	}

	/**
	 * Writes parsed annotations to memory
	 *
	 * @param string $key
	 * @param \Phalcon\Annotations\Reflection $data
	 * @throws Exception
	 */
	public function write($key, Reflection $data)
	{
		if(is_string($key) === false) {
			throw new Exception('Invalid parameter type.');
		}

		$this->_data[strtolower($key)] = $data;
	}
}