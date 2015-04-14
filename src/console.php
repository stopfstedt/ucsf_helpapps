<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);

// ip verify
$console
    ->register('ip-verify')
    ->setDefinition(array(
        new InputOption('ip', null, InputOption::VALUE_REQUIRED, 'A valid IP address.')
    ))
    ->setDescription('Verifies if a given IP address is within the UCSF IP range, and returns the location that this address resolves to.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $ipAddress = $input->getOption('ip');
        $verifier = $app['ip_net_verifier'];
        $location = $verifier->getLocation($ipAddress) ?: 'Unknown';
        $output->writeln('Location: ' . $location);
    })
;

return $console;

