<?php

use PHPUnit\Framework\TestCase;
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\Handlers\AcceptedCommandHandler;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\DTO\Deal;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class AcceptedCommandHandlerTest extends TestCase
{
    public function testAcceptedCommand()
    {
        $repo = new InMemoryDealRepository();
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $processor = new CommandProcessor($repo, $logger);
        $processor->registerHandler(
            AcceptedCommandHandler::commandName(),
            new AcceptedCommandHandler($repo)
        );

        $deal = new Deal(42);
        $repo->saveDeal($deal);

        $processor->process('/принято 500 офис', 42);

        $this->assertEquals(500, $repo->getProperty(42, 14));
        $this->assertEquals('офис', $repo->getProperty(42, 15));
    }
}
