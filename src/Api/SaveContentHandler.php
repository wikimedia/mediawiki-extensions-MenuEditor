<?php

namespace MediaWiki\Extension\MenuEditor\Api;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use MWStake\MediaWiki\Component\Wikitext\ParserFactory;
use RequestContext;
use Title;
use TitleFactory;
use Wikimedia\ParamValidator\ParamValidator;

class SaveContentHandler extends Handler {
	/** @var ParserFactory */
	private $parserFactory;
	/** @var TitleFactory */
	private $titleFactory;

	/**
	 * @param ParserFactory $parserFactory
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		ParserFactory $parserFactory, TitleFactory $titleFactory
	) {
		$this->parserFactory = $parserFactory;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @return \MediaWiki\Rest\Response|mixed
	 * @throws HttpException
	 */
	public function execute() {
		$params = $this->getValidatedParams();
		$page = $this->makeTitle( $params['pagename'] );
		$body = $this->getValidatedBody();

		$parser = $this->parserFactory->newEmptyMenuParser( $page );
		$parser->addNodesFromData( $body );

		$rev = $parser->saveRevision( RequestContext::getMain()->getUser() );

		if ( !$rev ) {
			throw new HttpException( 'save-failed' );
		}
		return $this->getResponseFactory()->createJson( [
			'revision' => $rev->getId(),
		] );
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
			]
		];
	}

	/**
	 * @param string $contentType
	 * @return JsonBodyValidator
	 * @throws HttpException
	 */
	public function getBodyValidator( $contentType ) {
		if ( $contentType !== 'application/json' ) {
			throw new HttpException( 'ContentType header must be application/json' );
		}
		return new JsonBodyValidator( [] );
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
}
