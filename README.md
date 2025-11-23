# laravellm-sv1

**Laravel LMS (Learning Management System) на MySQL**

## Возможности (MVP)

- Аутентификация (email), роли: студент/преподаватель/админ
- Управление пользователями и ролями
- Группы, курсы, уроки (текст, видео, тест)
- Прогресс, достижения
- Простая админ-панель (CRUD для курсов, пользователей)
- Импорт пользователей (CSV)
- Стартовая структура Laravel 11

## Быстрый старт

1. Склонируйте репозиторий:

    ```
    git clone https://github.com/romchy222/laravellm-sv1.git
    cd laravellm-sv1
    ```

2. Установите зависимости:

    ```
    composer install
    npm install && npm run build
    cp .env.example .env
    php artisan key:generate
    ```

3. Настройте параметры БД в `.env`
4. Выполните миграции:

    ```
    php artisan migrate
    ```

5. Запустите dev-сервер:

    ```
    php artisan serve
    ```

## Структура папок

- `app/Models` — Eloquent-модели (User, Role, Course и т.п.)
- `database/migrations` — Поэтапные миграции БД
- `resources/views` — Интерфейс (Blade)
- `app/Http/Controllers` — REST-контроллеры (CourseController, UserController ...)
- `routes/web.php` — Основные маршруты

## Контакты и обсуждение

Welcome PRs & Issues!
