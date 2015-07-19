<?php

class ConsoleCommandTestCase extends TestCase
{
    /**
     * @param  \Illuminate\Console\Command $command
     * @param  array $arguments
     * @return int
     */
    protected function runCommand(Illuminate\Console\Command $command, array $arguments = [])
    {
        $command->setLaravel(app());

        $input = new Symfony\Component\Console\Input\ArrayInput($arguments);
        $input->setInteractive(false);

        return $command->run($input, new Symfony\Component\Console\Output\NullOutput);
    }
}
