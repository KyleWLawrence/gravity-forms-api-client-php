<?php

namespace KyleWLawrence\GridPane\Console;

use KyleWLawrence\GridPane\Api\Exceptions\ApiResponseException;
use KyleWLawrence\GridPane\Api\HttpClient;
use Psy\Configuration;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('console')
            ->setDescription('Test out features of the php api client.')
            ->addArgument('bearer', InputArgument::REQUIRED);
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     *
     * @throws RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Configuration;

        $client = new HttpClient();
        $client->setAuth('bearer', ['bearer' => $input->getArgument('bearer')]);

        try {
            $data = $client->user()->getCurrent();
            $config->setStartupMessage(
                '<fg=green>Hi '.
                $data->name.
                '. An instance of HttpClient using your credentials is stored on $client variable.</>'
            );
        } catch (ApiResponseException $e) {
            $config->setStartupMessage('<fg=red>Invalid client credentials</>');
        }

        $shell = new Shell($config);
        $shell->setScopeVariables(compact('client'));
        $shell->run();
    }
}
