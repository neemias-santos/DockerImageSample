<?php


use dockerPHP\SmallList;
use PHPUnit\Framework\TestCase;

class SmallListTest extends TestCase
{

	/**
	 * @dataProvider listDataProvider
	 * @return void
	 */
	public function testAddSmallList($params)
	{
		$smallList = new SmallList();
		$itemKey = $smallList->addlist($params);
		$list = $smallList->getList();
		$this->assertArrayHasKey($itemKey, $list);
	}

	/**
	 * @param $params
	 * @dataProvider listDataProvider
	 * @return void
	 */
	public function testEditSmallList($params)
	{
		$smallList = new SmallList();
		$key = $smallList->addlist($params);

		$list = $smallList->getItemList($key);
		$this->assertEquals($params, $list);

		$editedParam = 'apple';

		$result = $smallList->editList($key, $editedParam);

		$this->assertEquals($editedParam, $result);
	}

	/**
	 * @dataProvider listDataProvider
	 */
	public function testDelete($params) {

		$smallList = new SmallList();
		$itemKey = $smallList->addlist($params);
		$list = $smallList->getList();
		$this->assertArrayHasKey($itemKey, $list);

		$smallList->deleteList($itemKey);

		$list = $smallList->getList();
		$this->assertArrayNotHasKey($itemKey, $list);


	}

	/**
	 * @return array
	 */
	public function listDataProvider(): array
	{
		$testClass = new stdClass();
		$testClass->fruitName = 'Pineapple';
		return [
			'Test 1' => [$testClass],
			'Test 2' => ['mango'],
			'Test 3' => [['orange', 'lime']],
		];
	}
}
