<?php
namespace CommandProcessor\Tests;

use PHPUnit\Framework\TestCase;
use CommandProcessor\Core\CommandProcessor;
use CommandProcessor\Handlers\ContactCommandHandler;
use CommandProcessor\DTO\CommandContext;

class CommandProcessorTest extends TestCase
{
    public function testContactCommandHandled()
    {
        $processor = new CommandProcessor();
        $processor->registerHandler(new ContactCommandHandler());
        $context = new CommandContext();
        $result = $processor->process('/contact', $context);
        $this->assertEquals('Контактная информация: info@example.com', $result);
    }

    public function testUnknownCommandThrows()
    {
        $this->expectException(\RuntimeException::class);
        $processor = new CommandProcessor();
        $processor->registerHandler(new ContactCommandHandler());
        $context = new CommandContext();
        $processor->process('/unknown', $context);
    }
}
