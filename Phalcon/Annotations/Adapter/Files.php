<?php
/**
 * Annotations Files Adapter
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
 * Phalcon\Annotations\Adapter\Files
 *
 * Stores the parsed annotations in files. This adapter is suitable for production
 *
 *<code>
 * $annotations = new \Phalcon\Annotations\Adapter\Files(array(
 *    'annotationsDir' => 'app/cache/annotations/'
 * ));
 *</code>
 * 
 * @see https://github.com/phalcon/cphalcon/blob/master/ext/annotations/adapter/files.c
 */
class Files extends Adapter implements AdapterInterface
{
	/**
	 * Annotations Directory
	 * 
	 * @var string
	 * @access protected
	*/
	protected $_annotationsDir = './';

	/**
	 * \Phalcon\Annotations\Adapter\Files constructor
	 *
	 * @param array|null $options
	 * @throws Exception
	 */
	public function __construct($options = null)
	{
		if(is_array($options) === true) {
			if(isset($options['annotationsDir']) === true) {
				if(is_string($options['annotationsDir']) === false) {
					throw new Exception('Invalid parameter type.');
				}

				$this->_annotationsDir = $options['annotationsDir'];
			}
		}
	}

	/**
	 * Normalize Path
	 * 
	 * @param string $key
	 * @param string $virtual_seperator
	 * @return string
	*/
	private function prepareVirtualPath($key, $virtual_seperator)
	{
		$keylen = strlen($key);
		for($i = 0; $i < $keylen; ++$i)
		{
			$c = $key[$i];
			if($c === '/' || $c === '\\' || $c === ':' || ctype_print($c) === false) {
				$key[$i] = $virtual_seperator;
			}
		}

		return strtolower($key);
	}

	/**
	 * Reads parsed annotations from files
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

		$path = $this->_annotationsDir.$this->prepareVirtualPath($key, '_').'.phpr';

		if(file_exists($path) === true) {
			$data = file_get_contents($path);
			if($data !== false) {
				$unserialized = unserialize($data);
				if(is_object($unserialized) === true ||
					$unserialized instanceof Reflection)
				{
					return $unserialized;
				}
			}
		}

		return null;
	}

	/**
	 * Writes parsed annotations to files
	 *
	 * @param string $key
	 * @param \Phalcon\Annotations\Reflection $data
	 */
	public function write($key, Reflection $data)
	{
		if(is_string($key) === false) {
			throw new Exception('Invalid parameter type.');
		}

		$exp = '';
		$path = $this->_annotationsDir.$this->prepareVirtualPath($key, '_').'.phpr';

		if(file_put_contents($path, serialize($data)) === false)
		{
			throw new Exception('Annotations directory cannot be written');
		}
	}
}