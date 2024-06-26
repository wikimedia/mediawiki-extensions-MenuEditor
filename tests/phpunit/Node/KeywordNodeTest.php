<?php

namespace MediaWiki\Extension\MenuEditor\Tests\Node;

use MediaWiki\Extension\MenuEditor\Node\Keyword;

class KeywordNodeTest extends RawTextNodeTest {

	/**
	 * @param array $input
	 * @param array $mutate
	 * @param string $expected
	 * @dataProvider provideData
	 * @covers \MediaWiki\Extension\MenuEditor\Node\Keyword::setKeyword
	 * @covers \MediaWiki\Extension\MenuEditor\Node\Keyword::getKeyword
	 * @covers \MediaWiki\Extension\MenuEditor\Node\Keyword::getCurrentData
	 */
	public function testNode( $input, $mutate, $expected ) {
		parent::testNode( $input, $mutate, $expected );
	}

	protected function provideNode( $input ) {
		return new Keyword( ...$input );
	}

	/**
	 * @return array[]
	 */
	public static function provideData() {
		$data = parent::provideData();
		$data['mutate-level-and-text']['mutate'] = [
			'level' => $data['mutate-level-and-text']['mutate']['level'],
			'keyword' => $data['mutate-level-and-text']['mutate']['text'],
		];

		return $data;
	}
}
