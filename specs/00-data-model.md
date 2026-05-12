# Spec 00 - MCD et MLD InterviewPrep

## Objectif

Definir le modele de donnees valide avant le code applicatif Laravel.

## User stories couvertes

- US1: authentification utilisateur
- US2 a US4: gestion des domaines
- US5 a US10: gestion des concepts
- US11 a US13: generations IA
- Bonus: soft deletes sur les concepts

## MCD

Entites sans types ni cles etrangeres:

- User: name, email, password
- Domain: name, color
- Concept: title, explanation, difficulty, status, deleted_at
- GeneratedQuestion: questions

Associations:

- Un User possede plusieurs Domains.
- Un Domain appartient a un User.
- Un Domain contient plusieurs Concepts.
- Un Concept appartient a un Domain.
- Un Concept possede plusieurs GeneratedQuestions.
- Une GeneratedQuestion appartient a un Concept.

## MLD

Table `users`:

- `id` BIGINT PK
- `name` VARCHAR(255)
- `email` VARCHAR(255) UNIQUE
- `password` VARCHAR(255)
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

Table `domains`:

- `id` BIGINT PK
- `user_id` BIGINT FK vers `users.id` avec ON DELETE CASCADE
- `name` VARCHAR(255)
- `color` VARCHAR(7)
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

Table `concepts`:

- `id` BIGINT PK
- `domain_id` BIGINT FK vers `domains.id` avec ON DELETE CASCADE
- `title` VARCHAR(255)
- `explanation` TEXT
- `difficulty` ENUM('junior','mid','senior')
- `status` ENUM('to_review','in_progress','mastered')
- `deleted_at` TIMESTAMP NULL
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

Table `generated_questions`:

- `id` BIGINT PK
- `concept_id` BIGINT FK vers `concepts.id` avec ON DELETE CASCADE
- `questions` JSON
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

## Ce que je veux

- Un schema simple, defendable en demo et coherent avec les user stories.
- Des relations Eloquent directes et faciles a expliquer.
- Une suppression en cascade des donnees dependantes.
- Le soft delete limite aux concepts.

## Ce que je ne veux pas

- Pas de table pivot inutile.
- Pas de `VARCHAR` libre pour `difficulty` et `status` si un enum est possible.
- Pas de cle API IA en base.
- Pas de table separee pour chaque question individuelle dans la premiere version.

## Plan agent

1. Generer les migrations selon le MLD.
2. Ajouter les modeles Eloquent et relations.
3. Ajouter les casts JSON et SoftDeletes.
4. Ajouter les accessors de labels dans `Concept`.
5. Verifier les migrations avec MySQL.

## Resultat build attendu

- Les tables sont creees avec les bonnes cles.
- Les suppressions cascade fonctionnent.
- `GeneratedQuestion.questions` est cast en tableau.
- Les concepts archives restent restaurables.

## Verification manuelle

- Creer un utilisateur, un domaine, un concept et une generation.
- Supprimer un domaine et verifier que ses concepts et generations disparaissent.
- Archiver un concept et verifier que la ligne garde `deleted_at`.

## Commit associe

- `docs(data-model): add AI-assisted MCD and MLD specification`
