<?php

use Gzhegow\Di\Di;
use Gzhegow\Facader\Facader;

require __DIR__ . '/../vendor/autoload.php';

$facader = new Facader();
$facader->setFacadesRootPath(__DIR__ . '/../src/Facades');
$facader->generate(Di::class, 'makeOrFail', [
	'Gzhegow\Lang\Facades\LangFacade' => [
		\Gzhegow\Lang\Domain\Lang::class,
	],
]);