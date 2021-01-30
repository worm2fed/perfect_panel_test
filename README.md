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
