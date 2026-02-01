# Fiche de Personnage
Statut : <span style="color:red">Pas implémenté</span>

## Mise en page principale (Accueil - Rôle Joueur)

Chaque joueur dispose de 1 ou plusieurs personnages, mais un seul personnage est actif. Si un joueur n'a pas de personnage actif, il est invité à créé un personnage et c'est la seule interaction possible avec l'application. Si il a un personnage actif, il est redirigé automatiquement sur la fiche du personnage.

### Bandeau horizontal supérieur
*   **Gauche** : Points de vie sous forme de petits carrés.
    *   Gris clair : Armure
    *   Vert : Biologique
    *   Rouge : Bio perdu
    *   Mauve : Armure perdue
    *   Répartition en 5 paliers : Indemne, Égratigné, Blessure légère, Blessure grave, Blessure critique.
    *   Chaque palier compte X carrés (X = valeur de PV du personnage).
* Toujours à gauche : le niveau de stress du personnage (quantité de stress actuelle)
*   **Centre** : Avatar du personnage. En-dessous, nombre de points d'expérience "XP" et de réputation "REP".
*   **Droite** : Caractéristiques principales (Force, Adresse, Intelligence).

### Zone centrale (sous le bandeau)
Affichage des compétences du personnage classées en 3 catégories (Force, Adresse, Intelligence).
*   Une ligne par compétence.
*   Indicateur de niveau : 10 carrés au total, X carrés jaunes (X = niveau du personnage) et le reste en gris.
* Un bouton "level up" qui permet d'ouvrir l'interface de level up

### Navigation
Boutons permettant de switcher vers :
*   Capacités
*   Inventaire
*   Cyberdeck
*   Téléphone
*   "LIFE" (Deckbuilding)

#### Capacités
Affiche des boutons représentants différentes capacités actives & passives du personnage. Une capacité active possède un nombre d'activation, et un bouton "activer" pour en décompter une activation. Une fois le nombre d'activation à 0, elle bascule en affichage "inactif". 
Une capacité active est rechargeable auto (et sera donc rechargée au max d'utilisation en cliquant un bouton "reset" ou bien en rechargement manuel qui ne pourrons être rechargée que via la console MJ)

Un bouton capacité contient une icone, une éventuelle image, un nom de capacité, une courte description. Elle est également définie par son type (passif/actif) son rechargement, et une catégorie (string) qui influencera la couleur d'affichage. Elle aura également une marque (pour identifier l'entreprise qui a fourni cette capacité)

#### Inventaire
Liste les possessions du personnage. C'est une liste qui contient les éléments attribués par le MJ, et la possibilité de rajouter des objets directement par le joueur. 
Un objet possède un nom, est relié à un personnage, et est éventuellement lié à une arme, armure, protection, cyberware, etc.. qui sont des ITEMS. Les objets rajoutés par des personnages sont d'office juste "nom".

#### Cyberdeck
Feature à peaufiner

#### Téléphone
L'interface téléphonne contient une liste de contact. Un contact est une div contenant une image de profil du contact, un nom, et une description. Il y a également une icône qui indique le nombre de message non lus envoyés par ce contact. En cliquant on peut afficher toute la conversion à la manière d'une chatbox, et le joueur peut envoyer des messages au contact. Le but est d'imiter de manière immersive une interface de téléphonne.

#### "LIFE"
Cette interface contient un deck de cartes. Elle permet de piocher des cartes, d'afficher les cartes du deck, d'afficher la défausse. Le joueur peut piocher des cartes jusqu'à un maximum de X (à configurer) + les éventuelles cartes piochables grâce à un effet d'une autre carte piochée.
Les cartes seront créées à partir d'une interface MJ et assignées au deck du joueur par le MJ

#### Level up
Cette interface affiche toutes les compétences existantes et permet d'en créer de nouvelles (spécifiquement pour ce personnage) puis de cliquer sur un bouton "+" en regard de chaque compétence pour y ajouter un niveau. 
En dessous des compétences seront listés les différents dons, skills, talents etc. qu'on peut acheter avec de l'xp. Ces achats viendront ajouter un nouvel élément sur les capacités du personnage. 
