<?php

namespace Fbsg\SugarCLI;

class Application extends \Symfony\Component\Console\Application
{
    /**
     * @param array $commands
     *
     * @throws \RuntimeException
     */
    public function addFromArray(array $commands)
    {
        foreach ($commands as $command) {
            if (class_exists($command)) {
                $this->add(new $command);
            } else {
                throw new \RuntimeException(sprintf('The class %s does not exist', $command));
            }
        }
    }
}
