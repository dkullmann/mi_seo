<?php
App::import('Helper', array('Html', 'MiSeo.Seo'));

class SeoHelperTestCase extends CakeTestCase {

	function startTest() {
		$this->Seo = new SeoHelper();
		$this->Seo->Html = new HtmlHelper();
	}

	function testTitleExplicit() {
		$expected = array(
			'meta' => array(
				'name' => 'title',
				'content' => 'Page Title'
			)
		);
		$result = $this->Seo->titleTag('Page Title');
		$this->assertTags($result, $expected);
	}

	function testTitleImplicit() {
		$expected = array(
			'meta' => array(
				'name' => 'title',
				'content' => 'Page Title'
			)
		);
		$result = $this->Seo->title('Page Title');
		$this->assertFalse($result);

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
		$result = $this->Seo->description('Page Description');
		$this->assertFalse($result);

		$result = $this->Seo->descriptionTag();
		$this->assertTags($result, $expected);
	}

	function testKeywordsExplicit() {
		$expected = array(
			'meta' => array(
				'name' => 'keywords',
				'content' => 'keyword1, keyword2, key phrase 1, key phrase 2'
			)
		);
		$result = $this->Seo->keywordsTag(array('keyword1', 'keyword2', 'key phrase 1', 'key phrase 2'));
		$this->assertTags($result, $expected);
	}

	function testKeywordsImplicit() {
		$expected = array(
			'meta' => array(
				'name' => 'keywords',
				'content' => 'keyword1, keyword2, key phrase 1, key phrase 2'
			)
		);
		$result = $this->Seo->keywords(array('keyword1', 'keyword2', 'key phrase 1', 'key phrase 2'));
		$this->assertFalse($result);

		$result = $this->Seo->keywordsTag();
		$this->assertTags($result, $expected);
	}

	function endTest() {
		unset($this->Seo);
		ClassRegistry::flush();
	}
}