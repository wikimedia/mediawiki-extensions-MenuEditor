<?php

namespace MediaWiki\Extension\MenuEditor\Api;

use Exception;
use MediaWiki\Extension\MenuEditor\Parser\WikitextMenuParser;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionStore;
use MWStake\MediaWiki\Component\Wikitext\ParserFactory;
use MWStake\MediaWiki\Lib\Nodes\INode;
use Title;
use TitleFactory;
use Wikimedia\ParamValidator\ParamValidator;

class ParseHandler extends Handler {
	/** @var ParserFactory */
	private $parserFactory;
	/** @var TitleFactory */
	private $titleFactory;
	/** @var RevisionStore */
	private $revisionStore;

	/**
	 * @param ParserFactory $parserFactory
	 * @param TitleFactory $titleFactory
	 * @param RevisionStore $revisionStore
	 */
	public function __construct(
		ParserFactory $parserFactory, TitleFactory $titleFactory, RevisionStore $revisionStore
	) {
		$this->parserFactory = $parserFactory;
		$this->titleFactory = $titleFactory;
		$this->revisionStore = $revisionStore;
	}

	/**
	 * @return \MediaWiki\Rest\Response|mixed
	 * @throws HttpException
	 */
	public function execute() {
		$params = $this->getValidatedParams();
		$page = $this->makeTitle( $params['pagename'] );
		$revision = $this->getRevision( $page, $params['revid'] );
		if ( !$revision ) {
			return $this->getResponseFactory()->createNoContent();
		}
		$parser = new WikitextMenuParser(
			$revision, $this->parserFactory->getNodeProcessors()
		);
		$nodes = $parser->parse();

		$data = $params['flat'] ? $nodes : $this->makeTree( $nodes );
		return $this->getResponseFactory()->createJson( $data );
	}

	/**
	 * @return array[]
	 */
	public function getParamSettings() {
		return [
			'pagename' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_TYPE => 'string',
			],
			'revid' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'flat' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false
			]
		];
	}

	/**
	 * @param string $pagename
	 * @return Title
	 * @throws HttpException
	 */
	private function makeTitle( string $pagename ) {
		$pagename = urldecode( $pagename );

		$title = $this->titleFactory->newFromText( $pagename );
		if ( !( $title instanceof Title ) ) {
			throw new HttpException( 'invalidtitle' );
		}

		return $title;
	}

	/**
	 * @param Title $page
	 * @param int|null $revid
	 * @return RevisionRecord|null
	 * @throws HttpException
	 */
	private function getRevision( Title $page, ?int $revid ): ?RevisionRecord {
		if ( !$page->exists() ) {
			return null;
		}
		if ( $revid === null ) {
			$revid = 0;
		}

		$revision = $this->revisionStore->getRevisionByTitle( $page, $revid );
		if ( !$revision ) {
			return null;
		}

		return $revision;
	}

	/**
	 * @param array $nodes
	 * @return array
	 */
	private function makeTree( array $nodes ) {
		$tree = [];
		/** @var MenuNode $node */
		foreach ( $nodes as $node ) {
			if ( $node->getLevel() !== 1 ) {
				continue;
			}
			$tree[] = $this->getNodeData( $node ) + $this->getChildren( $nodes, $node );
		}

		return $tree;
	}

	/**
	 * @param array $nodes
	 * @param MenuNode $node
	 * @return array[]
	 */
	private function getChildren( $nodes, $node ) {
		$parentFound = false;
		$children = [];
		/** @var MenuNode $node */
		foreach ( $nodes as $childNode ) {
			if ( $childNode === $node ) {
				$parentFound = true;
				continue;
			}
			if ( $parentFound ) {
				if ( $childNode->getLevel() === $node->getLevel() + 1 ) {
					$children[] = $this->getNodeData( $childNode ) + $this->getChildren( $nodes, $childNode );
				} else {
					return [ 'items' => $children ];
				}
			}
		}

		return [ 'items' => $children ];
	}

	/**
	 * @param INode $node
	 * @return array
	 * @throws Exception
	 */
	private function getNodeData( INode $node ) {
		$data = $node->jsonSerialize();
		unset( $data['wikitext'] );
		$data['name'] = 'menunode_' . random_int( 1, 999999 );

		return $data;
	}
}
