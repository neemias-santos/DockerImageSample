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
		$this->smallList = new SmallList();
		$this->smallList->addlist($params);
		$key = $this->smallList->generateKey($params);

		$list = $this->smallList->getList();

		$this->assertArrayHasKey($key, $list);
	}

	/**
	 * @param $params
	 * @dataProvider listDataProvider
	 * @return void
	 */
	public function testEditSmallList($params)
	{
		$smallList = new SmallList();
		$smallList->addlist($params);

		$key = $smallList->generateKey($params);
		$list = $smallList->getItemList($key);
		$this->assertEquals($params, $list);

		$editedParam = 'apple';

		$result = $smallList->editList($key, $editedParam);

	}

	/**
	 *
	 * @return void
	 */
	public function listDataProvider()
	{
		return [
			'Test 1' => ['banana', 'mango'],
			'Test 2' => ['car', 'motor'],
		];
	}
}
