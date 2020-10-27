# lang

Языковая библиотека

```
$di = new Di();

// $di->registerProvider(LangProvider::class);
$this->di->bindShared(Lang::class, function () {
	$config = require __DIR__ . '/../../config/lang.php';

	return new Lang(
		new PhpFileWordRepo(__DIR__ . '/../../storage/resources/lang'),
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
// $lang->getOrWord('@main.null'); // '@main.null'

$lang->get('@main.hello.world'); // 'Hello World'
$lang->getOrNull('@main.hello.world'); // 'Hello World'
$lang->getOrDefault('@main.hello.world', [], 'Text'); // 'Hello World'
$lang->getOrWord('@main.hello.world'); // 'Hello World'
```

Функционал:
```
// получить активный язык
public function getLoc() : string;
public function getLocale() : array;

// сменить язык
public function setLocale(string $locale, string $localeNumeric = null) : void;

// создать языковой путь для формирования URL
public function localePath(string $locale = null, string $url = null, array $q = null, string $ref = null) : string;

// пометить группу ключей как запрашиваемую при ближайшем обращении к репозиторию
public function load(...$groups) : void;

// получить перевод из репозитория
public function get(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : string;
public function getOrNull(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string;
public function getOrWord(string $aword, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string;
public function getOrDefault(string $aword, array $placeholders = [], string $default = null, string $group = null, string $locale = null, string &$word = null) : ?string;

// получить перевод и применить функцию определения количества
public function choice(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : string;
public function choiceOrNull(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string;
public function choiceOrWord(string $aword, string $number, array $placeholders = [], string $group = null, string $locale = null, string &$word = null) : ?string;
public function choiceOrDefault(string $aword, string $number, array $placeholders = [], string $default = null, string $group = null, string $locale = null, string &$word = null) : ?string;

// проверка наличия перевода в репозитории
public function has(string $aword, string $locale, string $group = null, string &$word = null, string &$result = null) : bool;

// заменить последовательности на переменные
public function interpolate(string $word, array $placeholders) : string;

// перевести массив или коллекцию массивов
public function translate(array $dct, array $placeholders = [], string $group = null, string $locale = null) : array;
public function translateMany(iterable $iterable, array $placeholders = [], string $group = null, string $locale = null) : array;

// собрать в массив переводы ключей по списку
public function collect(array $words, array $placeholders = [], string $group = null, string $locale = null) : array;
```
