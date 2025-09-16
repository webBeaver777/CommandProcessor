<?php

namespace Webbeaver\CommandProcessor\Laravel;

use Illuminate\Support\ServiceProvider;
use Webbeaver\CommandProcessor\Adapters\InMemoryDealRepository;
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\Handlers\AcceptedCommandHandler;
use Webbeaver\CommandProcessor\Handlers\ContactCommandHandler;
use Webbeaver\CommandProcessor\Handlers\ReasonSetCommandHandler;
use Webbeaver\CommandProcessor\Handlers\ReasonShowCommandHandler;

class CommandProcessorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Регистрируем CommandProcessor как singleton в контейнере Laravel
        $this->app->singleton(CommandProcessor::class, function ($app) {
            // Пока что используем InMemoryDealRepository (для тестов)
            // Позже легко заменить на EloquentDealRepository
            $repo = new InMemoryDealRepository;

            // Берем встроенный Laravel-логгер (обертка над Monolog)
            $logger = $app['log'];

            // Создаём основной процессор команд
            $processor = new CommandProcessor($repo, $logger);

            // Регистрируем дефолтные хэндлеры
            $processor->registerHandler(
                AcceptedCommandHandler::commandName(),
                new AcceptedCommandHandler($repo)
            );
            $processor->registerHandler(
                ContactCommandHandler::commandName(),
                new ContactCommandHandler($repo)
            );
            $processor->registerHandler(
                ReasonSetCommandHandler::commandName(),
                new ReasonSetCommandHandler($repo)
            );
            $processor->registerHandler(
                ReasonShowCommandHandler::commandName(),
                new ReasonShowCommandHandler($repo)
            );

            return $processor;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webbeaver\CommandProcessor\Laravel\Commands\CopyCommandProcessorTests::class,
                \Webbeaver\CommandProcessor\Laravel\CommandProcessorCommand::class,
            ]);
        }
    }
}
