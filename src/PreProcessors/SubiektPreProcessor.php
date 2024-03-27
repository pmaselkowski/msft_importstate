<?php

namespace Maslosoft\ImportStates\PreProcessors;

use League\Csv\Reader;
use Maslosoft\ImportStates\Interfaces\PreProcessorInterface;

class SubiektPreProcessor implements PreProcessorInterface
{
	public function preProcess(string $data): array
	{
		$header ='Lp|Rodzaj|Symbol|Nazwa|Stan|J.m.|hurtowa netto|hurtowa brutto|Opis';

		$data = iconv('windows-1250', 'utf-8', $data);
		$data = preg_replace('~^(?!\|).+~m', '', $data);
		$data = preg_replace('~\n+~m', "\n", $data);
		$data = trim($data);
		$data = preg_replace('~ +~m', " ", $data);
		$data = preg_replace('~^\|~m', "", $data);
		$data = preg_replace('~^ +~m', "", $data);
		$data = preg_replace('~ +$~m', "", $data);
		$data = preg_replace('~\|$~m', "", $data);
		$data = preg_replace('~ +$~m', "", $data);
		$data = preg_replace('~^Lp\|Rodza.+\n~m', '', $data);
		$data = str_replace([' |', '| '], '|', $data);
		$data = "$header\n$data";
		$intermediateOutput = tempnam('/tmp/', __CLASS__);
		file_put_contents($intermediateOutput, $data);
		$reader = Reader::createFromPath($intermediateOutput, 'r');
		$reader->setDelimiter('|');
		return iterator_to_array($reader, true);
	}

}