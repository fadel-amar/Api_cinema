<?php

namespace App\UserStories\creerUser;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest {


    #[Assert\NotBlank(message: "L'email est obligatoire")]
    public string $email;

    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{6,}$/",
        message: "Le mot de passe doit obligatoirement contenir au moins 1 Majuscule ou minuscule et 1 chiffre et 6 caractères"
    )]
    public string $password;

    #[Assert\NotBlank(message: "Veuillez confirmer le mot de passe")]
    public string $confirmPassword;

    /**
     * @param string $email
     * @param string $password
     * @param string $confirmPassword
     */
    public function __construct(string $email, string $password, string $confirmPassword)
    {
        $this->email = $email;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }




}
