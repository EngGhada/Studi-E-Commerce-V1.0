# Sécurité

## Statut

E-Print est un projet pédagogique. Il ne doit pas être exposé sur Internet sans
une revue de sécurité et les mesures de durcissement ci-dessous.

## Mesures déjà présentes

- mots de passe générés avec `password_hash` et contrôlés avec
  `password_verify` ;
- accès MySQL via PDO ;
- requêtes préparées pour de nombreux paramètres utilisateurs ;
- secrets de connexion chargés depuis `.env`, ignoré par Git ;
- séparation des droits membre/administrateur par rôle ;
- approbation des annonces et commentaires côté administration.

## Priorités avant production

1. Ajouter des jetons CSRF à toutes les opérations qui modifient l'état.
2. Configurer les cookies avec `Secure`, `HttpOnly` et `SameSite`.
3. Hacher les jetons « se souvenir de moi » en base, limiter leur durée,
   effectuer une rotation après usage et prévoir leur révocation.
4. Régénérer l'identifiant de session après authentification.
5. Centraliser la validation des entrées et l'échappement au moment de
   l'affichage.
6. Restreindre strictement les téléversements par type MIME réel, taille,
   extension générée et stockage hors racine publique.
7. Remplacer les erreurs détaillées de connexion par une journalisation serveur
   et un message générique côté utilisateur.
8. Vérifier chaque autorisation sur le serveur, notamment la propriété des
   annonces, profils, commentaires et lignes de panier.
9. Ajouter limitation de débit, politique de mot de passe et verrouillage
   progressif des tentatives.
10. Établir une politique de dépendances et exécuter régulièrement
    `composer audit`.

## Données de démonstration

Le dump SQL historique contient des profils fictifs, des hachages de mots de
passe et quelques anciens jetons persistants. Considérez toutes ces valeurs
comme compromises et réservées à un environnement local jetable. Avant une
publication de démonstration :

- remplacer les profils par des identités manifestement fictives ;
- supprimer tous les jetons persistants ;
- générer des mots de passe temporaires uniques ;
- purger ou anonymiser les journaux MongoDB ;
- vérifier les droits d'utilisation des images produit.

## Signalement

Pour un dépôt de portfolio, utilisez les issues GitHub sans inclure de secret,
de donnée personnelle, d'identifiant ou de preuve d'exploitation publique.
