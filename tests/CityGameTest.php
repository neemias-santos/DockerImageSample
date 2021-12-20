<?php

use dockerPHP\CityGame;
use PHPUnit\Framework\TestCase;

class CityGameTest extends TestCase
{
	/**
	 * @dataProvider citiesDataProvider
	 * @covers       \CityGame::sayHello
	 */
	public function testMyTest(array $cities, $expected)
	{
		$CityGameClass = new CityGame();
		self::assertEquals($expected, $CityGameClass->gameChecker($cities));
	}

	/**
	 * @param $city
	 *
	 * @dataProvider playDataProvider
	 */
	public function testBotPlayer(array $cities)
	{
		$cityGameClass = new CityGame();
		foreach ($cities as $city) {
			self::assertNotEquals('I lose', $cityGameClass->play($city));
		}
	}

	public function citiesDataProvider(): array
	{
		return [
			'Success' => [
				[
					'Atlanta',
					'Anchorage',
					'El Paso',
					'Oakland',
					'Denver',
					'Ridgewood',
				],
				true,
			],
			"Fail 1" => [
				[
					'Atlanta',
					'Atlanta',
					'Anchorage',
					'El Paso',
				],
				false,
			],
			"Fail 2" => [
				[
					'Atlanta',
					'Oakland',
				],
				false,
			],
		];
	}


	public function playDataProvider(): array
	{
		return [
			'Success' => [['Atlanta', 'El Paso', 'Denver']],
		];
	}
}