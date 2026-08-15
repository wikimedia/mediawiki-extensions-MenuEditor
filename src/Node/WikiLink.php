<?php

namespace MediaWiki\Extension\MenuEditor\Node;

use MediaWiki\Title\TitleFactory;

class WikiLink extends TwoFoldLinkSpec {
	public function __construct(
		int $level,
		string $target,
		string $label,
		string $originalWikitext,
		TitleFactory $titleFactory,
	) {
		parent::__construct( $target, $label, $originalWikitext, $titleFactory, $level );
	}

	/**
	 * @param string $target
	 */
	public function verifyTarget( string $target ) {
		if ( !$this->isValidPageName( $target ) ) {
			throw new \UnexpectedValueException( 'Target must be a valid wikipage' );
		}
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return 'menu-wiki-link';
	}

	/**
	 * @return string
	 */
	public function getCurrentData(): string {
		if ( trim( $this->getLabel() ) !== '' ) {
			return "{$this->getLevelString()} [[{$this->getTarget()}|{$this->getLabel()}]]";
		}
		return "{$this->getLevelString()} [[{$this->getTarget()}]]";
	}
}
