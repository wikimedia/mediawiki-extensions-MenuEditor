<?php

namespace MediaWiki\Extension\MenuEditor;

use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\GenericKeywordNodeProcessor;

class Extension {

	public static function onRegistration() {
		mwsInitComponents();

		$GLOBALS['mwsgWikitextNodeProcessorRegistry'] += [
			"menu-mediawiki-sidebar-keyword" => [
				"factory" => static function () {
					return new GenericKeywordNodeProcessor(
						'mediawiki-sidebar-keyword',
						[ 'TOOLBOX', 'LANGUAGES', 'SEARCH' ]
					);
				}
			]
		];
	}
}
