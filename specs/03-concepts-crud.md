# Spec 03 - CRUD Concepts

## Objectif

Permettre a l'utilisateur de documenter ses concepts techniques, filtrer sa revision et mettre a jour sa maitrise rapidement.

## User stories couvertes

- US5: Liste des concepts d'un domaine
- US6: Creer un concept
- US7: Voir le detail d'un concept
- US8: Modifier un concept
- US9: Changer le statut rapidement
- US10: Supprimer un concept
- Bonus: filtre combine statut et difficulte
- Bonus: soft deletes et restauration

## Ce que je veux

- Une liste de concepts par domaine.
- Un filtre par statut.
- Un filtre combine par statut et difficulte.
- Les labels formates `A revoir`, `En cours`, `Maitrise`, `Junior`, `Mid`, `Senior`.
- Un statut par defaut `to_review`.
- Une action rapide pour passer au statut suivant.
- Un archivage via SoftDeletes au lieu d'une suppression definitive.
- Une page des concepts archives avec restauration.

## Ce que je ne veux pas

- Pas de suppression physique pour les concepts dans la fonctionnalite principale.
- Pas de valeurs de statut non prevues.
- Pas de valeurs de difficulte non prevues.
- Pas de modification d'un concept appartenant a un autre utilisateur.
- Pas de duplication de logique de labels dans chaque vue.

## Plan agent

1. Creer migration `concepts` selon le MLD avec `deleted_at`.
2. Ajouter `SoftDeletes` dans le modele `Concept`.
3. Ajouter relations `Domain hasMany Concept` et `Concept belongsTo Domain`.
4. Ajouter casts ou constantes pour `difficulty` et `status`.
5. Ajouter accessors `statusLabel()` et `difficultyLabel()`.
6. Creer `ConceptController`.
7. Creer `StoreConceptRequest` et `UpdateConceptRequest`.
8. Ajouter route dediee pour le changement rapide de statut.
9. Ajouter routes et vues pour archives et restauration.
10. Optimiser les requetes avec `with()` et scopes de filtre.

## Resultat build attendu

- Les concepts sont listes avec filtres combinables.
- Le detail affiche titre, explication, difficulte, statut et generations.
- Le changement rapide suit `to_review -> in_progress -> mastered`.
- L'archivage cache le concept des listes principales.
- La restauration remet le concept dans les listes actives.

## Verification manuelle

- Creer un concept sans choisir de statut et verifier `A revoir`.
- Filtrer par `En cours` et `Senior` simultanement.
- Changer rapidement le statut depuis la liste.
- Archiver puis restaurer un concept.
- Verifier qu'un autre utilisateur ne peut pas acceder au concept.

## Fichiers probables

- `database/migrations/*_create_concepts_table.php`
- `app/Models/Concept.php`
- `app/Http/Controllers/ConceptController.php`
- `app/Http/Requests/StoreConceptRequest.php`
- `app/Http/Requests/UpdateConceptRequest.php`
- `resources/views/concepts/index.blade.php`
- `resources/views/concepts/show.blade.php`
- `resources/views/concepts/create.blade.php`
- `resources/views/concepts/edit.blade.php`
- `resources/views/concepts/archived.blade.php`

## Commit associe

- `feat(concepts): add CRUD filters and soft deletes with AI assistance`
