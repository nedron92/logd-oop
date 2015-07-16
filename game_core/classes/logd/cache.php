<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Cache.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    25.03.2015
 * @package game_core
 * @subpackage LOGD
 *
 * @description
 * This class implements a wrapper for the module phpfastcache.
 *
 */

class LOGD_Cache {

	/**
	 * @const string    a prefix for the cache-keys
	 */
	const CACHE_PREFIX = 'logd_';

	/**
	 * @var null|BasePhpFastCache   the cache object
	 */
	private $o_cache = null;

	/**
	 * @var null|LOGD_Cache  hold the actual instance of the class.
	 */
	private static $instance = null;

	/**
	 * Get singleton instance of the class or create new one, if no exists.
	 *
	 * @return LOGD_Cache   the instance
	 */
	public static function getInstance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor of the class.
	 * setting the cache-object to its default value
	 *
	 */
	private function __construct()
	{
		require_once(CORE_PATH.'classes'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'phpfastcache'.EXT);
		$this->o_cache = phpFastCache();
	}

	/**
	 * Defined the clone function as private, because of singleton pattern
	 */
	private function __clone()
	{}

	/**
	 * This method implements a little wrapper for the
	 * phpFastCache->set function.
	 *
	 * @param string $s_key     the keyword of the cache-entry
	 * @param string $s_value   the value of the cache-entry
	 * @param int    $i_time    how long the entry will be hold (in seconds)
	 */
	public function set($s_key,$s_value="",$i_time=0)
	{ $this->o_cache->set(self::CACHE_PREFIX.$s_key,$s_value,$i_time); }

	/**
	 * This method implements a little wrapper for the
	 * phpFastCache->get function.
	 *
	 * @param string $s_key     the keyword of the cache-entry
	 *
	 * @return mixed|null       the value of the cache-entry
	 */
	public function get($s_key)
	{
		$s_value = $this->o_cache->get(self::CACHE_PREFIX.$s_key);
		return $s_value;
	}

	/**
	 * This method implements a little wrapper for the
	 * phpFastCache->clean function.
	 *
	 */
	public function clear()
	{$this->o_cache->clean(); }

	/**
	 * This method implements a little wrapper for the
	 * phpFastCache->delete function.
	 *
	 * @param string $s_keyword     the keyword of the cache-entry
	 *
	 */
	public function delete_entry($s_keyword)
	{$this->o_cache->delete(self::CACHE_PREFIX.$s_keyword); }

	/**
	 * This method implements a little wrapper for the
	 * phpFastCache->isExisting function.
	 *
	 * @param string $s_key     the keyword of the cache-entry
	 *
	 * @return bool             TRUE if key exists, FALSE if not
	 */
	public function exists($s_key)
	{ return $this->o_cache->isExisting(self::CACHE_PREFIX.$s_key); }
} 