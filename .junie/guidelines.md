# Consignes de développement - Cyber Archadia

Bienvenue dans l'application Cyber Archadia. Voici les directives pour le développement.

## Structure de l'application
L'application est divisée en deux parties distinctes :

1.  **Interface Joueur**
    *   **Cible** : Joueurs de jeu de rôle (univers cyberpunk).
    *   **Expérience** : Doit être immersive et conçue selon une approche **mobile-first**.

2.  **Console Meneur de Jeu (MJ)**
    *   **Cible** : Utilisée sur PC par le MJ.
    *   **Expérience** : Interface web classique.

## Documentation des fonctionnalités
Les détails des fonctionnalités se trouvent dans le dossier `features/` :
*   `features.md` : Description globale.
*   Fichiers individuels : Explications détaillées et état d'avancement.

Le site sera développé selon une méthodologie "monolithique", cad le fait d'utiliser le moins de dépendances possiblee et le moins de javascript possible.
Pour ce faire on travaillera avec les LiveComponent de Symfony, et avec des "rechargements de page" à l'ancienne (je clique = je passe par une route qui charge une page). 
