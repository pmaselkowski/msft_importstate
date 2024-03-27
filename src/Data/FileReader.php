<?php

namespace Maslosoft\ImportStates\Data;

use Maslosoft\ImportStates\Interfaces\DataReaderInterface;

abstract class FileReader extends DataReader implements DataReaderInterface
{
	protected string $filename;

	public function __construct(string $filename)
	{
		$this->filename = $filename;
	}
}