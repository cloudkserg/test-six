<?php

namespace App\Command;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WorkCommand extends Command
{
    protected static $defaultName = 'WorkCommand';
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);

//        $user = new User();
//        $user->setUsername('super-puper1');
//        $user->setEmail('super-puper1@mail.ru');
//        $user->setPlainPassword('abba');
//        $user->setFacebookLink('from facebook');
//
//        $this->em->persist($user);
//        $message = new Message();
//        $message->setText('la la la');
//        $message->setUser($user);
//        $this->em->persist($message);
//
//        $this->em->flush();
//
//        $users = $this->em->createQuery('SELECT * FROM User');
//        $messages = $this->em->createQuery('SELECT * FROM Message');

        $user = $this->em->createQuery(
            'SELECT u FROM App\Entity\User u ORDER BY u.id ASC')
            ->setMaxResults(1)
            ->getSingleResult();
        /**
         * @var User $user
         */
        $otherUsers = $this->em->createQuery(
            'SELECT u FROM \App\Entity\User u WHERE u.id <>' . $user->getId())->getResult();

        foreach ($otherUsers as $otherUser) {
            $user->addMyFriend($otherUser);
        }
        $this->em->persist($user);
        $this->em->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
