<?php

namespace dockerPHP;

class SmallList
{
	protected array $list;

	public function __construct()
	{
		$this->list = [];
	}

	/**
	 * @param $params
	 *
	 * @return string|null
	 */
	public function addList($params): ?string
	{
		if ($params == null) {
			return null;
		}

		$key = $this->getNewKey();
		if (isset($this->list[$key])) {
			return null;
		}

		$this->list[$key] = $params;
		return $key;
	}

	/**
	 * @return array
	 */
	public function getList(): array
	{
		return $this->list;
	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function getItemList(string $key): mixed
	{
		return $this->list[$key] ?? null;
	}

	/**
	 * @param string $key
	 * @param $params
	 *
	 * @return null
	 */
	public function editList(string $key, $params)
	{
		if (!$params) {
			return null;
		}

		$item = $this->getItemList($key);
		if (!$item) {
			return null;
		}

		$this->list[$key] = $params;
		return $this->list[$key];
	}

	/**
	 * @param $key
	 *
	 * @return void|null
	 */
	public function deleteList($key)
	{
		$item = $this->getItemList($key);
		if (!$item) {
			return null;
		}
		unset($this->list[$key]);
	}

	/**
	 * @return string
	 */
	public function generateKey(): string
	{
		return uniqid('list');
	}

	/**
	 * It will generate an uniqid and check if that id it was used before
	 * @return string
	 */
	private function getNewKey(): string
	{
		do{
			$key = $this->generateKey();
		} while($this->getItemList($key) != null);
		return $key;
	}

}