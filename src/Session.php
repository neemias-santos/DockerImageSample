<?php

namespace dockerPHP;

use DateTime;

class Session
{
	const API_URL_GET = "https://candidate.hubteam.com/candidateTest/v3/problem/dataset?userKey=e5295838aa1726426df0bf7f5b65";
	const API_URL_POST = "https://candidate.hubteam.com/candidateTest/v3/problem/result?userKey=e5295838aa1726426df0bf7f5b65";


	/**
	 * This function handle the whole process from the inicial point until send the post request
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function handleSessionData(): bool
	{
		$sessions = $this->getSessions();
		$processedSessions = $this->groupSessions($sessions);
		return $this->postGroupSession($processedSessions);
	}

	/**
	 * It will get the jsom from the API and convert to array
	 * @return array
	 */
	public function getSessions(): array
	{
		$homepage = file_get_contents(self::API_URL_GET);
		return json_decode($homepage, true);
	}

	/**
	 * This function will group the sessions
	 * @param array $sessions
	 *
	 * @return array[]
	 * @throws \Exception
	 */
	public function groupSessions(array $sessions): array
	{
		$processedSessions = [];
		$orderedSession = $this->orderSessionByUserId($sessions);

		foreach ($orderedSession as $sessionData) {
			$visitorId = $sessionData['visitorId'];
			$session = $sessionData['events'];

			usort($session, function ($currentItem, $nextItem) {
				if ($currentItem['timestamp'] == $nextItem['timestamp']) {
					return 0;
				}
				return ($currentItem['timestamp'] < $nextItem['timestamp']) ? -1 : 1;
			});

			$lastAccess = '';
			foreach ($session as $access) {
				if (!isset($processedSessions[$visitorId])) {
					$processedSessions[$visitorId][] = [
						'duration' => 0,
						'pages' => [$access['url']],
						'startTime' => $access['timestamp'],
					];
					$lastAccess = $access;
					continue;
				}

				$first_date = new DateTime(date('Y-m-d H:i:s', $lastAccess['timestamp'] / 1000));
				$second_date = new DateTime(date('Y-m-d H:i:s', $access['timestamp'] / 1000));
				$difference = $first_date->diff($second_date);

				if ($difference->i > 10) {
					$processedSessions[$visitorId][] = [
						'duration' => 0,
						'pages' => [$access['url']],
						'startTime' => $access['timestamp'],
					];
					$lastAccess = $access;
					continue;
				}

				$lastAddedKey = array_key_last($processedSessions[$visitorId]);
				$processedSessions[$visitorId][$lastAddedKey]['pages'][] = $access['url'];
				$processedSessions[$visitorId][$lastAddedKey]['duration'] = $access['timestamp'] - $processedSessions[$visitorId][$lastAddedKey]['startTime'];
				$lastAccess = $access;
			}
		}
		return ['sessionsByUser' => $processedSessions];
	}

	/**
	 * Send the post request
	 *
	 * @param $processedSessions
	 *
	 * @return bool
	 */
	private function postGroupSession($processedSessions)
	{
		$data = json_encode($processedSessions, JSON_UNESCAPED_SLASHES);
		$crl = curl_init(self::API_URL_POST);
		curl_setopt($crl, CURLOPT_POST, 1);
		curl_setopt($crl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($crl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($crl);
		return !($result['status'] == 'error');
	}

	/**
	 * It will sort the access in chronological order
	 * @param array $sessions
	 *
	 * @return array
	 */
	private function orderSessionByUserId(array $sessions): array
	{
		$orderedSessions = [];
		foreach ($sessions['events'] as $session) {
			if (!array_key_exists($session['visitorId'], $orderedSessions)) {
				$orderedSessions[$session['visitorId']] = [
					'firstAccess' => $session['timestamp'],
					'events' => [],
					'visitorId' => $session['visitorId'],
				];
			}
			if ($orderedSessions[$session['visitorId']]['firstAccess'] > $session['timestamp']) {
				$orderedSessions[$session['visitorId']]['firstAccess'] = $session['timestamp'];
			}
			$orderedSessions[$session['visitorId']]['events'][] = $session;
		}
		usort($orderedSessions, function ($currentItem, $nextItem) {
			if ($currentItem['firstAccess'] == $nextItem['firstAccess']) {
				return 0;
			}
			return ($currentItem['firstAccess'] < $nextItem['firstAccess']) ? -1 : 1;
		});

		return $orderedSessions;
	}
}