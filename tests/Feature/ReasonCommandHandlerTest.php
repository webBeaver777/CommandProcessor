<?php

namespace Webbeaver\CommandProcessor\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Adapters\ReasonClosedCommandAdapter;
use Webbeaver\CommandProcessor\Adapters\ReasonCommandAdapter;
use Webbeaver\CommandProcessor\Core\FileLogger;
use Webbeaver\CommandProcessor\DTO\Deal;
use Webbeaver\CommandProcessor\DTO\ReasonClosedCommandDTO;
use Webbeaver\CommandProcessor\DTO\ReasonCommandDTO;

class ReasonCommandHandlerTest extends TestCase
{
    public function test_reason_set_command()
    {
        $repo = new InMemoryDealRepository;
        $dealId = 222;
        $deal = new Deal($dealId);
        $repo->saveDeal($deal);
        $dto = new ReasonClosedCommandDTO('удалена транзакция');
        $logger = new FileLogger(__DIR__.'/reason.log');
        $adapter = new ReasonClosedCommandAdapter($dealId, $repo);
        $adapter->handle($dto, $logger);
        $this->assertEquals('удалена транзакция', $repo->getProperty($dealId, 222));
    }

    public function test_reason_show_command()
    {
        $repo = new InMemoryDealRepository;
        $dealId = 223;
        $deal = new Deal($dealId);
        $repo->saveDeal($deal);
        $repo->setProperty($dealId, 222, 'удалена транзакция');
        $dto = new ReasonCommandDTO;
        $logger = new FileLogger(__DIR__.'/reason.log');
        $adapter = new ReasonCommandAdapter($dealId, $repo);
        $adapter->handle($dto, $logger);
        $messages = $repo->getMessages($dealId);
        $this->assertContains('Причина закрытия: удалена транзакция', $messages);
    }
}
