<?php

/**
 * Custom Order Reference for PrestaShop
 *
 * This software package is licensed under `proprietary` license[s].
 *
 * @package maslosoft/customorderreference_prestashop
 * @license proprietary
 *
 */

namespace Maslosoft\ImportStates;

use Db;

class Installer
{
	public function install(): bool
	{
		$sqls = require __DIR__ . '/../sql/init.php';
		$db = Db::getInstance();
		$result = [];
		foreach($sqls as $sql)
		{
			$result[] = $db->execute($sql);
		}
		return count($result) === array_sum($result);
	}
}