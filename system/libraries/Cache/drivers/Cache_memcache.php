<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * 
 * CI的2.1.3中，并不支持memcache，故copy一个memcached，改成memcache替代实现
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @author yaoying
 * @copyright	Copyright (c) 2006 - 2012 EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 2.0
 * @version $Id: Cache_memcache.php 64 2013-01-27 09:41:13Z yaoying $
 * @filesource	
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Memcached Caching Class 
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		ExpressionEngine Dev Team
 * @link		
 */

class CI_Cache_memcache extends CI_Driver {

	private $_memcached;	// Holds the memcached object

	protected $_memcache_conf 	= array(
					'default' => array(
						'default_host'		=> '127.0.0.1',
						'default_port'		=> 11211,
						'default_persistent' => false,
						'default_weight'	=> 1,
					)
				);

	// ------------------------------------------------------------------------	

	/**
	 * Fetch from cache
	 *
	 * @param 	mixed		unique key id
	 * @return 	mixed		data on success/false on failure
	 */	
	public function get($id)
	{	
		$data = $this->_memcached->get($id);
		
		return (is_array($data)) ? $data[0] : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Save
	 *
	 * @param 	string		unique identifier
	 * @param 	mixed		data being cached
	 * @param 	int			time to live
	 * @return 	boolean 	true on success, false on failure
	 */
	public function save($id, $data, $ttl = 60)
	{
		return $this->_memcached->set($id, array($data, time(), $ttl), 0, $ttl);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Delete from Cache
	 *
	 * @param 	mixed		key to be deleted.
	 * @return 	boolean 	true on success, false on failure
	 */
	public function delete($id)
	{
		return $this->_memcached->delete($id);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Clean the Cache
	 *
	 * @return 	boolean		false on failure/true on success
	 */
	public function clean()
	{
		return $this->_memcached->flush();
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * @param 	null		type not supported in memcached
	 * @return 	mixed 		array on success, false on failure
	 */
	public function cache_info($type = NULL)
	{
		return $this->_memcached->getStats();
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		FALSE on failure, array on success.
	 */
	public function get_metadata($id)
	{
		$stored = $this->_memcached->get($id);

		if (count($stored) !== 3)
		{
			return FALSE;
		}

		list($data, $time, $ttl) = $stored;

		return array(
			'expire'	=> $time + $ttl,
			'mtime'		=> $time,
			'data'		=> $data
		);
	}

	// ------------------------------------------------------------------------

	/**
	 * Setup memcached.
	 */
	private function _setup_memcache()
	{
		// Try to load memcached server info from the config file.
		$CI =& get_instance();
		if ($CI->config->load('memcache', TRUE, TRUE))
		{
			if (is_array($CI->config->config['memcache']))
			{
				$this->_memcache_conf = NULL;

				foreach ($CI->config->config['memcache'] as $name => $conf)
				{
					$this->_memcache_conf[$name] = $conf;
				}				
			}
		}
		
		$this->_memcached = new Memcache();
		
		foreach ($this->_memcache_conf as $name => $cache_server)
		{
			if ( ! array_key_exists('hostname', $cache_server))
			{
				$cache_server['hostname'] = $this->_default_options['default_host'];
			}
	
			if ( ! array_key_exists('port', $cache_server))
			{
				$cache_server['port'] = $this->_default_options['default_port'];
			}
	
			if ( ! array_key_exists('weight', $cache_server))
			{
				$cache_server['weight'] = $this->_default_options['default_weight'];
			}
			
			if ( ! array_key_exists('persistent', $cache_server))
			{
				$cache_server['persistent'] = $this->_default_options['default_persistent'];
			}
			
			$this->_memcached->addServer(
					$cache_server['hostname'], $cache_server['port'], $cache_server['persistent'], $cache_server['weight']
			);
		}
	}

	// ------------------------------------------------------------------------


	/**
	 * Is supported
	 *
	 * Returns FALSE if memcached is not supported on the system.
	 * If it is, we setup the memcached object & return TRUE
	 */
	public function is_supported()
	{
		if ( ! extension_loaded('memcache'))
		{
			log_message('error', 'The Memcache Extension must be loaded to use Memcache Cache.');
			
			return FALSE;
		}
		
		$this->_setup_memcache();
		return TRUE;
	}

	// ------------------------------------------------------------------------

}
// End Class

/* End of file Cache_memcache.php */
/* Location: ./system/libraries/Cache/drivers/Cache_memcache.php */