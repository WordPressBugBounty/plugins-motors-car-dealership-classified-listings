<?php
namespace MotorsVehiclesListing\Libs\Traits;

trait ProtectedActivationHook {
	/**
	 * Store activation hook callbacks
	 *
	 * @var array
	 */
	protected $activation_callbacks = array();

	/**
	 * Store update hook callbacks
	 *
	 * @var array
	 */
	protected $update_callbacks = array();

	/**
	 * Register activation hook with ability to store callback
	 *
	 * @param string $file Plugin file path
	 * @param string $method Method name to call on activation
	 * @return self
	 */
	protected function register_activation_hook( string $file, string $method = 'on_activation' ): self {
		$this->activation_callbacks[ $file ] = function() use ( $method ) {
			$this->$method();
		};

		register_activation_hook(
			$file,
			$this->activation_callbacks[ $file ]
		);

		return $this;
	}

	/**
	 * Register update hook with ability to store callback
	 *
	 * @param string $file Plugin file path
	 * @param string $method Method name to call on update
	 * @return self
	 */
	protected function register_update_hook( string $file, string $method = 'on_activation' ): self {
		$this->update_callbacks[ $file ] = function() use ( $method ) {
			$this->$method();
		};

		add_action(
			'upgrader_process_complete',
			$this->update_callbacks[ $file ],
			10,
			2
		);

		return $this;
	}

	/**
	 * Remove activation hook
	 *
	 * @param string $file Plugin file path
	 * @return self
	 */
	public function remove_activation_hook( string $file ): self {
		if ( isset( $this->activation_callbacks[ $file ] ) ) {
			remove_action(
				'activate_' . plugin_basename( $file ),
				$this->activation_callbacks[ $file ]
			);
			unset( $this->activation_callbacks[ $file ] );
		}

		return $this;
	}
}
