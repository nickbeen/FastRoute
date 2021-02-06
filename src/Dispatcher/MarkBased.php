<?php
declare(strict_types=1);

namespace FastRoute\Dispatcher;

use function preg_match;

class MarkBased extends RegexBasedAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function dispatchVariableRoute(array $routeData, string $uri): array
    {
        foreach ($routeData as $data) {
            if (! preg_match($data['regex'], $uri, $matches)) {
                continue;
            }

            [$handler, $varNames] = $data['routeMap'][$matches['MARK']];

            $vars = [];
            $i = 0;
            foreach ($varNames as $varName) {
                if (is_numeric($matches[++$i])) {
                    $vars[$varName] = (int) $matches[$i];
                } else {
                    $vars[$varName] = $matches[$i];
                }
            }

            return [self::FOUND, $handler, $vars];
        }

        return [self::NOT_FOUND];
    }
}
