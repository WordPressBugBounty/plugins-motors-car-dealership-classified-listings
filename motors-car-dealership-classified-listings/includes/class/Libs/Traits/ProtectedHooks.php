<?php
namespace MotorsVehiclesListing\Libs\Traits;

trait ProtectedHooks {
	/**
	 * Store hook callbacks to allow their removal
	 *
	 * @var array
	 */
	protected $hook_callbacks = array();

	/**
	 * Add action hook with ability to use method name as hook name
	 *
	 * @param string $hook          Hook name
	 * @param int    $priority      Priority
	 * @param int    $accepted_args Number of arguments
	 * @return self
	 */
	protected function add_action( string $hook, int $priority = 10, int $accepted_args = 3 ): self {
		// Create callback and store it in the class property
		$this->hook_callbacks[ $hook ][ $priority ] = function( ...$args ) use ( $hook ) {
			$method = strtr( $hook, array( '-' => '_' ) );
			$this->$method( ...$args );
		};

		add_action(
			$hook,
			$this->hook_callbacks[ $hook ][ $priority ],
			$priority,
			$accepted_args
		);

		return $this;
	}

	/**
	 * Remove action hook added with add_action method
	 *
	 * @param string $hook     Hook name
	 * @param int    $priority Priority
	 * @return self
	 */
	public function remove_action( string $hook, int $priority = 10 ): self {
		if ( isset( $this->hook_callbacks[ $hook ][ $priority ] ) ) {
			remove_action(
				$hook,
				$this->hook_callbacks[ $hook ][ $priority ],
				$priority
			);
			unset( $this->hook_callbacks[ $hook ][ $priority ] );
		}

		return $this;
	}

	/**
	 * Add filter hook with ability to use method name as hook name
	 *
	 * @param string $hook                Hook name
	 * @param int    $priority            Priority
	 * @param int    $accepted_args       Number of arguments
	 * @param bool   $unset_default_value Unset Default Value from $args
	 * @return self
	 */
	protected function add_filter( string $hook, int $priority = 10, int $accepted_args = 3, bool $unset_default_value = false ): self {
		// Create callback and store it in the class property
		$this->hook_callbacks[ $hook ][ $priority ] = function( ...$args ) use ( $hook, $unset_default_value ) {
			if ( $unset_default_value ) {
				unset( $args[ array_key_first( $args ) ] );
			}

			$method = strtr( $hook, array( '-' => '_' ) );
			return $this->$method( ...$args );
		};

		add_filter(
			$hook,
			$this->hook_callbacks[ $hook ][ $priority ],
			$priority,
			$accepted_args
		);

		return $this;
	}

	/**
	 * Remove filter hook added with add_filter method
	 *
	 * @param string $hook     Hook name
	 * @param int    $priority Priority
	 * @return self
	 */
	public function remove_filter( string $hook, int $priority = 10 ): self {
		if ( isset( $this->hook_callbacks[ $hook ][ $priority ] ) ) {
			remove_filter(
				$hook,
				$this->hook_callbacks[ $hook ][ $priority ],
				$priority
			);
			unset( $this->hook_callbacks[ $hook ][ $priority ] );
		}

		return $this;
	}
}
