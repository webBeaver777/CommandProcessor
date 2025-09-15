# CommandProcessor

![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)

Framework-agnostic PHP-библиотека для обработки текстовых команд, предназначенная для CRM и других систем, где требуется реакция на текстовые команды. Поддерживает интеграцию с Laravel.

## Возможности
- Простая регистрация и обработка текстовых команд
- Гибкая архитектура: легко расширять новыми обработчиками
- Интеграция с любым PHP-приложением и Laravel
- PSR-4 автозагрузка

## Установка
```bash
composer require webbeaver777/command-processor
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
    new AcceptedCommandHandler()
);

$processor->process('accepted 123', 1); // пример вызова
```

## Интеграция с Laravel
Пакет содержит сервис-провайдер и консольную команду для Laravel:

1. Добавьте сервис-провайдер (обычно Laravel сделает это автоматически через `extra.laravel.providers`):
```php
// config/app.php
'providers' => [
    // ...
    Webbeaver\CommandProcessor\Laravel\CommandProcessorServiceProvider::class,
],
```
2. Используйте команду:
```bash
php artisan command:processor 222 "/причина_закрытия удалена транзакция"
```

## Расширение
Для добавления своей команды реализуйте интерфейс `CommandHandlerInterface`:
```php
use Webbeaver\CommandProcessor\Contracts\CommandHandlerInterface;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class MyCommandHandler implements CommandHandlerInterface {
    public static function commandName(): string { return 'mycmd'; }
    public function handle(string $args, CommandContext $context): void {
        // Ваша логика
    }
}
```

## Структура
- **Core/** — ядро (CommandProcessor)
- **Contracts/** — интерфейсы
- **Handlers/** — стандартные обработчики команд
- **Adapters/** — примеры репозиториев
- **DTO/** — объекты передачи данных
- **Laravel/** — интеграция с Laravel

## Тестирование
```bash
vendor/bin/phpunit
```

## Лицензия
MIT

---
Автор: [webBeaver777](https://webbeaver777.github.io/)
