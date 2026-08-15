<?php

namespace MediaWiki\Extension\MenuEditor\Hook;

use BlueSpice\Discovery\Hook\BlueSpiceDiscoveryTemplateDataProviderAfterInit;
use BlueSpice\Discovery\ITemplateDataProvider;
use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\MenuEditor\MenuFactory;

class DiscoverySkin implements BlueSpiceDiscoveryTemplateDataProviderAfterInit {

	public function __construct(
		private readonly MenuFactory $menuFactory,
	) {
	}

	/**
	 * @param ITemplateDataProvider $registry
	 */
	public function onBlueSpiceDiscoveryTemplateDataProviderAfterInit( $registry ): void {
		$context = RequestContext::getMain();
		$title = $context->getTitle();
		$menus = $this->menuFactory->getAllMenus();
		$applied = false;

		foreach ( $menus as $key => $menu ) {
			if ( $menu->appliesToTitle( $title ) ) {
				$applied = true;
			}
		}
		if ( !$applied ) {
			return;
		}

		$registry->register( 'panel/edit', 'ca-menueditsource' );
		$registry->unregister( 'panel/edit', 'ca-new-section' );
		$registry->unregister( 'panel/edit', 'ca-ve-edit' );
	}
}
