# Audit du dépôt

## Résumé

L'application possède davantage de valeur technique que ne le montrait son
README initial : trois parcours de rôle, un catalogue complet, un panier,
un workflow de modération, un back-office et deux technologies de persistance.
Le principal problème était la découvrabilité, suivi par la reproductibilité
de l'installation et l'explicitation des limites de sécurité.

## Points forts observés

- périmètre fonctionnel cohérent de marketplace ;
- séparation claire entre front-office et administration ;
- utilisation de PDO et de requêtes préparées dans les parcours principaux ;
- hachage moderne des mots de passe ;
- modèle relationnel avec clés étrangères ;
- journalisation d'activité dans MongoDB ;
- ressources et données de démonstration suffisantes pour une présentation
  locale.

## Écarts de présentation identifiés

- README limité à une phrase ;
- application complète conservée sur `master`, alors que `main` est la branche
  par défaut et ne contient qu'un README minimal ;
- absence d'exemple `.env` et de procédure d'installation ;
- architecture et responsabilités des dossiers non documentées ;
- fonctionnalités non regroupées par rôle ;
- absence de notes de sécurité, roadmap et politique pour les comptes de
  démonstration ;
- absence de captures reproductibles et anonymisées ;
- anciens jetons de connexion persistante présents dans le dump SQL.

L'examen de la documentation de soutenance a ensuite confirmé l'existence de
wireframes, diagrammes UML, tests manuels, captures des parcours et d'un
déploiement Hostinger. Il a également montré que certaines affirmations de
sécurité documentées ne sont pas entièrement vérifiables dans la version du code
actuellement publiée, notamment le durcissement des cookies et la limitation des
tentatives.

## Améliorations réalisées

| Modification | Intention |
|---|---|
| Refonte bilingue du README | Donner une compréhension du projet en moins d'une minute |
| Tableau des fonctionnalités | Rendre visible le périmètre pour chaque rôle |
| Diagramme et document d'architecture | Expliquer les flux PHP, MySQL et MongoDB |
| Guide d'installation | Rendre le démarrage local reproductible |
| `.env.example` | Documenter la configuration sans publier de secret |
| Notes de sécurité | Montrer une évaluation lucide de l'état du projet |
| Roadmap | Transformer les limites en plan d'évolution crédible |
| Nettoyage des anciens jetons SQL | Éviter de redistribuer des secrets persistants |
| Durcissement de `.gitignore` | Prévenir l'ajout accidentel de secrets et fichiers locaux |
| Étude de cas | Valoriser l'analyse, la conception, les tests et le déploiement |
| Captures authentiques | Montrer catalogue, panier, publication et administration |

Le code applicatif a été préservé. Les seules données modifiées sont les anciens
jetons de connexion persistante du dump SQL, volontairement vidés.

## Recommandation GitHub

Après validation locale, la branche contenant l'application devrait devenir la
branche par défaut, ou être fusionnée proprement dans `main`. La page GitHub
devrait ensuite recevoir :

- une description courte : « Marketplace PHP avec espace membre, panier,
  modération, administration, MySQL et MongoDB » ;
- les sujets `php`, `mysql`, `mongodb`, `bootstrap`, `ecommerce`,
  `portfolio-project` ;
- une image sociale issue d'une capture anonymisée ;
- une version taguée après validation de l'installation.

## Prochaine preuve de qualité

La meilleure amélioration suivante est un environnement Docker accompagné de
tests de fumée. Il permettra de générer des captures fiables, de réduire le temps
d'installation et de démontrer immédiatement les parcours membre et
administrateur.
