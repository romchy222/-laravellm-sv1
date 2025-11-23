# Laravel LMS - Comprehensive Learning Management System

## Обзор проекта

Это полнофункциональная Learning Management System (LMS), построенная на Laravel 11, которая поддерживает все современные требования к платформе электронного обучения.

## Реализованные функции

### 1. Управление пользователями ✅

- **Аутентификация**: Поддержка email, телефона и SSO (через Laravel Socialite)
- **Роли**: 5 ролей с детальной системой прав доступа
  - Студент (student) - доступ к обучению
  - Преподаватель (teacher) - создание курсов, проверка заданий
  - Куратор (curator) - управление группами и отчеты
  - Модератор (moderator) - модерация контента
  - Администратор (admin) - полный доступ
- **Группы**: Система групп, классов и потоков
- **Профили**: Прогресс, достижения, статистика
- **Импорт/Экспорт**: CSV, Excel через Maatwebsite/Excel

### 2. Система курсов ✅

**Модели**: `Course`, `CourseModule`, `Lesson`

Структура:
- Курсы → Модули → Уроки
- Поддержка различных типов уроков:
  - Видео (video)
  - Текст (text)
  - Тесты (quiz)
  - Задания (assignment)
  - SCORM (scorm)
  - PDF документы (pdf)
  - Интерактивные задания (interactive)

**Функции:**
- Drip-контент (unlock_after_days)
- Расписание (start_date, end_date)
- Форматы курса (self_paced, cohort, blended)
- Сертификаты после завершения
- Контроль публикации
- SEO метаданные

### 3. Уроки и задания ✅

**Модели**: `Question`, `QuestionBank`, `UserAnswer`, `Assignment`, `AssignmentSubmission`

**Типы вопросов:**
- Множественный выбор (multiple_choice)
- Одиночный выбор (single_choice)
- Верно/Неверно (true_false)
- Короткий ответ (short_answer)
- Эссе (essay)
- Соответствие (matching)
- Упорядочивание (ordering)

**Функции:**
- Банки вопросов для переиспользования
- Автоматическая проверка
- Ручная проверка заданий
- Ограничение времени (time_limit)
- Ограничение попыток (max_attempts)
- Объяснения к ответам (explanation)
- Система баллов

### 4. Видеоплеер 🔧

**Функции (требуют интеграции):**
- Безопасное хранение (через AWS S3 или similar)
- Прогресс-бар отслеживания
- Встроенные вопросы в видео (через content JSON)
- Защита от скачивания

### 5. Коммуникации ✅

**Модели**: `LessonComment`

**Реализовано:**
- Комментарии к урокам с вложенностью
- Система статусов (active/hidden)

**Требует доработки:**
- Чаты (можно интегрировать Laravel Echo + Pusher)
- Личные сообщения (добавить модель Message)
- Форум курса (добавить модель ForumTopic, ForumPost)
- Push/Email/Telegram уведомления (Laravel Notifications)

### 6. Управление группами ✅

**Модель**: `Group`

**Функции:**
- Привязка к курсу
- Преподаватель группы
- Ограничение количества студентов
- Расписание (JSON формат)
- Домашние задания через Assignment
- Автоматическое распределение (требует реализации логики)

### 7. Журнал успеваемости ✅

**Модели**: `UserProgress`, `Enrollment`

**Отслеживание:**
- Прогресс по каждому уроку
- Процент выполнения курса
- Оценки за задания
- Время, проведенное на уроке
- Количество попыток
- Статус (not_started, in_progress, completed)
- Последний доступ

### 8. Админ-панель 🔧

**Базовая структура создана**

Требует добавления:
- UI/Frontend для администрирования
- CRUD контроллеры для всех моделей
- Фильтры и поиск
- Дашборд с аналитикой

### 9. Монетизация ✅

**Модели**: `UserWallet`, `WalletTransaction`

**Функции:**
- Цены на курсы (price, currency)
- Статус оплаты в Enrollment
- Система кошельков пользователя
- Транзакции (credit/debit)

**Интеграции (требуют настройки):**
- Kaspi Pay
- Stripe
- PayPal
- ЮKassa

### 10. Интеграции 🔧

**API готова** (routes/api.php)

Требует реализации:
- Webhooks endpoints
- Zoom/Google Meet/Teams интеграция
- Telegram бот для уведомлений
- CRM коннекторы (AmoCRM, Bitrix24)

### 11. Геймификация ✅

**Модель**: `Achievement`, `UserAchievement`

**Реализовано:**
- Достижения с требованиями
- Система баллов
- Типы ачивок

**Требует добавления:**
- Leaderboard (можно реализовать через запросы)
- Квесты (добавить модель Quest)
- Система уровней

### 12. Безопасность ✅

**Реализовано:**
- RBAC через Spatie/Laravel-Permission
- Отслеживание входов (last_login_at, last_login_ip)
- Session management

**Требует:**
- 2FA (Laravel Fortify)
- IP/Device restrictions
- Журнал действий админов (модель AdminLog)
- Защита видео от скачивания (DRM)

### 13. Мобильные возможности 🔧

**Требует:**
- Responsive frontend (Bootstrap/Tailwind)
- PWA manifest
- Service Workers для offline
- Push уведомления

### 14. Аналитика 🔧

**База данных поддерживает:**
- Сбор метрик прогресса
- Поведенческие данные

**Требует:**
- Analytics контроллеры
- Дашборды визуализации
- Отчеты

### 15. Контент-менеджмент ✅

**Модели**: `Category`, `Tag`, `CourseTag`

**Функции:**
- Категории с вложенностью
- Теги для курсов
- Версионность (через soft deletes)

**Требует:**
- Библиотека медиа (Media model)
- Поиск (Laravel Scout)

### 16. Корпоративные функции 🔧

**Multi-tenancy** требует:
- Tenant model
- Tenant middleware
- Isolated databases или shared with tenant_id

**Функции требуют добавления:**
- Программы обучения (LearningPath model)
- Матрица компетенций (Competency model)
- 360° оценка

### 17. Дополнительные инструменты 🔧

**Сертификаты** ✅
- Модель готова
- Требует PDF генератор (DomPDF/Snappy)

**SCORM** 🔧
- Поддержка типа урока готова
- Требует SCORM парсер

**AI функции** 🔧
- Требует интеграцию с OpenAI API
- Генерация тестов
- Резюме уроков
- Рекомендации

## Структура базы данных

### Основные таблицы:
1. `users` - Пользователи
2. `roles` & `permissions` - Роли и права (Spatie)
3. `categories` - Категории курсов
4. `courses` - Курсы
5. `course_modules` - Модули курса
6. `lessons` - Уроки
7. `enrollments` - Записи на курсы
8. `groups` - Группы обучения
9. `group_user` - Связь пользователей и групп
10. `question_banks` - Банки вопросов
11. `questions` - Вопросы
12. `user_answers` - Ответы пользователей
13. `user_progress` - Прогресс по урокам
14. `assignments` - Домашние задания
15. `assignment_submissions` - Сданные работы
16. `achievements` - Достижения
17. `user_achievements` - Полученные достижения
18. `certificates` - Сертификаты
19. `tags` - Теги
20. `course_tags` - Теги курсов
21. `course_reviews` - Отзывы
22. `lesson_comments` - Комментарии
23. `user_wallets` - Кошельки
24. `wallet_transactions` - Транзакции

## Установка и запуск

### Требования:
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM

### Шаги установки:

1. Клонирование репозитория:
```bash
git clone https://github.com/romchy222/-laravellm-sv1.git
cd -laravellm-sv1
```

2. Установка зависимостей:
```bash
composer install
npm install
```

3. Настройка окружения:
```bash
cp .env.example .env
php artisan key:generate
```

4. Настройка базы данных в `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravellm_sv1
DB_USERNAME=root
DB_PASSWORD=
```

5. Миграции и сиды:
```bash
php artisan migrate
php artisan db:seed
```

6. Сборка фронтенда:
```bash
npm run build
```

7. Запуск сервера:
```bash
php artisan serve
```

## Следующие шаги для полной реализации

### Приоритет 1 (Критично):
1. ✅ Установить Spatie Laravel-Permission
2. ✅ Создать миграции для roles & permissions
3. 🔧 Создать UI для всех моделей (Admin Panel)
4. 🔧 Реализовать контроллеры для CRUD операций
5. 🔧 Создать Blade views или API для frontend

### Приоритет 2 (Важно):
1. Интеграция платежных систем
2. Система уведомлений (Email, Push, Telegram)
3. Видеоплеер с защитой
4. Поиск и фильтрация
5. Analytics dashboard

### Приоритет 3 (Расширенные функции):
1. SCORM импорт
2. AI интеграции
3. Multi-tenancy
4. Маркетплейс курсов
5. Мобильное приложение

## API Endpoints

### Аутентификация:
- POST `/api/register` - Регистрация
- POST `/api/login` - Вход
- POST `/api/logout` - Выход
- GET `/api/user` - Текущий пользователь

### Курсы:
- GET `/api/courses` - Список курсов
- POST `/api/courses` - Создать курс
- GET `/api/courses/{id}` - Детали курса
- PUT `/api/courses/{id}` - Обновить курс
- DELETE `/api/courses/{id}` - Удалить курс

### Уроки:
- GET `/api/courses/{course}/lessons` - Уроки курса
- POST `/api/lessons/{id}/progress` - Отметить прогресс
- POST `/api/lessons/{id}/answers` - Отправить ответы

### Дополнительные endpoints требуют реализации

## Технологический стек

- **Backend**: Laravel 11
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Roles & Permissions**: Spatie Laravel-Permission
- **File Processing**: Maatwebsite Excel
- **Image Processing**: Intervention Image
- **HTTP Client**: Guzzle

## Безопасность

- CSRF защита включена
- XSS защита через Blade
- SQL Injection защита через Eloquent
- Rate limiting на API
- HTTPS рекомендуется для продакшна

## Лицензия

MIT License

## Поддержка

Для вопросов и предложений создавайте Issues в GitHub.
