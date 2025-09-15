# CommandProcessor

![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)

CommandProcessor — PHP-библиотека для обработки текстовых команд (например, для CRM). Поддерживает интеграцию с Laravel и работу вне фреймворка.

## Возможности
- Простая регистрация и обработка текстовых команд
- Гибкая архитектура: легко расширять новыми обработчиками
- Интеграция с Laravel (ServiceProvider, artisan-команда)
- PSR-4 автозагрузка

## Установка
```bash
composer require webbeaver777/command-processor
```

## Публикация тестов
Тесты пакета автоматически копируются в папку `tests/Feature` вашего проекта Laravel после установки или обновления пакета (см. post-install-cmd/post-update-cmd в composer.json). Если нужно скопировать тесты вручную:

```bash
php scripts/copy-tests.php
```

## Запуск тестов
```bash
./vendor/bin/phpunit
```

## Быстрый старт (без фреймворка)
```php
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Handlers\AcceptedCommandHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('commands');
$logger->pushHandler(new StreamHandler('php://stdout'));
$repository = new InMemoryDealRepository();
$processor = new CommandProcessor($repository, $logger);

$processor->registerHandler(
    AcceptedCommandHandler::commandName(),
    new AcceptedCommandHandler($repository)
);

$processor->process('accepted 123', 1); // пример вызова
```

## Интеграция с Laravel
Пакет содержит сервис-провайдер и artisan-команду для Laravel:

```php
// config/app.php
'providers' => [
    // ...
    Webbeaver\CommandProcessor\Laravel\CommandProcessorServiceProvider::class,
],
```

## Структура пакета
- `src/` — исходный код пакета
- `publishable/tests/Feature/` — тесты для публикации в проект Laravel
- `scripts/copy-tests.php` — скрипт для копирования тестов
- `tests/` — директория для тестов в проекте (после публикации)

---

MIT License
