<?php
declare(strict_types=1);

namespace LessDomain\Event\Publisher;

use LessDomain\Event\Event;
use LessDomain\Event\Listener\LazyContainerListener;
use LessDomain\Event\Listener\Listener;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class FifoPublisherFactory
{
    public const CONFIG_KEY = 'eventSubscriptions';

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @psalm-suppress MixedAssignment from foreach
     */
    public function __invoke(ContainerInterface $container): FifoPublisher
    {
        $config = $container->get('config');
        assert(is_array($config));
        assert(is_array($config[self::CONFIG_KEY]));

        $subscriptions = [];

        foreach ($config[self::CONFIG_KEY] as $listener => $events) {
            assert(is_string($listener) && is_subclass_of($listener, Listener::class));
            assert(is_iterable($events));

            $lazyListener = new LazyContainerListener($container, $listener);

            foreach ($events as $event) {
                assert(is_string($event) && is_subclass_of($event, Event::class));

                $subscriptions[$event][] = $lazyListener;
            }
        }

        return new FifoPublisher($subscriptions);
    }
}
