# Laravel LMS - Comprehensive Learning Management System

**Полнофункциональная Learning Management System на Laravel 11 + MySQL**

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 🚀 Возможности

Эта LMS платформа включает в себя все современные функции для электронного обучения:

### ✅ Управление пользователями
- Многофакторная аутентификация (email, телефон, SSO)
- 5 ролей с детальной системой прав: студент, преподаватель, куратор, модератор, админ
- Группы, классы и потоковые группы
- Профили с прогрессом и достижениями
- Импорт/экспорт пользователей (CSV, Excel)

### ✅ Курсы и контент
- Иерархическая структура: Курсы → Модули → Уроки
- 7 типов уроков: текст, видео, тесты, задания, SCORM, PDF, интерактивные
- Drip-контент (открытие по расписанию)
- 3 формата курса: самообучение, потоковый, смешанный
- Автоматическая генерация сертификатов

### ✅ Тесты и задания
- 7 типов вопросов: выбор, ввод, соответствие, упорядочивание и др.
- Банки вопросов для переиспользования
- Автоматическая и ручная проверка
- Ограничение времени и попыток
- Объяснения к ответам

### ✅ Прогресс и аналитика
- Детальный трекинг прогресса студентов
- Процент выполнения курса
- Оценки и статистика
- Журнал активности
- Отчеты для преподавателей

### ✅ Геймификация
- Система достижений (ачивки)
- Баллы за активность
- Возможность создания лидербордов

### ✅ Монетизация
- Платные курсы
- Система кошельков пользователей
- Готовность к интеграции: Kaspi Pay, Stripe, PayPal, ЮKassa
- Промокоды и подписки

### 🔧 В разработке
- Интерактивный видеоплеер с защитой от скачивания
- Чаты и форумы
- Push/Email/Telegram уведомления
- Интеграции с Zoom, Google Meet, Teams
- CRM интеграции (AmoCRM, Bitrix24)
- Multi-tenancy для корпоративных клиентов
- AI функции (генерация тестов, резюме уроков)

## 📋 Требования

- PHP 8.2 или выше
- MySQL 8.0 или выше
- Composer
- Node.js & NPM

## 🛠 Быстрый старт

### 1. Клонирование репозитория

```bash
git clone https://github.com/romchy222/-laravellm-sv1.git
cd -laravellm-sv1
```

### 2. Установка зависимостей

```bash
composer install
npm install
```

### 3. Настройка окружения

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Настройка базы данных

Отредактируйте `.env` файл:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravellm_sv1
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Миграции и начальные данные

```bash
php artisan migrate
php artisan db:seed
```

Будут созданы роли и права доступа:
- **Admin** - полный доступ
- **Teacher** - управление курсами и группами
- **Curator** - управление группами и отчеты
- **Moderator** - модерация контента
- **Student** - доступ к обучению

### 6. Сборка фронтенда

```bash
npm run build
```

### 7. Запуск сервера

```bash
php artisan serve
```

Приложение будет доступно по адресу: `http://localhost:8000`

## 📂 Структура проекта

```
├── app/
│   ├── Http/Controllers/     # Контроллеры
│   │   └── Auth/             # Аутентификация
│   ├── Models/               # Eloquent модели
│   └── ...
├── database/
│   ├── migrations/           # Миграции БД
│   └── seeders/              # Начальные данные
├── routes/
│   ├── web.php               # Web маршруты
│   ├── api.php               # API маршруты
│   └── auth.php              # Аутентификация
├── resources/
│   └── views/                # Blade шаблоны
├── config/                   # Конфигурация
└── public/                   # Публичные файлы
```

## 📊 Модели данных

Система включает следующие основные модели:

- **User** - Пользователи с ролями
- **Course** - Курсы
- **CourseModule** - Модули курса
- **Lesson** - Уроки
- **Enrollment** - Записи на курсы
- **Group** - Группы обучения
- **Question** / **QuestionBank** - Вопросы и банки вопросов
- **UserProgress** - Прогресс пользователей
- **Assignment** - Домашние задания
- **Achievement** - Достижения
- **Certificate** - Сертификаты
- **UserWallet** - Кошельки пользователей
- И многое другое...

Полная документация по моделям: [IMPLEMENTATION.md](IMPLEMENTATION.md)

## 🔐 Безопасность

- CSRF защита включена по умолчанию
- XSS защита через Blade templates
- SQL Injection защита через Eloquent ORM
- Role-Based Access Control (RBAC)
- Отслеживание входов и IP адресов
- Готовность к 2FA

## 📖 Документация

- [Полная документация по реализации](IMPLEMENTATION.md)
- [API Documentation](#) (в разработке)

## 🤝 Вклад в проект

Мы приветствуем любые PR и Issues! 

### Как помочь:
1. Fork репозитория
2. Создайте feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit изменения (`git commit -m 'Add some AmazingFeature'`)
4. Push в branch (`git push origin feature/AmazingFeature`)
5. Откройте Pull Request

## 📝 Лицензия

MIT License - см. [LICENSE](LICENSE) файл

## 👥 Авторы

- GitHub: [@romchy222](https://github.com/romchy222)

## 🙏 Благодарности

- [Laravel](https://laravel.com) - PHP Framework
- [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission) - Roles & Permissions
- [Maatwebsite Excel](https://docs.laravel-excel.com) - Excel Import/Export

## 📞 Контакты

Вопросы и предложения? Создавайте [Issues](https://github.com/romchy222/-laravellm-sv1/issues)!

---

**Сделано с ❤️ для образования**
