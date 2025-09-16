<?php

namespace Webbeaver\CommandProcessor\Tests\Feature;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\DTO\Deal;
use Webbeaver\CommandProcessor\Handlers\ContactCommandHandler;

class ContactCommandHandlerTest extends TestCase
{
    public function test_contact_command_with_contact()
    {
        $repo = new InMemoryDealRepository;
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $processor = new CommandProcessor($repo, $logger);
        $processor->registerHandler(
            ContactCommandHandler::commandName(),
            new ContactCommandHandler($repo)
        );

        $deal = new Deal(101);
        $deal->contact = 'Иван Иванов';
        $repo->saveDeal($deal);

        $processor->process('/контакт', 101);

        $messages = $repo->getMessages(101);
        $this->assertContains('Контакт клиента: Иван Иванов', $messages);
    }

    public function test_contact_command_without_contact()
    {
        $repo = new InMemoryDealRepository;
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $processor = new CommandProcessor($repo, $logger);
        $processor->registerHandler(
            ContactCommandHandler::commandName(),
            new ContactCommandHandler($repo)
        );

        $deal = new Deal(102);
        $repo->saveDeal($deal);

        $processor->process('/контакт', 102);
        $messages = $repo->getMessages(102);
        $this->assertContains('Контакт клиента не указан', $messages);
    }
}
