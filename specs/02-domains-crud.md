# Spec 02 - CRUD Domains

## Objectif

Construire la gestion des domaines techniques pour organiser les connaissances de l'utilisateur.

## User stories couvertes

- US2: Liste de mes domaines
- US3: Creer un domaine
- US4: Modifier ou supprimer un domaine

## Ce que je veux

- Une liste des domaines de l'utilisateur connecte uniquement.
- Pour chaque domaine: nom, couleur, nombre total de concepts, nombre de concepts maitrises.
- Un formulaire de creation avec `name` et `color`.
- Un formulaire de modification.
- Une suppression protegee par verification d'ownership.
- Des requetes optimisees avec `withCount()`.

## Ce que je ne veux pas

- Pas d'affichage des domaines d'un autre utilisateur.
- Pas de calcul des compteurs en boucle dans la vue.
- Pas de validation directement dans le controller.
- Pas de couleur autre qu'un code hex valide.
- Pas de suppression sans confirmation dans l'interface.

## Plan agent

1. Creer migration `domains` selon le MLD.
2. Ajouter relation `User hasMany Domain` et `Domain belongsTo User`.
3. Creer `DomainController` resource.
4. Creer `StoreDomainRequest` et `UpdateDomainRequest`.
5. Ajouter les routes sous middleware `auth`.
6. Construire les vues index, create, edit et show si necessaire.
7. Ajouter les compteurs `concepts_count` et `mastered_concepts_count`.

## Resultat build attendu

- L'utilisateur voit uniquement ses domaines.
- Les compteurs de progression sont visibles des la liste.
- La creation, modification et suppression fonctionnent.
- Une suppression de domaine supprime ses concepts par cascade.

## Verification manuelle

- Creer deux domaines avec couleurs differentes.
- Ajouter des concepts de statuts differents.
- Verifier les compteurs dans la liste.
- Tester qu'un utilisateur B ne peut pas ouvrir le domaine de l'utilisateur A.

## Fichiers probables

- `database/migrations/*_create_domains_table.php`
- `app/Models/Domain.php`
- `app/Http/Controllers/DomainController.php`
- `app/Http/Requests/StoreDomainRequest.php`
- `app/Http/Requests/UpdateDomainRequest.php`
- `resources/views/domains/index.blade.php`
- `resources/views/domains/create.blade.php`
- `resources/views/domains/edit.blade.php`

## Commit associe

- `feat(domains): add CRUD with AI-assisted implementation`
