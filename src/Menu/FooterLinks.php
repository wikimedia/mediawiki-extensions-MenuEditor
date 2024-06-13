<?php

namespace MediaWiki\Extension\MenuEditor\Menu;

use MediaWiki\Extension\MenuEditor\ParsableMenu;
use MediaWiki\Extension\MenuEditor\Parser\IMenuParser;
use MediaWiki\Extension\MenuEditor\Parser\WikitextMenuParser;
use MediaWiki\Revision\RevisionRecord;
use MWStake\MediaWiki\Component\Wikitext\ParserFactory;
use Title;

class FooterLinks implements ParsableMenu {

	/** @var ParserFactory */
	private $parserFactory;

	/**
	 * @param ParserFactory $parserFactory
	 */
	public function __construct( ParserFactory $parserFactory ) {
		$this->parserFactory = $parserFactory;
	}

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
		return "ext.menueditor.ui.data.tree.FooterLinksTree";
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToTitle( Title $title ): bool {
		return $title->getNamespace() === NS_MEDIAWIKI && $title->getDBkey() === 'FooterLinks';
	}

	/**
	 * @inheritDoc
	 */
	public function getKey(): string {
		return 'footerlinks';
	}

	/**
	 * @inheritDoc
	 */
	public function getEmptyContent(): array {
		return [];
	}

	/**
	 * @param Title $title
	 * @param RevisionRecord|null $revision
	 * @return IMenuParser
	 * @throws \MWException
	 */
	public function getParser( Title $title, ?RevisionRecord $revision = null ): IMenuParser {
		if ( !$revision ) {
			$revision = $this->parserFactory->getRevisionForText( '', $title );
		}
		return new WikitextMenuParser(
			$revision, $this->parserFactory->getNodeProcessors()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getToolbarItems(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function getEditRight(): string {
		return 'editinterface';
	}
}
