# Spec 01 - Authentification

## Objectif

Permettre a un utilisateur de creer un compte, se connecter et se deconnecter avant d'acceder aux donnees de preparation.

## User stories couvertes

- US1: Inscription, connexion, deconnexion

## Ce que je veux

- Une authentification Laravel standard et securisee.
- Les pages protegees par middleware `auth`.
- Une redirection claire apres connexion vers le dashboard ou la liste des domaines.
- Les donnees strictement separees par utilisateur.

## Ce que je ne veux pas

- Pas de systeme auth custom complexe.
- Pas de stockage de mot de passe en clair.
- Pas d'acces aux domaines, concepts ou generations sans connexion.
- Pas de dependance externe inutile pour l'authentification.

## Plan agent

1. Installer ou activer le starter auth valide par l'equipe.
2. Proteger les routes applicatives avec `auth`.
3. Rediriger l'utilisateur connecte vers `/dashboard`.
4. Tester inscription, connexion et deconnexion.
5. Verifier que les routes privees redirigent vers login.

## Resultat build attendu

- Un utilisateur peut s'inscrire.
- Un utilisateur peut se connecter.
- Un utilisateur peut se deconnecter.
- Les pages privees sont inaccessibles sans session.

## Verification manuelle

- Creer un compte de test.
- Se deconnecter puis tenter d'ouvrir `/domains`.
- Confirmer la redirection vers la page login.
- Se reconnecter et confirmer l'acces aux pages protegees.

## Fichiers probables

- `routes/web.php`
- `app/Models/User.php`
- `resources/views/auth/*`
- `resources/views/layouts/*`

## Commit associe

- `feat(auth): scaffold authentication with AI assistance`
