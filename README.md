#ПРОБЛЕМА:
При поднятии докер-контейнера запускается локальный nginx-сервер,
который не отображает index.php текущего проекта,
а дефолтную пусть страницу nginx-сервера. Поэтому выполненную работу нельзя проверить
в docker-контейнере без его корректной настройки

#ЗАДАНИЕ:
Создать на Symfony 5+ приложение, которое позволяет через api просматривать/создавать/редактировать
список пользователей в бд mysql/postgress
Приложение должно запускаться в docker контейнерах. Обязательные поля в бд:
email, username, password. В списке пользователей должен быть поиск по username и email

#ЗАПУСК ПРОЕКТА:
```sh
$ bin/console doctrine:database:create --if-not-exists
$ bin/console doctrine:migrations:migrate --no-interaction
$ bin/console doctrine:fixtures:load
```

#РАБОТА С API

get:
```sh
curl -X 'GET' \
'http://localhost:8000/api/users/1' \
-H 'accept: application/ld+json'
```

get:
```sh
curl -X 'GET' \
'http://localhost:8000/api/users?limit=10' \
-H 'accept: application/ld+json'
```

create:
```sh
curl -X 'POST' \
'http://localhost:8000/api/users' \
-H 'accept: application/ld+json' \
-H 'Content-Type: application/ld+json' \
-d '{
"email": "new-user@namil.com",
"username": "new-user",
"password": "new-password"
}'
```

auth:
```sh
curl -X 'POST' \
'http://localhost:8000/auth' \
-H 'accept: application/ld+json' \
-H 'Content-Type: application/ld+json' \
-d '{
"username": "new-user",
"password": "new-password"
}'
```

update:
```sh
curl -X 'PATCH' \
'http://localhost:8000/api/users/51' \
-H 'accept: application/ld+json' \
-H 'Content-Type: application/merge-patch+json' \
-d '{
"email": "new-user@gmail.com",
"username": "new-user",
"password": "new-user-password"
}'
```
