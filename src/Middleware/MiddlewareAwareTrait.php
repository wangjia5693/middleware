<?php
namespace Middleware;

use RuntimeException;
use SplStack;
use SplDoublyLinkedList;
use UnexpectedValueException;

/**
 * 中间件
 */
trait MiddlewareAwareTrait
{
    /**
     * 中间件堆栈
     */
    protected $stack;

    /**
     * 堆栈锁
     */
    protected $middlewareLock = false;

    /**
     * 增加中间件
     */
    protected function addMiddleware(callable $callable)
    {
        if ($this->middlewareLock) {
            throw new RuntimeException('Middleware can’t be added once the stack is dequeuing');
        }

        if (is_null($this->stack)) {
            $this->seedMiddlewareStack();
        }
        $next = $this->stack->top();
        $this->stack[] = function ( $req) use ($callable, $next) {
            $result = call_user_func($callable, $req, $next);
            return $result;
        };

        return $this;
    }

    /**
     * 插入堆栈第一条内容
     */
    protected function seedMiddlewareStack(callable $kernel = null)
    {
        if (!is_null($this->stack)) {
            throw new RuntimeException('MiddlewareStack can only be seeded once.');
        }
        if ($kernel === null) {
            $kernel = $this;
        }
        $this->stack = new SplStack;
        $this->stack->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP);
        $this->stack[] = $kernel;
    }

    /**
     * 调用堆栈内容
     */
    public function callMiddlewareStack( $req)
    {
        if (is_null($this->stack)) {
            $this->seedMiddlewareStack();
        }
        /** @var callable $start */
        $start = $this->stack->top();

        $this->middlewareLock = true;
        $resp = $start($req);
        $this->middlewareLock = false;
        return $resp;
    }
}
