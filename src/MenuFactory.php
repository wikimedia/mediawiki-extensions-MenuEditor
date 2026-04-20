<?php

declare( strict_types = 1 );

namespace MediaWiki\Extension\MenuEditor;

use Wikimedia\ObjectFactory\ObjectFactory;

class MenuFactory {
	/** @var MenuAttributeRegistry */
	private $registry;
	/** @var ObjectFactory */
	private $objectFactory;
	/** @var null */
	private $menus = null;

	/**
	 * @param MenuAttributeRegistry $registry
	 * @param ObjectFactory $objectFactory
	 */
	public function __construct( MenuAttributeRegistry $registry, ObjectFactory $objectFactory ) {
		$this->registry = $registry;
		$this->objectFactory = $objectFactory;
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
