<?php

namespace MediaWiki\Extension\MenuEditor\Node;

class GenericKeyword extends Keyword {

	public function __construct(
		private readonly string $type,
		int $level,
		string $keyword,
		?string $originalWikitext = null
	) {
		parent::__construct( $level, $keyword, $originalWikitext );
	}

		/**
		 * @return string
		 */
	public function getType(): string {
		return $this->type;
	}

}
