<?php
namespace App\UserStories\creerUser;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class Register {

    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validateur;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validateur
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validateur)
    {
        $this->entityManager = $entityManager;
        $this->validateur = $validateur;
    }


    /**
     * @throws \Exception
     */
    public function execute(RegisterRequest $requete, UserPasswordHasherInterface $passwordHasher): bool
    {

        // Valider les données en entrées (de la requête)
        $problemes = $this->validateur->validate($requete);

        if (count($problemes) > 0) {
            $messagesErreur = [];
            foreach ($problemes as $probleme) {
                $messagesErreur[] =  $probleme->getMessage();
            }

            throw new \Exception(implode("<br\>", $messagesErreur));
        }


        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $requete->email]);
        if ($user != null) {
            throw new \Exception("L'email est déjà attribué à un utilisateur");
        }

        if ($requete->password != $requete->confirmPassword) {
            throw new \Exception("Le deux mots de passe sont pas identiques");
        }


        // Créer l'utilisateur
        $user = new User();
        $user->setEmail($requete->email);
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $requete->password
        );


        $user->setPassword($hashedPassword);

        // Enregistrer l'utilisateur dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }






}