<?php

namespace dockerPHP;

class SmallList
{
	protected array $list;

	public function __construct()
	{
		$this->list = [];
	}

	public function addlist($params)
	{
		if ($params == null) {
			return;
		}

		$key = $this->getNewKey();
		if (isset($this->list[$key])) {
			return;
		}

		$this->list[$key] = $params;
		return $key;
	}

	public function getList()
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

	public function deleteList($key)
	{
		$item = $this->getItemList($key);
		if (!$item) {
			return null;
		}
		unset($this->list[$key]);
	}

	public function generateKey(): string
	{
		return uniqid('list');
	}

	/**
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