<?php

namespace App\Command;

use App\Entity\Cite\CiResponsable;
use App\Entity\Cite\CiTeacher;
use App\Entity\User;
use App\Service\DatabaseService;
use App\Service\SanitizeData;
use App\Service\Synchro\SyncData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CiteSynchroUserCommand extends Command
{
    protected static $defaultName = 'cite:synchro:user';
    protected static $defaultDescription = 'Synchronise user data';
    protected $em;
    protected $emWindev;
    protected $syncData;
    protected $sanitizeData;

    public function __construct(DatabaseService $databaseService, SyncData $syncData, SanitizeData $sanitizeData)
    {
        parent::__construct();

        $this->em       = $databaseService->getEm();
        $this->emWindev = $databaseService->getEmWindev();
        $this->syncData = $syncData;
        $this->sanitizeData = $sanitizeData;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //init random password for new user
        $password = password_hash(uniqid(), PASSWORD_ARGON2I);

        $users = $this->em->getRepository(User::class)->findBy(['fromWeb' => false]);
        foreach($users as $user){
            $user->setIsSync(false);
        }
        $this->em->flush();

        $this->startProcess($io, $password, $output, CiResponsable::class, [], 'ROLE_RESPONSABLE');
        $io->newLine();$io->newLine();
        $this->startProcess($io, $password, $output, CiTeacher::class, [], 'ROLE_TEACHER');

        return Command::SUCCESS;
    }

    protected function startProcess($io, $password, $output, $classe, $params, $role)
    {
        $io->title(sprintf('Création des utilisateurs [%s]', $role));

        $obj = $this->em->getRepository($classe)->findBy($params);

        $this->process($io, $output, $password, $obj, $role);
        $this->deleteNotUse($io, $role);
    }

    protected function process($io, $output, $password, $data, $type)
    {
        //init progressbar + compteur
        $progressBar = new ProgressBar($output, count($data));
        $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% 🏁");
        $progressBar->setOverwrite(true);
        $compteurCreated = 0;
        $compteurUpdated = 0;
        $compteurNotChanged = 0;

        // get all id's already set, to eliminate user already created
        $elementsId = [];
        foreach ($data as $item) {
            array_push($elementsId, $item->getOldId());
        }
        $users = $this->em->getRepository(User::class)->findBy([
            'who' => $elementsId,
            'fromWeb' => false
        ]);
        $usersFromWeb = $this->em->getRepository(User::class)->findBy([
            'fromWeb' => true
        ]);

        $duplicate = [];
        foreach ($data as $item) {
            $prefix = false;

            if ($type == 'ROLE_RESPONSABLE') {
                if(count($item->getEleves()) != 0){
                    $prefix = 'r-';
                }
            } else if ($type == 'ROLE_TEACHER') {
                $prefix = 'p-';
            }

            //create user with username
            if ($prefix != false) {

                $username = $this->createUsername($item, $prefix);

                if($username != false){
                    $i = 1;
                    while (in_array($username, $duplicate)) {
                        $username = $username . $i;
                        $i++;
                    }

                    if($item->getLastname() == "BALAKIAN" && $type == 'ROLE_TEACHER'){
                        $username = "p-bapi";
                    }

                    array_push($duplicate, $username);

                    $retour = $this->createUser($usersFromWeb, $users, $item, $password, $username, [$type], $item->getOldId());

                    $new = $retour[0];
                    if($retour[1] == 0){
                        $compteurCreated++;
                    }elseif($retour[1] == 1){
                        $compteurUpdated++;
                    }else{
                        $compteurNotChanged++;
                    }

                    $this->em->persist($new);
                    $progressBar->advance();
                }
            }
        }

        $progressBar->finish();

        $io->newLine();$io->newLine();
        $io->text(sprintf("%s : ", $type));
        $io->text(sprintf("%d comptes créés", $compteurCreated));
        $io->text(sprintf("%d comptes mis à jour", $compteurUpdated));
        $io->text(sprintf("%d comptes inchangés", $compteurNotChanged));
    }

    protected function createUsername($item, $prefix): string
    {
        if($prefix == "p-"){
            $firstname = $item->getFirstname() ? $this->sanitizeData->fullSanitize($item->getFirstname()) : null;
            $lastname = $item->getLastname() ? $this->sanitizeData->fullSanitize($item->getLastname()) : null;

            $firstname = $firstname != null ? substr($firstname, 0, 2) : "";
            $lastname = $lastname != null ? substr($lastname, 0, 2) : "";

            $name = $lastname . $firstname;

            if ($name == "") {
                $name = $item->getId();
            }
        }else{
            $longueur = strlen($item->getOldId());
            if($longueur < 6){
                $zeroToAdd = 6 - $longueur;
                $name = str_repeat("0", $zeroToAdd);
                $name .= $item->getOldId();
            }else{
                $name = $item->getOldId();
            }
        }

        return  $prefix . $name;
    }

    protected function createUser($usersFromWeb, $users, $item, $password, $username, $role, $who): array
    {
        $case = 0;
        $toSet = true; $removeWeb = false;
        $user = new User();

        if($item->getLastname() == "BALAKIAN" && $role[0] == "ROLE_TEACHER"){
            $username = "p-bapi";
            foreach($users as $existe){
                if($existe->getUsername() == $username){
                    $user = $existe;
                    $toSet = false;
                    $case = 2;
                }
            }
        }else{
            /** @var User $existe */
            foreach($users as $existe){
                if($existe->getWho() == $who && in_array($role[0], $existe->getRoles())) {
                    $user = $existe;
                    $case = 1;
                    if($existe->getUsername() == $username) {
                        $toSet = false;
                        $case = 2;
                    }
                }
            }

            foreach ($usersFromWeb as $existe){
                if($existe->getUsername() == $username && in_array($role[0], $existe->getRoles())){
                    $user = $existe;
                    $case = 2;
                    $removeWeb = true;
                }
            }
        }

        if($toSet){
            $firstname = $item->getFirstname() ? $this->sanitizeData->fullSanitize($item->getFirstname()) : $username;
            $lastname = $item->getLastname() ? $this->sanitizeData->fullSanitize($item->getLastname()) : $username;

            $user->setUsername($username);
            $user->setFirstname(ucfirst($firstname));
            $user->setLastname(mb_strtoupper($lastname));
            $user->setEmail($item->getEmail() ? $item->getEmail() : null);
            $user->setRoles($role);
            $user->setWho($who);
            $user->setPassword($password);
            $user->setFromWeb(false);
            $user->setIsSync(true);

            if($removeWeb){
                $user->setFromWeb(false);
            }
        }else{
            $user->setIsSync(true);
        }

        $user->setFullAncien(false);

        return [$user, $case];
    }

    protected function deleteNotUse($io, $role)
    {
        $all = $this->em->getRepository(User::class)->findBy(['fromWeb' => false]);
        $users = [];
        /** @var User $user */
        foreach($all as $user){
            if(in_array($role, $user->getRoles()) && $user->getIsSync() == false){
                array_push($users, $user);
            }
        }

        $count = 0;
        foreach($users as $user){
            $this->em->remove($user);
            $count++;
        }

        $this->em->flush();

        $io->text(sprintf("%d comptes supprimés", $count));
    }
}
