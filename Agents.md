# AGENTS.md - InterviewPrep Laravel

Ce fichier guide tout coding agent utilise sur le projet InterviewPrep. Il doit etre commite des le Jour 1 avant le code applicatif.

## Contexte projet

InterviewPrep est une application Laravel personnelle pour structurer la preparation aux entretiens techniques backend Laravel. L'utilisateur organise ses connaissances par domaines, redige des concepts, suit sa maitrise et genere des questions d'entretien via une API IA.

## Stack cible

- Laravel 13 ou version validee par l'equipe
- PHP 8.3+
- MySQL
- Blade, Tailwind CSS ou CSS simple selon le starter choisi
- Auth Laravel natif ou starter officiel valide par l'equipe
- API IA: Groq API en priorite
- Appel IA: facade Laravel `Http::`, sans package externe

## Workflow obligatoire avec agent

1. Creer ou mettre a jour une spec dans `specs/` avant de coder.
2. Utiliser l'agent en mode Plan avant le mode Build.
3. Dans la spec, remplir clairement `Ce que je veux`, `Ce que je ne veux pas`, `Plan agent`, `Resultat build` et `Verification manuelle`.
4. Coder sur une branche feature dediee.
5. Commiter avec une mention explicite de l'usage IA.
6. Relire et corriger manuellement le code genere avant merge.

Branches recommandees:

- `feature/auth`
- `feature/domains-crud`
- `feature/concepts-crud`
- `feature/ai-generation`
- `feature/dashboard-archives`

Exemples de commits:

- `feat(domains): add CRUD generated with AI assistance`
- `fix(concepts): correct ownership checks after AI generation`
- `docs(specs): add AI-assisted build spec for Groq generation`

## Regles de conception

### Donnees

Relations attendues sur 3 niveaux minimum:

- `User hasMany Domain`
- `Domain belongsTo User`
- `Domain hasMany Concept`
- `Concept belongsTo Domain`
- `Concept hasMany GeneratedQuestion`
- `GeneratedQuestion belongsTo Concept`

Tables attendues:

- `users`
- `domains`
- `concepts`
- `generated_questions`

Champs importants:

- `domains.user_id`, `domains.name`, `domains.color`
- `concepts.domain_id`, `concepts.title`, `concepts.explanation`, `concepts.difficulty`, `concepts.status`, `concepts.deleted_at`
- `generated_questions.concept_id`, `generated_questions.questions`

Enums attendus:

- `difficulty`: `junior`, `mid`, `senior`
- `status`: `to_review`, `in_progress`, `mastered`

### Laravel

- Utiliser des Form Request classes pour toutes les validations.
- Verifier l'appartenance utilisateur avant toute lecture, modification ou suppression.
- Eviter les requetes N+1 avec `with()`, `withCount()` et `load()`.
- Utiliser les accessors `statusLabel()` et `difficultyLabel()` pour afficher les labels francais.
- Utiliser SoftDeletes uniquement sur `Concept`.
- Garder la logique metier hors des vues.

Labels attendus:

- `to_review`: `A revoir`
- `in_progress`: `En cours`
- `mastered`: `Maitrise`
- `junior`: `Junior`
- `mid`: `Mid`
- `senior`: `Senior`

### IA

- Appeler l'API via `Illuminate\Support\Facades\Http` uniquement.
- Lire la cle API depuis `.env` uniquement.
- Ne jamais commiter de cle API.
- Sauvegarder le resultat en base avant affichage.
- Afficher une erreur propre si l'API echoue.
- Generer exactement 5 questions par generation.
- Stocker les questions dans `generated_questions.questions` au format JSON.

Variables `.env` attendues:

```env
GROQ_API_KEY=
GROQ_MODEL=llama-3.1-8b-instant
```

## Ce que l'agent ne doit pas faire

- Ne pas ajouter de package externe pour appeler l'API IA.
- Ne pas stocker la cle API dans le code, les migrations, les seeders ou les specs.
- Ne pas valider directement dans les controllers si une Form Request est attendue.
- Ne pas afficher une exception brute a l'utilisateur.
- Ne pas generer de code sans verifier l'ownership utilisateur.
- Ne pas ignorer les soft deletes des concepts archives.
- Ne pas creer de tables ou colonnes non prevues sans justification dans la spec.

## Verification avant commit

Avant chaque commit, verifier:

- Les migrations passent.
- Les relations Eloquent sont utilisees dans les vues et controllers.
- Les Form Requests couvrent les champs obligatoires.
- Les pages ne declenchent pas de N+1 visible avec Debugbar.
- Le scenario utilisateur principal fonctionne en navigation manuelle.
- La spec correspond au code final.

## Format minimal d'une spec

Chaque fichier dans `specs/` doit contenir:

- Objectif
- User stories couvertes
- Ce que je veux
- Ce que je ne veux pas
- Plan agent
- Resultat build
- Verification manuelle
- Commandes ou checks effectues
- Commit associe
