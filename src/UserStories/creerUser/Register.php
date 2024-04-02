<?php
namespace App\UserStories\creerUser;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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


    public function execute(RegisterRequest $requete): bool
    {

       /* // Valider les données en entrées (de la requête)
        $problemes = $this->validateur->validate($requete);

        if (count($problemes) > 0) {
            $messagesErreur = [];
            foreach ($problemes as $probleme) {
                $messagesErreur[] =  $probleme->getMessage();
            }

            throw new \Exception(implode("<br\>", $messagesErreur));
        }


        $adherent = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $requete->email]);
        if ($adherent != null) {
            throw new \Exception("L'email est déjà attribué à un adherent");
        }

        // Générer un numéro d'adhérent au format AD-999999
        $numeroAdherent = $this->generateurNumeroAdherent->generer();

        $getNumeroAdherent = $this->entityManager->getRepository(Adherent::class)->findOneBy(['numero_adherent' => $numeroAdherent]);
        if ($getNumeroAdherent != null) {
            throw new \Exception("Le numero adherent existe déjà");
        }

        // Créer l'adhérent
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($numeroAdherent);
        $adherent->setEmail($requete->email);
        $adherent->setNom($requete->nom);
        $adherent->setPrenom($requete->prenom);
        $adherent->setDateAdhesion(new \DateTime());

        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        return true;
    }*/






}