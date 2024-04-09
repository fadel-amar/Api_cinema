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
    public function execute(RegisterRequest $requete, UserPasswordHasherInterface $passwordHasher)
    {

        // Valider les données en entrées (de la requête)
        $problemes = $this->validateur->validate($requete);
        $errors = [];

        if (count($problemes) > 0) {
            foreach ($problemes as $probleme) {
                if(str_contains($probleme->getMessage(), "email")) {
                    $errors['email'] =  $probleme->getMessage();
                    return $errors;
                } else {
                    $errors['password'] =  $probleme->getMessage();
                    return $errors;
                }
            }
        }

        if (!filter_var($requete->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email']  = "L'email n'est pas valide";
            return $errors;
        }

        if ($requete->email) {
            $errors['email']  = "L'email est déjà attribué à un utilisateur";
            return $errors;
        }


        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $requete->email]);
        if ($user != null) {
            $errors['email']  = "L'email est déjà attribué à un utilisateur";
            return $errors;
        }

        if ($requete->password != $requete->confirmPassword) {
            $errors['password']  = "Le deux mots de passe sont pas identiques";
        }

        if(!empty($errors)) {
            return $errors;
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