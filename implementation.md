# Plan d'implémentation - Cyber Archadia

Ce document décrit les étapes nécessaires à l'implémentation technique de l'application Cyber Archadia, en respectant la méthodologie monolithique, l'utilisation limitée des Symfony LiveComponents et l'exploitation des CSS View Transitions pour une expérience fluide sans JavaScript.

## Phase 1 : Initialisation & Infrastructure

1.  *   Installation des dépendances clés :
        *   `AssetMapper` (pour éviter la complexité de Webpack/Vite).
        *   `Stimulus` & `Turbo` (intégré par défaut, utilisé de manière minimale).
        *   `Tailwind CSS` (via Symfony UX).

2.  **Configuration des Thèmes & Assets**
    *   Mise en place de deux bases de layout distinctes :
        *   `base_player.html.twig` : Design immersif, mobile-first, thème sombre/cyberpunk.
        *   `base_gm.html.twig` : Design utilitaire, classique, type dashboard.
    *   **Activation des View Transitions** :
        *   Ajout de `@view-transition { navigation: auto; }` dans le CSS global pour simuler un rendu SPA lors des rechargements de page.

## Phase 2 : Modèle de Données & Sécurité

1.  **Système d'Authentification**
    *   Création de l'entité `User`.
    *   Mise en place de la sécurité (formulaire de login).
    *   Définition des rôles : `ROLE_USER` (Joueur), `ROLE_GM` (Maître du Jeu).

2.  **Schéma de Base de Données (Core)**
    *   `Character` : Nom, PV (actuels/max), Stress, XP, REP, Caractéristiques (Force, Adresse, Intelligence), Avatar, User (owner).
    *   `Skill` : Nom, Catégorie (Force, Adresse, Intelligence), Niveau.
    *   `Ability` : Nom, Description, Type (Actif/Passif), Icone, Marque, Rechargement.
    *   `Item` : Nom, Type (Arme, Armure, etc.), Propriétés.
    *   `InventoryItem` : Relation entre `Character` et `Item` (ou simple chaîne pour les objets joueurs).

3.  **Schéma de Base de Données (Social & Deck)**
    *   `Contact` : Nom, Description, Image, Character (owner).
    *   `Message` : Emetteur, Récepteur, Contenu, Date, Statut (lu/non lu).
    *   `Card` : Nom, Image, Texte, Effets.
    *   `Deck` / `Discard` : Tables de liaison pour le deckbuilding "Vie".

## Phase 3 : Interface Joueur (Immersive & Mobile-First)

1.  **Système de Personnage Actif**
    *   Logique de redirection : si 0 personnage -> création, si 1+ -> sélection ou redirection auto vers le dernier actif.

2.  **Fiche de Personnage (Accueil)**
    *   Implémentation du bandeau supérieur (LiveComponent pour les PV et le Stress, permettant une mise à jour sans rechargement lors de dégâts).
    *   Affichage des compétences avec jauges graphiques.
    *   **Navigation par Routes** : Utilisation de routes Symfony classiques (`/char/{id}/inventory`, `/char/{id}/skills`, etc.) au lieu de LiveComponents pour le changement de vue, exploitant les View Transitions pour la fluidité.

3.  **Modules Spécifiques Joueur**
    *   **Inventaire** : Liste simple avec ajout d'objets (rechargement de page ou LiveComponent pour l'ajout rapide).
    *   **Capacités** : Boutons d'activation (LiveComponent pour le décompte/état inactif sans quitter la vue).
    *   **Téléphone (Social)** : Interface type messagerie, navigation entre contacts via des routes.
    *   **LIFE (Deckbuilding)** : Interface de pioche et gestion de main (LiveComponent pour l'aspect "jeu de cartes").

## Phase 4 : Console MJ (Back-office & Gestion)

1.  **Tableau de Bord "Vue d'ensemble"**
    *   LiveComponent affichant la liste des joueurs connectés.
    *   Actions rapides (Quick Actions) pour modifier PV/Stress à distance via `Mercure` (optionnel) ou polling LiveComponent.

2.  **Administration (CRUD)**
    *   Utilisation de `EasyAdmin` ou génération de CRUDs Symfony personnalisés pour :
        *   La base de données d'Items et d'Abilities.
        *   La gestion des Utilisateurs et Personnages.

3.  **Outils de Création**
    *   Interface de création de cartes pour le système "LIFE".
    *   Interface d'assignation de cartes aux decks des joueurs.

## Phase 5 : Fonctionnalités Avancées

1.  **Génération par IA (Symfony AI)**
    *   Intégration du composant AI pour assister le MJ.
    *   Prompts pré-configurés pour les PNJ, Lieux et Intrigues.

2.  **Netrunning & DEEPDIVE**
    *   Interface Cyberdeck pour le joueur.
    *   Générateur de réseaux (nodes/ICE) pour le MJ.
    *   Visualisation de graphes simple (probablement avec une petite librairie JS type `Mermaid.js` ou `Cytoscape` malgré l'approche monolithique).

3.  **Level Up System**
    *   Interface dédiée pour dépenser l'XP.
    *   Validation des pré-requis pour l'acquisition de nouveaux dons/talents.

## Phase 6 : Polissage & Immersion

1.  **Effets Visuels & Fluidité**
    *   **CSS View Transitions** : Personnalisation des transitions entre les pages (fondu, glissement) pour renforcer l'aspect high-tech sans JS.
    *   Utilisation de CSS (Scanlines, CRT flicker, Glitch effects) pour l'immersion.
    *   Sons d'interface (notifications messages, activation capacités).

2.  **Optimisation Mobile**
    *   Tests de performance (le rechargement de page doit être instantané grâce à Turbo et aux assets légers).
    *   PWA (Progressive Web App) pour permettre l'installation sur l'écran d'accueil du smartphone.
