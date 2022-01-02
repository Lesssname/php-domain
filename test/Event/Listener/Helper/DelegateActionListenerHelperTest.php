<?php
declare(strict_types=1);

namespace LessDomainTest\Event\Listener\Helper;

use LessDomain\Event\Event;
use LessDomain\Event\Listener\Helper\DelegateActionListenerHelper;
use LessDomain\Event\Property\Action;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDomain\Event\Listener\Helper\DelegateActionListenerHelper
 */
final class DelegateActionListenerHelperTest extends TestCase
{
    public function testSubHandle(): void
    {
        $action = new Action('foo');

        $event = $this->createMock(Event::class);
        $event
            ->method('getAction')
            ->willReturn($action);

        $class = new class ($this, $event) {
            use DelegateActionListenerHelper;

            public function __construct(private TestCase $tester, private Event $event)
            {}

            private function handleFoo(Event $event)
            {
                $this->tester::assertSame($this->event, $event);
            }
        };

        $class->handle($event);
    }
}
