<?php

namespace MediaWiki\Extension\MenuEditor\Api;

use MediaWiki\Extension\MenuEditor\MenuFactory;
use MediaWiki\Extension\MenuEditor\ParsableMenu;
use MediaWiki\Extension\MenuEditor\Parser\WikitextMenuParser;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MWStake\MediaWiki\Component\Wikitext\ParserFactory;
use MWStake\MediaWiki\Lib\Nodes\IParser;

abstract class MenuHandler extends Handler {
	public function __construct(
		private readonly TitleFactory $titleFactory,
		private readonly MenuFactory $menuFactory,
		private readonly ParserFactory $parserFactory,
	) {
	}

	/**
	 * @param Title $title
	 * @param RevisionRecord|null $revision
	 *
	 * @return IParser
	 */
	protected function getParserForRevision(
		Title $title, ?RevisionRecord $revision = null
	): IParser {
		foreach ( $this->menuFactory->getAllMenus() as $menu ) {
			if ( $menu->appliesToTitle( $title ) ) {
				if ( $menu instanceof ParsableMenu ) {
					return $menu->getParser( $title, $revision );
				}
				if ( !$revision ) {
					$revision = $this->parserFactory->getRevisionForText( '', $title );
				}
				return new WikitextMenuParser(
					$revision, $this->parserFactory->getNodeProcessors()
				);
			}
		}
		throw new HttpException( 'No suitable parser found', 500 );
	}

	/**
	 * @param string $pagename
	 * @return Title
	 * @throws HttpException
	 */
	protected function makeTitle( string $pagename ) {
		$pagename = urldecode( $pagename );

		$title = $this->titleFactory->newFromText( $pagename );
		if ( !( $title instanceof Title ) ) {
			throw new HttpException( 'invalidtitle' );
		}

		return $title;
	}
}
