# Spec 05 - Dashboard et archives

## Objectif

Ajouter une page d'accueil utile pour suivre la progression globale et retrouver les concepts archives.

## User stories couvertes

- Bonus: dashboard de progression
- Bonus: concepts archives avec restauration

## Ce que je veux

- Une page dashboard apres connexion.
- Le nombre de concepts par statut.
- Le domaine le mieux maitrise.
- Le domaine le plus a revoir.
- Un lien visible vers les concepts archives.
- Une page archives listant les concepts soft deleted.
- Une action de restauration par concept.

## Ce que je ne veux pas

- Pas de dashboard surcharge.
- Pas de statistiques calculees avec des boucles lourdes en PHP.
- Pas de concepts archives melanges avec la liste principale.
- Pas de restauration sans verification que le domaine appartient a l'utilisateur.

## Plan agent

1. Creer `DashboardController`.
2. Charger les statistiques de l'utilisateur connecte uniquement.
3. Calculer les compteurs par statut avec des agregations SQL.
4. Identifier le domaine le mieux maitrise via les compteurs de concepts `mastered`.
5. Identifier le domaine le plus a revoir via les compteurs de concepts `to_review`.
6. Creer une vue dashboard simple et lisible.
7. Creer une action archives si elle n'existe pas dans `ConceptController`.

## Resultat build attendu

- Le dashboard resume la preparation en quelques indicateurs.
- Les statistiques ignorent les concepts archives sauf indication contraire.
- La page archives affiche uniquement les concepts de l'utilisateur connecte.
- La restauration fonctionne et redirige vers la liste du domaine.

## Verification manuelle

- Creer plusieurs domaines et concepts avec statuts differents.
- Verifier les compteurs du dashboard.
- Archiver un concept et confirmer qu'il quitte les statistiques principales.
- Restaurer le concept et confirmer qu'il revient dans les statistiques.

## Fichiers probables

- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/ConceptController.php`
- `resources/views/dashboard.blade.php`
- `resources/views/concepts/archived.blade.php`
- `routes/web.php`

## Commit associe

- `feat(dashboard): add progress dashboard with AI assistance`
