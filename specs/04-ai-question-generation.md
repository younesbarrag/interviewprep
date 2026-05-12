# Spec 04 - Generation IA de questions d'entretien

## Objectif

Generer, sauvegarder et afficher des questions d'entretien realistes pour un concept technique en utilisant Groq via la facade Laravel `Http::`.

## User stories couvertes

- US11: Generer des questions d'entretien
- US12: Voir l'historique des generations
- US13: Supprimer une generation

## Ce que je veux

- Un bouton sur la page detail d'un concept: `Generer des questions d'entretien`.
- Un appel Groq base sur le titre, l'explication, la difficulte et le statut du concept.
- Exactement 5 questions techniques realistes en francais.
- Un resultat sauvegarde dans `generated_questions` avant affichage.
- Un historique chronologique des generations.
- Une suppression d'un lot de questions inutile.
- Un message utilisateur clair si l'API echoue.

## Ce que je ne veux pas

- Pas de package externe pour Groq.
- Pas de cle API dans le code ou dans Git.
- Pas d'affichage avant sauvegarde en base.
- Pas de page blanche en cas d'erreur API.
- Pas de generation pour un concept appartenant a un autre utilisateur.
- Pas de reponse libre difficile a parser si on peut demander un JSON strict.

## Plan agent

1. Creer migration `generated_questions` selon le MLD.
2. Ajouter modele `GeneratedQuestion` avec cast JSON sur `questions`.
3. Ajouter relations `Concept hasMany GeneratedQuestion` et `GeneratedQuestion belongsTo Concept`.
4. Creer `AiQuestionController` ou action dediee dans `ConceptQuestionController`.
5. Ajouter configuration `services.groq` avec `key`, `model` et `url` lus depuis `.env`.
6. Appeler Groq avec `Http::withToken(config('services.groq.key'))`.
7. Demander une reponse JSON contenant un tableau `questions` de 5 chaines.
8. Valider et normaliser la reponse avant sauvegarde.
9. Sauvegarder le lot dans `generated_questions`.
10. Rediriger vers le detail du concept avec message de succes ou d'erreur.

## Exemple de prompt IA

System:

```text
Tu es un recruteur backend Laravel. Genere uniquement du JSON valide.
```

User:

```text
Genere exactement 5 questions d'entretien techniques en francais pour ce concept.
Titre: {{ title }}
Explication: {{ explanation }}
Difficulte: {{ difficulty_label }}
Statut de maitrise: {{ status_label }}
Format attendu: {"questions":["...","...","...","...","..."]}
```

## Resultat build attendu

- Une generation cree une ligne dans `generated_questions`.
- Les 5 questions s'affichent dans l'historique du concept.
- Les generations sont triees de la plus recente a la plus ancienne.
- La suppression d'une generation fonctionne.
- Les erreurs API sont affichees dans une alerte propre.

## Gestion erreurs

- Cle API absente: afficher `Configuration IA manquante`.
- Timeout ou erreur reseau: afficher `Le service IA est indisponible. Reessayez plus tard.`
- JSON invalide: afficher `La reponse IA est invalide. Aucune generation n'a ete sauvegardee.`
- Moins ou plus de 5 questions: normaliser si possible, sinon refuser la sauvegarde.

## Verification manuelle

- Generer des questions pour un concept Laravel.
- Rafraichir la page et verifier que les questions persistent.
- Generer une deuxieme fois et verifier l'historique.
- Supprimer un lot et verifier qu'il disparait.
- Tester avec une cle API invalide et verifier le message propre.

## Fichiers probables

- `database/migrations/*_create_generated_questions_table.php`
- `app/Models/GeneratedQuestion.php`
- `app/Http/Controllers/AiQuestionController.php`
- `config/services.php`
- `resources/views/concepts/show.blade.php`

## Commit associe

- `feat(ai): generate Groq interview questions with AI assistance`
