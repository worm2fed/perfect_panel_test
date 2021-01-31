# perfect_panel_test

## Первое задание

```sql
SELECT u.id AS ID
     , CONCAT(u.first_name, ' ', u.last_name) AS Name
     , MIN(b.author) as Author
     , GROUP_CONCAT(b.name SEPARATOR ', ') AS Books
FROM users u
LEFT JOIN user_books ub ON ub.user_id = u.id
LEFT JOIN books b ON b.id = ub.book_id
WHERE u.age BETWEEN 7 AND 17
GROUP BY u.id
HAVING COUNT(ub.id) = 2
   AND COUNT(DISTINCT(b.author)) = 1
```

## Второе задание

Второе задание реализовано при помощи фреймворка Yii2.

1. Необходимо склонировать репозиторий, создать виртуальный хост, применить миграции.
2. Для регистрации пользователя используется метод `host/signup`, для авторизации – `host/login`.
3. Для получения курсов используется метод `host/api/v1?method=rates&currency=CURRENCIES`, где `CURRENCIES` - это строка, содержащая интересующие валюты (например USD,RUB,EUR). Если `CURRENCIES` содержит неизвестную валюту, запрос вернёт пустой результат.
4. Для конвертации курсов используется метод `host/api/v1?method=convert&&currency_from=FROM&currency_to=TO&value=VALUE`, где `FROM` и `TO` - это строки, содержащие интересующие валюты, а `VALUE` - числовое значение для конвертации (минимальное значение 0.01). На текущий момент поддерживается конвертация из любой известной валюты в BTC или из BTC в любую известную валюту.
