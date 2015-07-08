<?php

class ConsoleCommandTestCase extends TestCase
{
	/**
	 * @param string $command
	 * @param array $arguments
	 * @return null
	 */
    protected function runCommand($command, array $arguments = [])
    {
        $command->setLaravel(app());

        $input = new Symfony\Component\Console\Input\ArrayInput($arguments);
        $input->setInteractive(false);

        return $command->run($input, new Symfony\Component\Console\Output\NullOutput);
    }
}
