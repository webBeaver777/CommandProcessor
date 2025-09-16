# CommandProcessor

![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)

CommandProcessor — PHP-библиотека для обработки текстовых команд (например, для CRM). Поддерживает интеграцию с Laravel и работу вне фреймворка.

## Возможности
- Простая регистрация и обработка текстовых команд через фабрику
- Гибкая архитектура: легко расширять новыми адаптерами и DTO
- Интеграция с Laravel (ServiceProvider, artisan-команды)
- PSR-4 автозагрузка
- Логирование команд через PSR-3
- Покрытие тестами всех бизнес-команд

## Установка
```bash
composer require webbeaver777/command-processor
```

## Быстрый старт

1. **Создайте DTO для вашей команды:**
```php
class AcceptedCommandDTO extends CommandDTO
{
    public string $amount;
    public string $office;
    public function __construct(string $amount, string $office) {
        $this->amount = $amount;
        $this->office = $office;
    }
}
```

2. **Реализуйте адаптер с приватным deal_id:**
```php
class AcceptedCommandAdapter implements CommandAdapterInterface
{
    private int $dealId;
    public function __construct(int $dealId) { $this->dealId = $dealId; }
    public function handle(AcceptedCommandDTO $command, LoggerInterface $logger): void
    {
        $logger->info("Принято для сделки {$this->dealId}: сумма={$command->amount}, офис={$command->office}");
        // ...бизнес-логика...
    }
}
```

3. **Зарегистрируйте адаптер в фабрике:**
```php
$factory = new CommandAdapterFactory();
$factory->registerAdapter(AcceptedCommandDTO::class, fn(int $dealId) => new AcceptedCommandAdapter($dealId));
```

4. **Вызовите команду:**
```php
$dealId = 123;
$dto = new AcceptedCommandDTO('500', 'офис');
$logger = new FileLogger(__DIR__ . '/test.log');
$adapter = $factory->getAdapter($dto, $dealId);
$adapter->handle($dto, $logger);
```

## Расширение
- Для новой команды создайте свой DTO и адаптер, зарегистрируйте через фабрику.
- Фабрику менять не нужно.

## Интеграция с Laravel
- Зарегистрируйте адаптеры в сервис-провайдере.
- Используйте любой PSR-3 логгер (например, Monolog).

## Требования
- PHP 8+
- psr/log

## Структура
- `src/Webbeaver/CommandProcessor/DTO` — DTO-команды
- `src/Webbeaver/CommandProcessor/Adapters` — Адаптеры
- `src/Webbeaver/CommandProcessor/Core` — Фабрика, логгер
- `src/Webbeaver/CommandProcessor/Contracts` — Интерфейсы

---

Пакет легко расширяется, не зависит от Laravel, соответствует PSR-12.
