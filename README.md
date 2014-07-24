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
>>>>>>> tmp
