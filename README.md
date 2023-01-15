# Medicine
## Запуск
### Контейнер с бд
```sh
docker compose up
```

### php сервер
```sh
php -S localhost:8003 -t public/
```

## Работа с бд и Doctrine
### Пересоздание бд
Дроп бд:
```bash
php bin/console doctrine:database:drop --force
```
Восстановление бд:
```bash
php bin/console doctrine:database:create
```

### Миграции
#### Создать новую миграцию
```bash
php bin/console make:migration
```
#### Установить последнюю миграцию
```bash
php bin/console doctrine:migrations:migrate
```
#### Создать пустую миграцию
```bash
php bin/console doctrine:migrations:generate
```
#### Загрузить существующую в бд миграцию
```bash
php bin/console doctrine:migrations:execute --up DoctrineMigrations\\Version20221010123446_add_aliasCategory
```

### Установка фикстур
Фикстуры пока не используются, это на будущее
#### Стереть данные из бд и записать фикстуры
```bash
php bin/console doctrine:fixtures:load
```
#### Дописать фикстуры в бд без стирания
```bash
php bin/console doctrine:fixtures:load --append
```