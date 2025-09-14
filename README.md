### ЗАДАЧА
* `Задание_кандидату_для_подготовки_к_собеседованию.docx`

### НАСТРОЙКА
* `composer install`
* `yii init`
* настроить доступ к СУБД
* * `yii migrate`
* генерация данных:
* * `yii fake-data/user-down`
* * `yii fake-data/user`

### ЗАПУСК
* `php -S127.0.0.1:9001 -tfrontend/web`
* `php -S127.0.0.1:9000 -tbackend/web`

### ТЕСТИРОВАНИЕ
* `http://127.0.0.1:9001`
* файл `test183.postman_collection.json` для 'Postman'

### АВТОР
* Шатров Алексей Сергеевич <mail@ashatrov.ru>