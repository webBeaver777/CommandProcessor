# CommandProcessor

![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)

CommandProcessor — PHP-библиотека для обработки текстовых команд (например, для CRM). Поддерживает интеграцию с Laravel и работу вне фреймворка.

## Возможности
- Простая регистрация и обработка текстовых команд
- Гибкая архитектура: легко расширять новыми обработчиками
- Интеграция с Laravel (ServiceProvider, artisan-команды)
- PSR-4 автозагрузка
- Логирование команд через PSR-3
- Покрытие тестами всех бизнес-команд

## Установка
```bash
composer require webbeaver777/command-processor
```

## Интеграция с Laravel

> В Laravel 12 сервис-провайдер CommandProcessorServiceProvider регистрируется автоматически через package discovery. Ручных действий не требуется.

2. Проверьте, что artisan-команды доступны:
   ```bash
   php artisan list | grep command
   ```
   Должны быть:
   - `command-processor:copy-tests` — копирует тесты пакета в ваш проект
   - `command:processor` — обрабатывает команды для сделки

## Использование artisan-команды для обработки команд

Команда для обработки команд:
```bash
php artisan command:processor <deal_id> <команда>
```

**Пример:**
```bash
php artisan command:processor 45 "/принято 500 офис"
```
- `<deal_id>` — ID сделки
- `<команда>` — текстовая команда (например, /принято 500 офис)

Результат обработки будет выведен в консоль.

## Публикация тестов

Тесты пакета можно скопировать в папку `tests/Feature` вашего проекта Laravel с помощью artisan-команды:
```bash
php artisan command-processor:copy-tests
```
Эта команда скопирует все тесты из пакета в директорию вашего проекта. После этого вы сможете запускать их стандартным способом:
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

## Структура пакета
- `src/` — исходный код пакета
  - `Laravel/CommandProcessorCommand.php` — artisan-команда для обработки команд
  - `Laravel/Commands/CopyCommandProcessorTests.php` — artisan-команда для копирования тестов
  - `Laravel/CommandProcessorServiceProvider.php` — сервис-провайдер для интеграции
- `tests/Feature/` — тесты для публикации в проект Laravel
- `tests/` — директория для тестов

## FAQ / Типичные ошибки

**Команда не видна в artisan:**
- Проверьте, что ServiceProvider добавлен`.
- Запустите `composer dump-autoload` для обновления автозагрузки.
- Проверьте список команд: `php artisan list | grep command`

**Ошибка при запуске команды:**
- Проверьте, что передаёте оба аргумента: ID сделки и команду.
- Пример корректного вызова: `php artisan command:processor 42 "/принято 500 офис"`

---

MIT License
