<?php
App::import('Helper', 'MiSeo.Seo');

class SeoHelperTestCase extends CakeTestCase {

	function startTest() {
		$this->Seo = new SeoHelper();
	}

	function testTitleExplicit() {
		$expected = array(
			'<title', 'Page Title', '/title'
		);
		$result = $this->Seo->titleTag('Page Title');
		$this->assertTags($result, $expected);
	}

	function testTitleImplicit() {
		$expected = array(
			'<title', 'Page Title', '/title'
		);
		$this->Seo->title('Page Title');
		$result = $this->Seo->titleTag();
		$this->assertTags($result, $expected);
	}

	function testDescriptionExplicit() {
		$expected = array(
			'meta' => array(
				'name' => 'description',
				'content' => 'Page Description'
			)
		);
		$result = $this->Seo->descriptionTag('Page Description');
		$this->assertTags($result, $expected);
	}

	function testDescriptionImplicit() {
		$expected = array(
			'meta' => array(
				'name' => 'description',
				'content' => 'Page Description'
			)
		);
		$this->Seo->description('Page Description');
		$result = $this->Seo->descriptionTag();
		$this->assertTags($result, $expected);
	}

	function testKeywordsExplicit() {
		$expected = array(
			'meta' => array(
				'name' => 'keywords',
				'content' => 'keyword1,keyword2,key phrase 1,key phrase 2'
			)
		);
		$result = $this->Seo->keywordsTag(array('keyword1', 'keyword2', 'key phrase 1', 'key phrase 2'));
		$this->assertTags($result, $expected);
	}

	function testKeywordsImplicit() {
		$expected = array(
			'meta' => array(
				'name' => 'keywords',
				'content' => 'keyword1,keyword2,key phrase 1,key phrase 2'
			)
		);
		$this->Seo->keywords(array('keyword1', 'keyword2', 'key phrase 1', 'key phrase 2'));
		$result = $this->Seo->keywordsTag();
		$this->assertTags($result, $expected);
	}

	function testLinkExplicit() {
		$expected = array(
			'link' => array(
				'href' => '/someurl',
				'rel' => 'alternate'
			)
		);
		$result = $this->Seo->linkTag('alternate', '/someurl');
		$this->assertTags($result, $expected);
	}

	function testLinkImplicit() {
		$expected = array(
			'link' => array(
				'href' => '/someurl',
				'rel' => 'alternate'
			)
		);
		$this->Seo->link('alternate', '/someurl');
		$result = $this->Seo->linkTag('alternate');
		$this->assertTags($result, $expected);
	}

	function testMultipleLinks() {
		$expected = array(
			'link' => array(
				'title' => 'English',
				'type' => 'text/html',
				'hreflang' => 'en',
				'rel' => 'alternate',
				'href' => '/someurl'
			),
			array(
				'link' => array(
					'title' => 'Deutsch',
					'type' => 'text/html',
					'hreflang' => 'de',
					'rel' => 'alternate',
					'href' => '/someotherurl'
				)
			)
		);
		$this->Seo->link('alternate', '/someurl', array('title' => 'English', 'type' =>'text/html', 'hreflang' => 'en'));
		$this->Seo->link('alternate', '/someotherurl', array('title' => 'Deutsch', 'type' =>'text/html', 'hreflang' => 'de'));
		$result = $this->Seo->linkTag('alternate');
		$this->assertTags($result, $expected);
	}

	function testheaderTagsSimple() {
		$expected = array(
			array(
				'meta' => array(
					'name' => 'description',
					'content' => 'Enticing page description'
				)
			),
			array(
				'meta' => array(
					'name' => 'keywords',
					'content' => 'fluffy,bunny,cute'
				)
			)
		);

		$this->Seo->title('Enticing page title');
		$this->Seo->description('Enticing page description');
		$this->Seo->keywords('fluffy,bunny,cute');

		$result = $this->Seo->headerTags();
	}

/**
 * Test calling the headerTags funcion + removing/replacing tags
 *
 * @return void
 * @access public
 */
	function testheaderTags() {
		$expected = array(
			'<title', 'Enticing page title', '/title',
			array(
				'meta' => array(
					'name' => 'description',
					'content' => 'Enticing page description'
				)
			),
			array(
				'meta' => array(
					'name' => 'keywords',
					'content' => 'bunny,rabid,fugly'
				)
			),
			array(
				'meta' => array(
					'name' => 'author',
					'content' => 'Andy Dawson'
				)
			),
			'link' => array(
				'title' => 'English',
				'type' => 'text/html',
				'hreflang' => 'en',
				'rel' => 'alternate',
				'href' => '/someurl'
			),
			array(
				'link' => array(
					'title' => 'Deutsch',
					'type' => 'text/html',
					'hreflang' => 'de',
					'rel' => 'alternate',
					'href' => '/someotherurl'
				)
			)
		);

		$this->Seo->title('Enticing page title');
		$this->Seo->description('Enticing page description');
		$this->Seo->keywords('fluffy,bunny,cute');

		$this->Seo->removeKeyword('fluffy');
		$this->Seo->removeKeyword('cute');
		$this->Seo->addKeyword('rabid');
		$this->Seo->addKeyword('fugly');

		$this->Seo->meta('author', 'Andy Dawson');
		$this->Seo->link('alternate', '/someurl', array('title' => 'English', 'type' =>'text/html', 'hreflang' => 'en'));
		$this->Seo->link('alternate', '/someotherurl', array('title' => 'Deutsch', 'type' =>'text/html', 'hreflang' => 'de'));

		$result = $this->Seo->headerTags();
		$this->assertTags($result, $expected);
	}

	function endTest() {
		unset($this->Seo);
		ClassRegistry::flush();
	}
}