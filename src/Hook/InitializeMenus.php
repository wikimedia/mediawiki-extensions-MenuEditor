<?php

namespace MediaWiki\Extension\MenuEditor\Hook;

use MediaWiki\Extension\MenuEditor\MenuFactory;
use MediaWiki\Hook\BeforeInitializeHook;

class InitializeMenus implements BeforeInitializeHook {
	public function __construct(
		private readonly MenuFactory $menuFactory,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function onBeforeInitialize( $title, $unused, $output, $user, $request, $mediaWiki ) {
		$output->addModules( 'ext.menuEditor.boostrap' );
		$this->menuFactory->initialize();
	}
}
