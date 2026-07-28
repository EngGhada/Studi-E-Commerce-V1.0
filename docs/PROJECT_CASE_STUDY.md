# Étude de cas - Studi E-Commerce

## Contexte

Ce projet individuel a été réalisé pour le titre professionnel Développeur Web
et Web Mobile de Studi. Le développement s'est déroulé du 1er août au
8 décembre 2024, soit quatre mois et huit jours.

Le besoin simulait celui d'une petite entreprise souhaitant disposer d'une
plateforme de vente et de publication d'annonces. La solution devait offrir une
navigation simple aux visiteurs, des fonctions de contribution aux membres et
un contrôle complet aux administrateurs.

## Problème à résoudre

La solution initiale ne permettait pas de centraliser efficacement :

- le catalogue et sa navigation par catégories ;
- les annonces proposées par les membres ;
- les profils, commentaires et paniers ;
- la validation des contenus ;
- la traçabilité des actions importantes.

## Démarche

### Analyse

Les besoins ont été convertis en parcours fonctionnels pour trois acteurs :
visiteur, membre et administrateur. Le travail a été organisé dans Trello avec
des phases de préparation, conception, développement, sécurité, tests et
déploiement.

### Conception

La conception inclut :

- diagrammes de cas d'utilisation ;
- diagramme de classes ;
- diagrammes de séquence pour les parcours membre et administrateur ;
- modèle conceptuel, logique et physique des données ;
- wireframes bureau et mobile.

Le modèle relationnel couvre les utilisateurs, catégories, articles,
commentaires et paniers. Une collection MongoDB séparée conserve les événements
d'activité.

### Développement

L'application a été réalisée avec PHP 8.2, PDO, MariaDB/MySQL, MongoDB,
Bootstrap 5.3, JavaScript et jQuery 3.7. Les écrans sont rendus côté serveur et
les opérations du panier utilisent des appels asynchrones pour actualiser les
quantités et le total.

### Validation et déploiement

La documentation source présente des tests d'inscription, de validation des
entrées, de connexion aux deux bases et d'affichage responsive. Une version a
été déployée sur Hostinger avec HTTPS et une base MongoDB Atlas.

Les adresses, comptes d'hébergement et paramètres de connexion ne sont
volontairement pas reproduits dans ce dépôt.

## Résultat

Le produit couvre les parcours suivants :

- découverte, recherche et filtrage du catalogue ;
- inscription et authentification ;
- profil membre et suivi des annonces ;
- création d'annonce avec validation administrative ;
- commentaires et modération ;
- panier avec ajout, retrait et modification des quantités ;
- administration des membres, catégories, articles et commentaires ;
- tableau de bord et consultation du journal d'activité.

## Compétences démontrées

- analyse et formalisation d'un besoin ;
- conception UML et modélisation de données ;
- développement PHP full-stack ;
- intégration d'une persistance SQL et NoSQL ;
- conception responsive avec Bootstrap ;
- validation, tests manuels et diagnostic ;
- configuration d'un hébergement web ;
- documentation technique et communication de projet.

## Regard critique

Le projet démontre un cycle de développement complet, mais reste une application
pédagogique procédurale. Les prochaines priorités sont les tests automatisés,
la protection CSRF, le durcissement des sessions et cookies, la validation
centralisée des téléversements et une architecture en couches.

Cette distinction entre fonctionnalités réalisées et améliorations restantes
permet d'évaluer le projet avec transparence, sans le présenter comme une
solution prête pour la production.
