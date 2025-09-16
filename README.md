# CommandProcessor

![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)

CommandProcessor — современная PHP-библиотека для обработки текстовых команд (например, для CRM, чат-ботов, интеграций). Поддерживает работу вне фреймворка и интеграцию с Laravel.

## Возможности
- Простая регистрация и обработка текстовых команд через единый CommandProcessor
- Гибкая архитектура: легко расширять новыми обработчиками и DTO
- Интеграция с Laravel (ServiceProvider, artisan-команды)
- PSR-4 автозагрузка
- Логирование команд через PSR-3 (Monolog)
- Покрытие тестами всех бизнес-команд

## Установка
```bash
composer require webbeaver777/command-processor
```

## Пример использования

```php
require __DIR__.'/vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\DTO\CommandContext;
use Webbeaver\CommandProcessor\DTO\Deal;
use Webbeaver\CommandProcessor\Handlers\AcceptedCommandHandler;
use Webbeaver\CommandProcessor\Handlers\ContactCommandHandler;
use Webbeaver\CommandProcessor\Handlers\ReasonSetCommandHandler;
use Webbeaver\CommandProcessor\Handlers\ReasonShowCommandHandler;

// Настраиваем логгер PSR-3 (Monolog)
$logger = new Logger('test');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

// Создаём репозиторий и тестовую сделку
$dealId = 42;
$repository = new InMemoryDealRepository();
$deal = new Deal($dealId);
$repository->saveDeal($deal);

// Создаём CommandProcessor
$processor = new CommandProcessor($repository, $logger);

// Регистрируем обработчики команд
$processor->registerHandler(new AcceptedCommandHandler($repository));
$processor->registerHandler(new ContactCommandHandler());
$processor->registerHandler(new ReasonSetCommandHandler($repository));
$processor->registerHandler(new ReasonShowCommandHandler($repository));

// Формируем контекст с параметрами
$context = new CommandContext(['deal' => $deal]);
$context->repository = $repository; // для некоторых обработчиков

// Обрабатываем команды
$processor->process('/принято 500 офис', $context);
$deal->contact = 'Иван Иванов';
$processor->process('/контакт', $context);
$processor->process('/причина_закрытия удалена транзакция', $context);
$processor->process('/причина', $context);

// Форматированный вывод состояния сделки
function formatDeal(Deal $deal) {
    echo "\n================= Состояние сделки =================\n";
    echo "ID: " . $deal->id . "\n";
    echo "Contact: " . ($deal->contact ?? '-') . "\n";
    echo "Properties:" . PHP_EOL;
    if (!empty($deal->properties)) {
        foreach ($deal->properties as $key => $value) {
            echo "  [$key] => $value\n";
        }
    } else {
        echo "  (нет)\n";
    }
    echo "Messages:" . PHP_EOL;
    if (!empty($deal->messages)) {
        foreach ($deal->messages as $msg) {
            echo "  - $msg\n";
        }
    } else {
        echo "  (нет)\n";
    }
    echo "===================================================\n\n";
}
formatDeal($deal);
```

**Результат:**
- Все команды обрабатываются через единый CommandProcessor.
- Состояние сделки (ID, контакт, свойства, сообщения) выводится в удобном виде.
- Можно легко расширять обработчики и DTO под свои задачи.

## Стандартные команды

- `/принято 500 офис` — устанавливает сумму и офис для сделки
- `/контакт` — выводит контакт клиента
- `/причина_закрытия ...` — устанавливает причину закрытия
- `/причина` — выводит причину закрытия

## Расширение
- Для новой команды создайте свой DTO и обработчик, зарегистрируйте через CommandProcessor.
- Не требуется менять ядро или фабрику.

## Интеграция с Laravel
- Зарегистрируйте обработчики в сервис-провайдере.
- Используйте любой PSR-3 логгер (например, Monolog).

## Требования
- PHP 8+
- psr/log

## Структура
- `src/Webbeaver/CommandProcessor/DTO` — DTO-команды
- `src/Webbeaver/CommandProcessor/Handlers` — Обработчики
- `src/Webbeaver/CommandProcessor/Adapters` — Адаптеры
- `src/Webbeaver/CommandProcessor/Core` — Ядро, логгер
- `src/Webbeaver/CommandProcessor/Contracts` — Интерфейсы

## Тестирование

Для запуска тестов используйте:
```bash
vendor/bin/phpunit
```

---

Пакет легко расширяется, не зависит от Laravel, соответствует PSR-12.
