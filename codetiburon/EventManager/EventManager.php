<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Observer Pattern implementation
 */
namespace CodeTiburon\EventManager;

class EventManager implements EventManagerInterface
{
    /**
     * @var array
     */
    protected $subscribers = [];

    /**
     * Subscribe a listener
     *
     * @param string $event
     * @param Callable $callback
     * @return $this
     */
    public function subscribe($event, $callback)
    {
        if (is_callable($callback)) {
            throw new \InvalidArgumentException('$callback is not Callable');
        }

        $subscribers[$event] = $callback;
        return $this;
    }

    /**
     * Trigger an event
     *
     * @param string $event
     * @param ...$params
     * @return bool
     */
    public function trigger($event, ...$params)
    {
        if (empty($this->subscribers[$event])) {
            return false;
        }

        foreach ($this->subscribers[$event] as $callback) {
            $callback(...$params);
        }

        return true;
    }
}