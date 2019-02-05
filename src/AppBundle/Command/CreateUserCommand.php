<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'user:create';
    /**
     * @var RegistryInterface
     */
    private $doctrine;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    protected function configure()
    {
        $this
            ->addOption('load', 'l', InputOption::VALUE_NONE,
                'Zaladuj użytkownikow', null)
            ;
    }

    public function __construct(RegistryInterface $doctrine, UserPasswordEncoderInterface $encoder)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {


        if($input->getOption('load'))
        {
//            $sql = "SET FOREIGN_KEY_CHECKS=0;TRUNCATE users;SET FOREIGN_KEY_CHECKS=1";
//            $this->doctrine->getConnection()->exec($sql);

            $manager = $this->doctrine->getManager();
            $users = $this->getUsers();

            foreach ($users as $user) {
                $User = new User();

                $password = $this->encoder->encodePassword($User, $user['password']);
                $User->setName($user['name'])
                    ->setUsername($user['username'])
                    ->setRoles($user['roles'])
                    ->setPosition($user['position'])
                    ->setPassword($password);

                $manager->persist($User);
            }

            $manager->flush();
            $output->writeln('Załadowano wszystkich użytkowników.');
        }


    }

    protected function getUsers(){
        return array(
//            array(
//                "name" => "SYSTEM",
//                "username" => "system",
//                "roles" => ["ROLE_ADMIN"],
//                "position" => "System",
//                "password" => null
//            ),
            array(
                "name" => "Administrator",
                "username" => "admin",
                "roles" => ["ROLE_ADMIN"],
                "position" => "Administrator",
                "password" => "taxi9191"
            ),
            array(
                "name" => "Karolizna Zych",
                "username" => "Karolina",
                "roles" => ["ROLE_USER"],
                "position" => "KASA2",
                "password" => "kacper"
            ),
            array(
                "name" => "Marcin Zych",
                "username" => "Marcin",
                "roles" => ["ROLE_USER"],
                "position" => "KASA4",
                "password" => "123"
            ),
            array(
                "name" => "Agnieszka Czajka",
                "username" => "aczajka",
                "roles" => ["ROLE_USER"],
                "position" => "KASA3",
                "password" => "Zuza"
            )
        );
    }
}
