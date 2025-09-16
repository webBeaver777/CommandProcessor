<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\DTO\Deal;
use Webbeaver\CommandProcessor\Handlers\AcceptedCommandHandler;

class AcceptedCommandHandlerTest extends TestCase
{
    public function test_accepted_command()
    {
        $repo = new InMemoryDealRepository;
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $processor = new CommandProcessor($repo, $logger);
        $processor->registerHandler(
            new AcceptedCommandHandler($repo)
        );

        $deal = new Deal(42);
        $repo->saveDeal($deal);
        $context = new \Webbeaver\CommandProcessor\DTO\CommandContext(['deal' => $deal]);
        $processor->process('/принято 500 офис', $context);

        $this->assertEquals(500, $repo->getProperty(42, 14));
        $this->assertEquals('офис', $repo->getProperty(42, 15));
    }
}
