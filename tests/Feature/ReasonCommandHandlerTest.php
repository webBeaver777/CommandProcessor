<?php
namespace Webbeaver\CommandProcessor\Tests\Feature;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\DTO\Deal;
use Webbeaver\CommandProcessor\Handlers\ReasonSetCommandHandler;
use Webbeaver\CommandProcessor\Handlers\ReasonShowCommandHandler;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class ReasonCommandHandlerTest extends TestCase
{
    public function test_reason_set_command()
    {
        $repo = new InMemoryDealRepository;
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));
        $processor = new CommandProcessor($repo, $logger);
        $processor->registerHandler(new ReasonSetCommandHandler($repo));
        $deal = new Deal(222);
        $repo->saveDeal($deal);
        $context = new CommandContext(['deal' => $deal]);
        $processor->process('/причина_закрытия удалена транзакция', $context);
        $this->assertEquals('удалена транзакция', $repo->getProperty(222, 222));
    }

    public function test_reason_show_command()
    {
        $repo = new InMemoryDealRepository;
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler('php://stdout'));
        $processor = new CommandProcessor($repo, $logger);
        $processor->registerHandler(new ReasonShowCommandHandler($repo));
        $deal = new Deal(223);
        $repo->saveDeal($deal);
        $repo->setProperty(223, 222, 'удалена транзакция');
        $context = new CommandContext(['deal' => $deal]);
        $processor->process('/причина', $context);
        $messages = $repo->getMessages(223);
        $this->assertContains('Причина закрытия: удалена транзакция', $messages);
    }
}

