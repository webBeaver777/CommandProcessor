<?php

namespace Webbeaver\CommandProcessor\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Webbeaver\CommandProcessor\Adapters\AcceptedCommandAdapter;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Core\FileLogger;
use Webbeaver\CommandProcessor\DTO\AcceptedCommandDTO;
use Webbeaver\CommandProcessor\DTO\Deal;

class AcceptedCommandHandlerTest extends TestCase
{
    public function test_accepted_command()
    {
        $repo = new InMemoryDealRepository;
        $dealId = 42;
        $deal = new Deal($dealId);
        $repo->saveDeal($deal);
        $dto = new AcceptedCommandDTO('500', 'офис');
        $logger = new FileLogger(__DIR__.'/accepted.log');
        $adapter = new AcceptedCommandAdapter($dealId, $repo);
        $adapter->handle($dto, $logger);
        $this->assertEquals('500', $repo->getProperty($dealId, 14));
        $this->assertEquals('офис', $repo->getProperty($dealId, 15));
    }
}
