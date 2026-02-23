# Architecture & Performance

L'application Cyber Archadia adopte une approche **monolithique moderne**, privilégiant la puissance du serveur tout en offrant une expérience fluide et immersive, proche d'une Single Page App (SPA), sans la complexité de JavaScript excessif.

## 🚀 Expérience Immersive (Effet SPA)

Pour donner ce rendu fluide typique des SPA, nous utilisons les capacités natives des navigateurs modernes.

### 1. View Transitions API
Le passage d'une route à l'autre déclenche une transition CSS automatique sans JavaScript complexe.

```css
/* Intégré dans les styles globaux */
@view-transition {
    navigation: auto;
}
```

### 2. Speculation Rules (Prerendering)
Pour éliminer les temps de chargement, nous utilisons les **Speculation Rules API**. Cette technologie permet au navigateur de pré-charger (prefetch) ou même de pré-calculer (prerender) la page suivante avant même que le joueur ne clique.

Voici la configuration recommandée à insérer dans le `head` (via Twig) :

```html
<script type="speculationrules">
{
  "prerender": [
    {
      "source": "list",
      "urls": ["/profil", "/inventaire", "/carte"],
      "score": 0.5
    },
    {
      "where": {
        "and": [
          { "href_matches": "/*" },
          { "not": { "href_matches": ["/logout", "/admin/*"] } }
        ]
      },
      "eagerness": "moderate"
    }
  ],
  "prefetch": [
    {
      "urls": ["/historique"],
      "eagerness": "conservative"
    }
  ]
}
</script>
```

- **Prerender** : Rend la page en arrière-plan (instantané au clic). Utilisé pour les routes critiques (Profil, Carte).
- **Moderate** : Prerender au survol (hover) du lien ou lors du `mousedown`.
- **Conservative** : Prerender uniquement lors du `mousedown`.

## 🔄 Cycle de Vie & Interactions

L'application repose sur un cycle simple mais puissant :

1. **Action Joueur** : Un clic sur un bouton ou un lien appelle une route Symfony.
2. **Action Controller** : Le contrôleur effectue les modifications en base de données ou déclenche des événements.
3. **Rendu Dynamique** : La page complète est rechargée. Grâce aux `View Transitions`, l'utilisateur ne perçoit pas de coupure visuelle.

## 📡 Mises à jour en temps réel (MJ -> Joueur)

Pour les interactions critiques (ex: le MJ inflige des dégâts à un personnage), nous utilisons **Symfony Messenger** couplé à **Mercure** pour pousser les mises à jour vers les fiches de personnages sans que le joueur n'ait besoin de rafraîchir manuellement sa page.

- Le MJ déclenche une action via sa console.
- Un message est dispatché via Messenger.
- Un événement est envoyé au client (via Mercure/Turbo).
- La fiche se met à jour dynamiquement.
