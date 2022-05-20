<?php

namespace MediaWiki\Extension\MenuEditor\Menu;

use Title;

class MediawikiSidebar extends MenuWithActionOverride {
	/**
	 * @inheritDoc
	 */
	public function getRLModule(): string {
		return "ext.menuEditor.tree";
	}

	/**
	 * @inheritDoc
	 */
	public function getJSClassname(): string {
		return "ext.menueditor.ui.data.tree.MediawikiSidebarTree";
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToTitle( Title $title ): bool {
		return $title->getNamespace() === NS_MEDIAWIKI && $title->getDBkey() === 'Sidebar';
	}

	/**
	 * @inheritDoc
	 */
	public function getKey(): string {
		return 'mediawiki-sidebar';
	}

	/**
	 * @inheritDoc
	 */
	public function getEmptyContent(): array {
		return [];
	}
}
