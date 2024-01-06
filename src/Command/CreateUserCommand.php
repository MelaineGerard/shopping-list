<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Permet de générer un utilisateur',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = '';
        $password = '';
        $confirmPassword = '';

        while ('' === $email) {
            /** @var string $email */
            $email = $io->ask('Email de l\'utilisateur');
        }

        while ('' === $password || $password !== $confirmPassword) {
            /** @var string $password */
            $password = $io->askHidden('Mot de passe de l\'utilisateur');

            /** @var string $confirmPassword */
            $confirmPassword = $io->askHidden('Confirmez le mot de passe de l\'utilisateur');
        }

        $isAdministrator = $io->confirm('Est-ce un administrateur ?');

        $user = new User();

        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setRoles($isAdministrator ? ['ROLE_ADMIN'] : ['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Utilisateur créé avec succès !');

        return Command::SUCCESS;
    }
}
