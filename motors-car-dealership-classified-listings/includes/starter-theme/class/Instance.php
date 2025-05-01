<?php
namespace MotorsStarterTheme;

abstract class Instance {
	/**
	 * @var static
	 */
	protected static $instance;

	/**
	 * Initialize the singleton class
	 * @return void
	 */
	public static function init() {
		if ( ! isset( static::$instance[ static::class ] ) ) {
			static::$instance[ static::class ] = new static();
		}
	}

	/**
	 * @return static
	 */
	public static function instance() {
		return static::$instance[ static::class ];
	}
}
