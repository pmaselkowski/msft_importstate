<?php

namespace Maslosoft\ImportStates\Renderers;

use Maslosoft\ImportStates\Interfaces\DataReaderInterface;

class StatesRenderer
{
	private DataReaderInterface $reader;
	public function __construct(DataReaderInterface $reader)
	{
		$this->reader = $reader;
	}

	public function __toString(): string
	{
		$html = [];
		$html[] = '<table class="table">';
		foreach ($this->reader->read() as $idx => $row)
		{
			if($idx === 0)
			{
				$cellTag = 'th';
			}
			else
			{
				$cellTag = 'td';
			}
			$html[] = '<tr>';
			foreach($row as $field => $value)
			{
				$html[] = "<$cellTag>";
				$html[] = htmlspecialchars($value);
				$html[] = "</$cellTag>";
			}
			$html[] = '</tr>';
		}
		$html[] = '</table>';
		return implode("\n", $html);
	}
}