<?php

namespace Maslosoft\ImportStates\Interfaces;

interface PreProcessorInterface
{
	public function preProcess(string $data): array;
}