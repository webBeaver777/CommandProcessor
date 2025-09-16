<?php

namespace Webbeaver\CommandProcessor\Tests;

use PHPUnit\Framework\TestCase;
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\Handlers\ContactCommandHandler;
use Webbeaver\CommandProcessor\DTO\Deal;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CommandProcessorTest extends TestCase
{
    public function testContactCommandHandled()
    {
        $repo = new InMemoryDealRepository;
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));
        $processor = new CommandProcessor($repo, $logger);
        $processor->registerHandler(
            ContactCommandHandler::commandName(),
            new ContactCommandHandler($repo)
        );
        $deal = new Deal(1);
        $deal->contact = 'info@example.com';
        $repo->saveDeal($deal);
        $context = new \Webbeaver\CommandProcessor\DTO\CommandContext(['deal' => $deal]);
        $processor->process('/контакт', $context);
        $messages = $repo->getMessages(1);
        $this->assertContains('Контакт клиента: info@example.com', $messages);
    }

    public function testUnknownCommandThrows()
    {
        $repo = new InMemoryDealRepository;
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));
        $processor = new CommandProcessor($repo, $logger);
        $deal = new Deal(2);
        $repo->saveDeal($deal);
        $context = new \Webbeaver\CommandProcessor\DTO\CommandContext(['deal' => $deal]);
        $this->expectException(\RuntimeException::class);
        $processor->process('/unknown', $context);
    }

    public function test_sanity_check()
    {
        $this->assertTrue(true);
    }

    public function testContactCommandAdapterFactoryAndLogger()
    {
        // Подготовка фабрики и регистрация адаптера
        $factory = new \Webbeaver\CommandProcessor\Core\CommandAdapterFactory();
        $factory->registerAdapter(
            \Webbeaver\CommandProcessor\DTO\ContactCommandDTO::class,
            fn(int $dealId) => new \Webbeaver\CommandProcessor\Adapters\ContactCommandAdapter($dealId)
        );

        // Подготовка DTO, логгера и адаптера
        $dealId = 42;
        $dto = new \Webbeaver\CommandProcessor\DTO\ContactCommandDTO('Иван Иванов');
        $logFile = __DIR__ . '/test-contact.log';
        @unlink($logFile); // очистить лог перед тестом
        $logger = new \Webbeaver\CommandProcessor\Core\FileLogger($logFile);

        $adapter = $factory->getAdapter($dto, $dealId);
        $adapter->handle($dto, $logger);

        // Проверка, что лог содержит ожидаемую строку
        $logContent = file_get_contents($logFile);
        $this->assertStringContainsString('Выполнение Contact для сделки 42: Иван Иванов', $logContent);
    }
}
