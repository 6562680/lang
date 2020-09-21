# lang

Языковая библиотека

```
$di = new Di();

// $di->registerProvider(LangProvider::class);
$this->di->bindShared(Lang::class, function () {
	$config = require __DIR__ . '/../../config/lang.php';

	return new Lang(
		new FileWordRepo(__DIR__ . '/../../storage/resources/lang'),
		new MemoryWordRepo(),

		$config[ 'locales' ],
		$config[ 'locale' ],
		$config[ 'locale_numeric' ],
		$config[ 'locale_fallback' ],
		$config[ 'locale_suffix' ]
	);
});

$lang = $di->get(LangService::class);
$lang->load('main');

// $lang->get('@main.null'); // @throws
// $lang->getOrNull('@main.null'); // null
// $lang->getOrDefault('@main.null', [], 'Text'); // 'Text'
// $lang->getOrWord('@main.null'); // '@main.hello.world'

$lang->get('@main.hello.world'); // 'Hello World'
$lang->getOrNull('@main.hello.world'); // 'Hello World'
$lang->getOrDefault('@main.hello.world', [], 'Text'); // 'Hello World'
$lang->getOrWord('@main.hello.world'); // 'Hello World'
```