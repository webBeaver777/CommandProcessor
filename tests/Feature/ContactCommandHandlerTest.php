<?php

namespace Webbeaver\CommandProcessor\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Webbeaver\CommandProcessor\Adapters\ContactCommandAdapter;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Core\FileLogger;
use Webbeaver\CommandProcessor\DTO\ContactCommandDTO;
use Webbeaver\CommandProcessor\DTO\Deal;

class ContactCommandHandlerTest extends TestCase
{
    public function test_contact_command_with_contact()
    {
        $repo = new InMemoryDealRepository;
        $dealId = 101;
        $deal = new Deal($dealId);
        $deal->contact = 'Иван Иванов';
        $repo->saveDeal($deal);
        $dto = new ContactCommandDTO('Иван Иванов');
        $logger = new FileLogger(__DIR__.'/contact.log');
        $adapter = new ContactCommandAdapter($dealId, $repo);
        $adapter->handle($dto, $logger);
        $messages = $repo->getMessages($dealId);
        $this->assertContains('Контакт клиента: Иван Иванов', $messages);
    }

    public function test_contact_command_without_contact()
    {
        $repo = new InMemoryDealRepository;
        $dealId = 102;
        $deal = new Deal($dealId);
        $repo->saveDeal($deal);
        $dto = new ContactCommandDTO('');
        $logger = new FileLogger(__DIR__.'/contact.log');
        $adapter = new ContactCommandAdapter($dealId, $repo);
        $adapter->handle($dto, $logger);
        $messages = $repo->getMessages($dealId);
        $this->assertContains('Контакт клиента: ', $messages);
    }

    public function test_sanity_check()
    {
        $this->assertTrue(true);
    }
}
