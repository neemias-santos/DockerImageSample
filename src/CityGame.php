<?php

namespace dockerPHP;

class CityGame
{
	public static array $knownCities = [
		'Atlanta',
		'Anchorage',
		'El Paso',
		'Oakland',
		'Denver',
		'Ridgewood',
	];

	/**
	 * @param array $cities
	 *
	 * @return bool
	 */
	public function gameChecker(array $cities): bool
	{
		$verifiedCities = [];
		foreach ($cities as $city) {
			$firstLetters = strtolower(substr($city, 0, 1));
			if (!empty($verifiedCities) && $firstLetters != substr(end($verifiedCities), -1)) {
				return false;
			}
			if (array_search($city, $verifiedCities) !== false) {
				return false;
			}
			$verifiedCities[] = $city;
		}

		return true;
	}

	public function play($city)
	{
		return $this->knownCities($city) ?? 'I lose';
	}

	/**
	 * @param $city
	 *
	 * @return mixed
	 */
	private function knownCities($city): mixed
	{
		$lastLetter = substr($city, -1);
		$playedCities = [];
		foreach (self::$knownCities as $knownCity) {
			if ($knownCity === $city) continue;

			if (isset($playedCities[$knownCity])) continue;

			$firstLetters = strtolower(substr($knownCity, 0, 1));
			if ($firstLetters === $lastLetter) {
				$playedCities[$knownCity] = true;
				return $knownCity;
			}
		}
		return false;
	}
}