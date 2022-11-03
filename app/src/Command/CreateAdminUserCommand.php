<?php

namespace App\Command;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[AsCommand(
    name: 'app:create:admin',
    description: 'Create App Admim',
)]

class CreateAdminUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;

    public function __construct( EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $question1 = new Question('Please enter username: ');
        $question1->setValidator(function ($value) {
            $errors = $this->validator->validate($value, new Email());
            if ( count($errors) > 0 ) {
                throw new \Exception((string) $errors);
            }
            return $value;
        });

        $username = $helper->ask($input, $output, $question1);

        $question2 = new Question('Please enter password: ');
        $question2->setHidden(true);

        $question2->setValidator(function ($value) {
            $errors = $this->validator->validate($value, new Length( min: 8));
            if ( count($errors) > 0 ) {
                throw new \Exception(('Invalid Password'));
            }
            return $value;
        });

        $password = $helper->ask($input, $output, $question2);

        $user = new User();
        $user->setEmail($username);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setRoles(['ROLE_ADMIN']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('User Admin Created');

        return Command::SUCCESS;
    }
}
