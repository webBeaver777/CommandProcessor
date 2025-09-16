<?php

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

// Настраиваем Monolog
$logger = new Logger('test');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

$dealId = 42;
$repository = new InMemoryDealRepository;
$deal = new Deal($dealId);
$repository->saveDeal($deal);

// Создаем CommandProcessor с репозиторием и логгером
$processor = new CommandProcessor($repository, $logger);

// Создаём обработчики с репозиторием
$acceptedHandler = new AcceptedCommandHandler($repository);
$contactHandler = new ContactCommandHandler;
$reasonSetHandler = new ReasonSetCommandHandler($repository);
$reasonShowHandler = new ReasonShowCommandHandler($repository);

// Регистрируем обработчики
$processor->registerHandler($acceptedHandler);
$processor->registerHandler($contactHandler);
$processor->registerHandler($reasonSetHandler);
$processor->registerHandler($reasonShowHandler);

// Формируем CommandContext с параметрами
$context = new CommandContext(['deal' => $deal]);
$context->repository = $repository; // для ContactCommandHandler

// Проверяем обработку каждой команды через CommandProcessor
$processor->process('/принято 500 офис', $context);
echo "AcceptedCommandHandler выполнен\n";
formatDeal($deal);

$deal->contact = 'Иван Иванов';
$processor->process('/контакт', $context);
echo "ContactCommandHandler выполнен\n";
formatDeal($deal);

$processor->process('/причина_закрытия удалена транзакция', $context);
echo "ReasonSetCommandHandler выполнен\n";
formatDeal($deal);

$processor->process('/причина', $context);
echo "ReasonShowCommandHandler выполнен\n";
formatDeal($deal);

// Показываем итог состояния сделки
var_dump($deal);

function formatDeal(Deal $deal)
{
    echo "\n================= Состояние сделки =================\n";
    echo 'ID: '.$deal->id."\n";
    echo 'Contact: '.($deal->contact ?? '-')."\n";
    echo 'Properties:'.PHP_EOL;
    if (! empty($deal->properties)) {
        foreach ($deal->properties as $key => $value) {
            echo "  [$key] => $value\n";
        }
    } else {
        echo "  (нет)\n";
    }
    echo 'Messages:'.PHP_EOL;
    if (! empty($deal->messages)) {
        foreach ($deal->messages as $msg) {
            echo "  - $msg\n";
        }
    } else {
        echo "  (нет)\n";
    }
    echo "===================================================\n\n";
}
