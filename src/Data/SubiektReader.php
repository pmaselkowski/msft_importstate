<?php

namespace Maslosoft\ImportStates\Data;

use Maslosoft\ImportStates\PreProcessors\SubiektPreProcessor;

class SubiektReader extends FileReader
{
	public function read(): array
	{
		$data = file_get_contents($this->filename);
		return (new SubiektPreProcessor)->preProcess($data);
	}
}