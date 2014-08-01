# API
=====

php App Api


$api = new Api_Object_Name($params);

Для выполнения метода и получения результата, выполните след.:

    $api->invoke();
    if ($error = $api->errorInfo) {
        $result = $error;
    } else {
        $result = $api->getResponse();
    }


Эти действия можно выполнить короче

    $result = $api();

Обращение к объекту как к фунции вызовит последовательность вышеперечисленый действий


## map

```php
$map = array(
    'users.create' => array('file',     '/path/to/file.php'),
    'users.search' => array('callable', {closure} ),
    'users.delete' => array('object',   'ClassName:method', 'arg1', arg2... ),
);
```

__file__

Подключаеться файл с переменой ``$p`` - переданные параметры


__callable__

Вызаваеться функция, передавая ``$p`` в качестве параметра


