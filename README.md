# Библиотека для парсинга CommerceML 2 файлов

Основная цель данной библиотеки - парсинг XML файлов стандарта CommerceML 2
и представление данных в виде объектов.

### Установка

1. Обновите ваш composer.json файл.

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/ArtemsWay/parser1c"
    }
],
"require": {

    "artemsway/parser1c": "dev-master"
},
```

2. Выполните команду ``` composer update ```.

### Использование

```php
    require 'vendor/autoload.php';
    
    $importFile = 'path/to/file/import0_1.xml';
    $offersFile = 'path/to/file/offers0_1.xml';
    
    $importData = \ArtemsWay\Parser1C\Parser1C::parseImportFile($importFile);
    $offersData = \ArtemsWay\Parser1C\Parser1C::parseOffersFile($offersFile);
```


### Выходные данные

```php
    var_dump($importData);
    
    array:6 [▼
      "schemaVersion" => "2.07"
      "importTime" => "2017-05-13T21:41:15"
      "onlyChanges" => false
      "categories" => array:218 [▶]
      "properties" => array:608 [▶]
      "products" => array:4535 [▶]
    ]
    
    var_dump($offersData);
        
    array:6 [▼
      "schemaVersion" => "2.07"
      "importTime" => "2017-05-13T21:41:15"
      "onlyChanges" => false
      "priceTypes" => array:1 [▶]
      "warehouses" => array:2 [▶]
      "offers" => array:4535 [▶]
    ]
```

### Дополнение

Возможен парсинг части данных.

```php
    use ArtemsWay\Parser1C\Parser1C;
    use ArtemsWay\Parser1C\Parsers\DOM\ImportParser;
    
    $importFile = 'path/to/file/import0_1.xml';
    
    $parser = new Parser1C($importFile, new ImportParser);
    
    $partOfData = $parser->load()->parseProducts()->getData();
```

Есть возможность добавлять новые классы парсеров

```php
    use ArtemsWay\Parser1C\Parsers\DOM\DOMParser;
    use ArtemsWay\Parser1C\Parsers\ParserInterface;
    
    class ContractorParser extends DOMParser
    {
        public $contractors = [];
        
        public function parseContractors()
        {
            ...
        }
    }
```

Использование

```php
    use ArtemsWay\Parser1C\Parser1C;
    use ArtemsWay\Parser1C\Parsers\DOM\ImportParser;
    
    $contractorsFile = 'path/to/file/contractors0_1.xml';
    
    $parser = new Parser1C($contractorsFile, new ContractorParser);
    
    $partOfData = $parser->load()->parseAll()->getData();
```

##### ToDO
1. Добавить SAX парсер, для уменьшения потребления RAM памяти.