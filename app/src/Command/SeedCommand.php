<?php

namespace App\Command;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed',
    description: 'App seed',
)]
class SeedCommand extends Command
{
    private const COUNT = 10;

    private EntityManagerInterface $entityManager;


    public function __construct( EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= self::COUNT ; ++$i)
        {
            $participant = new Participant();
            $participant->setName($faker->name('female'));
            $participant->setAdress($faker->address());
            $participant->setBabyAge($faker->randomDigitNotNull());
            $participant->setKnowSensitive($faker->boolean());
            $participant->setPhone(0550000000);
            $participant->setSocial($faker->url());

            $this->entityManager->persist($participant);
            $this->entityManager->flush();
        }
        $io->success('Seed Ok');

        return Command::SUCCESS;
    }
}
