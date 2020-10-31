# lang

Языковая библиотека

```
$di = new \Gzhegow\Di\Di();
$di->registerProvider(\Gzhegow\Lang\Di\LangProvider::class);
```

При необходимости вы наследуете провайдер и меняете то, что нужно, например, пути к конфигам и ресурсам.

```
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
// получить список всех зарегистрированных языков, коллекция массивов
public function getLanguages() : array

// получить активный язык или язык по-умолчанию
public function getLang() : string
public function getLangDefault() : string

// получить массив представляющий собой настройки конкретного языка
public function getLanguageFor(string $lang) : array
public function getLanguage() : array
public function getLanguageDefault() : array

// получить локаль из конфига языков. Так язык может быть "ru", а локаль "ru_RU"
public function getLocaleFor(string $lang) : string
public function getLocale() : string
public function getLocaleDefault() : string

// сменить язык, пакет не сохраняет изменения в сессию, об этом позаботьтесь самостоятельно
public function setLang(string $lang, string $localeNumeric = null)
public function setLangDefault(string $lang)

// сформировать ссылку с указанным языком для роутинга или отображения в DOM
// да, функция может получать ссылки с языками и умно их заменять, убирая из ссылки язык по-умолчанию
public function languagePath(string $lang = null, string $url = null, array $q = null, string $ref = null) : string

// пометить группу ключей как запрашиваемую при ближайшем обращении к репозиторию. В процессе работы помечаете определенные модули, и в момент ближайшего перевода из базы возьмутся соответствующие списки переводов
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

// заменить последовательности на переменные: если в вашей строке есть переменные в виде `Hello, :name`, она будет заменена на указанную в массиве $placeholders['name']
public function interpolate(string $word, array $placeholders) : string;

// перевести массив или коллекцию массивов. Разумно бывает сначала данные собрать и перед самым отображением - перевести, а не совать функции перевода прямо в файлы шаблонов, заставляя систему бегать в базу переводов сотню раз
public function translate(array $dct, array $placeholders = [], string $group = null, string $locale = null) : array;
public function translateMany(iterable $iterable, array $placeholders = [], string $group = null, string $locale = null) : array;

// собрать в массив переводы ключей по списку
public function collect(array $words, array $placeholders = [], string $group = null, string $locale = null) : array;
```
