<?php

namespace MediaWiki\Extension\MenuEditor\Hook;

use MediaWiki\Extension\MenuEditor\MenuFactory;
use MediaWiki\Hook\BeforeInitializeHook;

class InitializeMenus implements BeforeInitializeHook {
	/** @var MenuFactory */
	private $menuFactory;

	/**
	 * @param MenuFactory $menuFactory
	 */
	public function __construct( MenuFactory $menuFactory ) {
		$this->menuFactory = $menuFactory;
	}

	/**
	 * @inheritDoc
	 */
	public function onBeforeInitialize( $title, $unused, $output, $user, $request, $mediaWiki ) {
		$output->addModules( 'ext.menuEditor.boostrap' );
		$this->menuFactory->initialize();
	}
}
