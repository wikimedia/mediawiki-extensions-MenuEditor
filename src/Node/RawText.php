<?php

namespace MediaWiki\Extension\MenuEditor\Node;

class RawText extends MenuNode {
	public function __construct(
		int $level,
		private string $text,
		string $originalWikitext = '',
	) {
		parent::__construct( $level, $originalWikitext );
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return 'menu-raw-text';
	}

	/**
	 * @param string $text
	 */
	public function setNodeText( string $text ) {
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getNodeText(): string {
		return $this->text;
	}

	/**
	 * @return string
	 */
	public function getCurrentData(): string {
		return "{$this->getLevelString()} {$this->getNodeText()}";
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		return [
			'type' => $this->getType(),
			'level' => $this->getLevel(),
			'text' => $this->getNodeText(),
			'wikitext' => $this->getCurrentData()
		];
	}
}
