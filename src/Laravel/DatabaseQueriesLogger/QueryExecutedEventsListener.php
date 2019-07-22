<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Laravel\DatabaseQueriesLogger;

use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Events\QueryExecuted;

class QueryExecutedEventsListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Service constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get logging level.
     *
     * @return string
     */
    public function loggingLevel(): string
    {
        return (string) env('DATABASE_QUERIES_LOGGING_LEVEL', 'debug');
    }

    /**
     * Handle the event.
     *
     * @param QueryExecuted $event
     *
     * @return void
     */
    public function handle(QueryExecuted $event): void
    {
        try {
            $bindings   = (array) $event->bindings;
            $duration   = $event->time;
            $connection = $event->connection->getName();
            $data       = \compact('bindings', 'duration', 'connection');

            // Format binding data
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof DateTime) {
                    $bindings[$i] = $binding->format('Y-m-d H:i:s');
                } else {
                    if (\is_string($binding)) {
                        $bindings[$i] = "'$binding'";
                    }
                }
            }

            // Insert bindings into query
            $query_string = \str_replace(['%', '?'], ['%%', '%s'], $event->sql);
            $query_string = \vsprintf($query_string, $bindings);

            $this->logger->log($this->loggingLevel(), "Database query [$query_string]", $data);
        } catch (Exception $e) {
            $this->logger->error("Cannot log database query: {$e->getMessage()}", ['exception' => $e]);
        }
    }
}
