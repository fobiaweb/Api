# API
=====

php App Api


$method = new Api_Object_Name($params, $options);


При создании объекта в конструкторе запускаеться ``$this->configure()``, где и определяються свойства параметров метода.


Для выполнения метода и получения результата, выполните след.:

    $method->invoke();
    if ($error = $api->errorInfo) {
        $result = $error;
    } else {
        $result = $api->getResponse();
    }


Эти действия можно выполнить короче

    $result = $method();

Обращение к объекту как к фунции вызовит последовательность вышеперечисленый действий

## Core

Меотд вызываеться путем
    
    $method->invoke();

функция инициализирует ``$this->initialize();`` параметры метода и запускает ``$method->execute()``, где и происходит вcя логика метотода. Все действия обернуты обработкой исключений. Т.Е. в крации можно представить:

    function invoke() {
        try {
            $this->initialize();
            $this->execute();
        } catch (\Exception $exc) {}
    }



## map

```php
$map = array(
    'users.create' => array('file',     '/path/to/file.php', array(... )),
    'users.search' => array('callable', {closure}, array(... ) ),
    'users.delete' => array('object',   'ClassName:method', array(... )
);
```

__file__

Подключаеться файл с переменой ``$p`` - переданные параметры


__callable__

Вызаваеться функция, передавая ``$p`` в качестве параметра


