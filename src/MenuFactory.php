<?php

declare( strict_types = 1 );

namespace MediaWiki\Extension\MenuEditor;

use Wikimedia\ObjectFactory\ObjectFactory;

class MenuFactory {
	/** @var null */
	private $menus = null;

	public function __construct(
		private readonly MenuAttributeRegistry $registry,
		private readonly ObjectFactory $objectFactory,
	) {
	}

	/**
	 * @return void
	 */
	public function initialize() {
		$this->assertLoaded();
	}

	/**
	 * @param string $key
	 * @param IMenu $menu
	 * @return void
	 */
	public function register( string $key, IMenu $menu ) {
		$this->assertLoaded();
		$this->menus[$key] = $menu;
	}

	/**
	 * @return IMenu[]
	 */
	public function getAllMenus(): array {
		$this->assertLoaded();
		return $this->menus;
	}

	private function assertLoaded() {
		if ( $this->menus === null ) {
			$this->menus = [];
			$data = $this->registry->getAllValues();

			foreach ( $data as $key => $spec ) {
				$object = $this->objectFactory->createObject( $spec );
				if ( !( $object instanceof IMenu ) ) {
					continue;
				}
				$this->menus[$key] = $object;
			}
		}
	}
}
