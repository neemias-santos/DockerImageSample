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

		$key = $this->generateKey($params);
		if (isset($this->list[$key])) {
			return;
		}

		$this->list[$key] = $params;
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

	public function editList(string $key, $params)
	{
		if (!$params) {
			return;
		}

		$item = $this->getItemList($key);
		if (!$item) {
			return;
		}

		$this->list[$key] = $params;
		return $this->list;
	}

	public function deleteList($key)
	{
		$item = $this->getItemList($key);
		if (!$item) {
			return null;
		}
		unset($this->list[$key]);
	}

	public function generateKey($params): string
	{
		return base64_encode($params);
	}

}