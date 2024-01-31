# Présentation

## Objectifs
Ce projet vise à développer une application WEB permettant à des utilisateurs de réserver des séances dans un cinéma.

## Contexte
Le contexte proposé est le suivant:
- Le cinéma dispose de 5 salles de projection.
- Dans chaque salle, des séances de projection sont proposées.
- Une séance concerne un film (le film est alors à l’affiche).
- Un spectateur devra pouvoir réserver des places pour une séance. En fonction de son profil, il pourra prétendre à des tarifs réduits.

## Organisation des données
### Salle
- un id
- un nom
- un nombre de places

### Séance
- un id
- une date de projection (date + heure)
- un tarif normal
- un tarif réduit
  Une séance concerne la projection d’un film dans une salle.

### Film
- un id
- un titre
- une durée (en mn)

### Utilisateur
Un utilisateur représente un visiteur du site. Il peut être anonyme ou posséder un compte sur le site. Dans ce dernier cas, on distinguera 2 rôles: inscrit (ROLE_USER) ou abonné (ROLE_ABONNE).
Il faudra également prévoir un rôle administrateur (ROLE_ADMIN) afin de gérer les données du cinéma.
- un id
- un username (email)
- un mot de passe
- des rôles

### Réservation
Un utilisateur peut réserver des places pour une séance (UNE SEULE SEANCE). En fonction de son rôle, il pourra bénéficier d’un tarif réduit.
- un id
- un nombre de places à réserver
- une date de réservation (date + heure)
- un montant total

## Fonctionnalités
Afin de se limiter, les données devront être insérées en amont dans la base de données. Les fonctionnalités principales attendues sont les suivantes:

- se créer un compte utilisateur
- s’abonner
- se connecter
- lister les films à l’affiche
- lister un film (avec ses séances de projection)
- Réserver une séance
- Afficher des statistiques
    - calculer le nombre de places sur une période donnée
    - calculer le nombre de places pour un film
      D’autres fonctionnalités pourront être définies ultérieurement!

## Organisation technique
Les fonctionnalités attendues devront être exposées par une API REST avec le Framework Symfony.
L’API REST devra se connecter à une base de données MYSQL/MariaDB dans laquelle l’ensemble des données devront être stockées.
Le site WEB sera développé également avec le Framework Symfony. Il devra utiliser (consommer) l’API REST afin de proposer aux utilisateurs les fonctionnalités attendues.
Vous utiliserez le système de contrôle de version Git afin de versionner votre code et le service d'hébergement en ligne GitHub pour maintenir une sauvegarde de votre code.
