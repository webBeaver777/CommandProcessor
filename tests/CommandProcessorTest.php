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
        $processor->process('/контакт', 1);
        $messages = $repo->getMessages(1);
        $this->assertContains('Контакт клиента: info@example.com', $messages);
    }

    public function testUnknownCommandThrows()
    {
        $repo = new InMemoryDealRepository;
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));
        $processor = new CommandProcessor($repo, $logger);
        $this->expectException(\RuntimeException::class);
        $processor->process('/unknown', 1);
    }
}
