<?php

namespace MediaWiki\Extension\MenuEditor\Menu;

use MediaWiki\Extension\MenuEditor\ParsableMenu;
use MWStake\MediaWiki\Component\Wikitext\ParserFactory;

abstract class GenericMenu implements ParsableMenu {

	public function __construct(
		protected readonly ParserFactory $parserFactory,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function getEmptyContent(): array {
		return [];
	}

	/**
	 * @return array
	 */
	protected function getProcessors(): array {
		$processors = $this->parserFactory->getNodeProcessors();
		$allowed = $this->getAllowedNodes();
		$applicable = [];
		foreach ( $processors as $key => $processor ) {
			if ( in_array( $key, $allowed ) ) {
				$applicable[] = $processor;
			}
		}

		return $applicable;
	}

	/**
	 * @return ParserFactory
	 */
	protected function getParserFactory(): ParserFactory {
		return $this->parserFactory;
	}
}
