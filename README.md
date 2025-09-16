# CommandProcessor

![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)

CommandProcessor — PHP-библиотека для обработки текстовых команд (например, для CRM). Поддерживает интеграцию с Laravel и работу вне фреймворка.

## Возможности
- Простая регистрация и обработка текстовых команд
- Гибкая архитектура: легко расширять новыми обработчиками
- Интеграция с Laravel (ServiceProvider, artisan-команда)
- PSR-4 автозагрузка
- Логирование команд через PSR-3
- Покрытие тестами всех бизнес-команд

## Установка
```bash
composer require webbeaver777/command-processor
```

## Публикация тестов
Тесты пакета можно скопировать в папку `tests/Feature` вашего проекта Laravel:

```bash
php artisan vendor:publish --tag=command-processor-tests
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

$processor->registerHandler(new AcceptedCommandHandler($repository));

$deal = new \Webbeaver\CommandProcessor\DTO\Deal(1);
$repository->saveDeal($deal);
$context = new \Webbeaver\CommandProcessor\DTO\CommandContext(['deal' => $deal]);
$processor->process('/принято 123 офис', $context); // пример вызова
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
- `tests/Feature/` — тесты для публикации в проект Laravel
- `tests/` — директория для тестов

---

MIT License

