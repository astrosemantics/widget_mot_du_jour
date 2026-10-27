# Widget « Mot astro du jour »

> **Version 1.1** (octobre 2026) — format 4×3, texte plus grand

Un widget Android ([KWGT](https://play.google.com/store/apps/details?id=org.kustom.widget),
format 4×3) qui présente chaque jour un mot d'astronomie : une définition simple,
un exemple concret, un petit dessin, et un lien vers Wikipédia. Il passe en rouge la nuit.

- **290 mots en 12 thèmes** : le ciel à l'œil nu, la Lune, le Système solaire, les étoiles,
  le ciel profond, le matériel, l'observation, l'espace, l'astrophotographie, la pollution
  lumineuse, les femmes et les hommes de l'astronomie, l'histoire et la culture.
- **Un thème après l'autre**, pour varier : le tour complet dure environ neuf mois.
- **Les jours d'événement**, le mot suit l'actualité du ciel, avec la pastille
  « Événement du jour » et un exemple écrit pour ce jour-là :

| Événement | Mot du jour |
|---|---|
| Nouvelle Lune, premier quartier, pleine Lune, dernier quartier | le nom de la phase |
| Pleine Lune à moins de 362 000 km | Super Lune |
| Équinoxes et solstices (avec l'heure exacte) | Équinoxe, Solstice |
| Veille du maximum d'une pluie d'étoiles filantes | Radiant |
| Maximum (Quadrantides, Lyrides, Êta Aquarides, Perséides, Orionides, Léonides, Géminides) | Pluie d'étoiles filantes (avec un mot sur la gêne de la Lune) |
| 3 janvier, 4 juillet | Périhélie, Aphélie |

---

## Installation

### 1. Sur le serveur

1. Copier le dossier `mot-du-jour` sur l'hébergement (PHP 7.4 ou plus, avec GD et FreeType).
2. Ouvrir `https://votre-site/mot-du-jour/mot-widget.php` : l'image du jour doit s'afficher.
3. Dans `config.php`, régler si besoin le lieu (il ne sert qu'au passage au rouge) et
   `debut`, le premier jour du cycle.

### 2. Dans KWGT

1. Ajouter un widget KWGT **4×3**, puis un élément **Image**, largeur **334**, en haut à gauche.
2. Sur la ligne **Bitmap**, cocher la case « formule » et coller :

```
https://votre-site/mot-du-jour/mot-widget.php?v=$df(yyMMddHH)$$mu(floor, df(mm)/15)$
```

3. Ajuster l'**échelle** de la racine (onglet Calque) pour que l'image remplisse le widget.

C'est tout : pas de variable globale, rien à régler.

### 3. Le bouton « Wikipédia ↗ » (facultatif)

Une forme invisible posée sur le bouton dessiné dans l'image ouvre la page Wikipédia du mot du jour.

1. Dans l'éditeur KWGT du widget, rester sur la **racine** et toucher **+**, puis **Forme**.
2. Onglet **Forme** : type **Rectangle**, largeur **90**, hauteur **30**.
3. Onglet **Position** : ancrage **en haut à gauche**, décalage X **232**, décalage Y **183**.
4. Onglet **Peinture** (couleur) : blanc, avec l'opacité (alpha) réglée à **30 %** pour l'instant,
   pour voir le rectangle.
5. Onglet **Toucher** : **+**, puis Action **Ouvrir un lien**, et coller :
   `https://votre-site/mot-du-jour/wiki.php`
6. Enregistrer (disquette). Sur l'écran d'accueil, le rectangle blanc doit recouvrir
   le bouton « Wikipédia ↗ ». S'il est décalé, ajuster X et Y.
7. Une fois bien placé, revenir à la forme et mettre l'opacité à **1 %** : la zone devient
   invisible mais reste active.

La forme doit être **sous l'image dans la liste** de la racine (ajoutée après elle), pour être
au-dessus à l'écran. Si l'échelle de la racine a été changée, la forme suit l'image toute seule.

`wiki.php` renvoie toujours vers la page du mot affiché ce jour-là. Les liens lancent une
recherche Wikipédia : l'article s'ouvre directement quand il existe sous ce nom, sinon
Wikipédia propose la liste des résultats.

> Ne jamais mettre d'action au toucher sur l'image elle-même : sur certains téléphones,
> l'appui long ne permet plus alors d'éditer le widget.

---

## Modifier le glossaire

Les mots sont dans `lib/donnees/glossaire.php`, un fichier généré à partir du glossaire relu
(définition, exemple, lien, dessin). Pour corriger une définition, le plus simple est de
modifier le texte directement dans ce fichier, entre les apostrophes, en gardant
l'apostrophe typographique ’ dans le texte (ou en écrivant \' pour une apostrophe droite).

---

## Contenu du dossier

| Fichier | Rôle |
|---|---|
| `mot-widget.php` | Image du widget |
| `wiki.php` | Redirection vers la page Wikipédia du mot du jour |
| `config.php` | Réglages |
| `lib/MotDuJour.php` | Choix du mot du jour et des événements du ciel |
| `lib/Pictos.php` | Les petits dessins |
| `lib/Astro.php` | Positions du Soleil et de la Lune (Meeus) |
| `lib/Dessin.php`, `lib/Theme.php`, `lib/garde.php` | Dessin, couleurs normales / rouges, protection |
| `lib/donnees/glossaire.php` | Les 290 mots et l'ordre de passage |
| `fonts/` | Polices Outfit (OFL) et DejaVu Sans pour les lettres grecques |

---

## Crédits

| Élément | Source | Licence |
|---|---|---|
| Glossaire | rédigé pour le club astro de La Martyre | — |
| Liens | [Wikipédia](https://fr.wikipedia.org) | contenus CC BY-SA |
| Police | [Outfit](https://fonts.google.com/specimen/Outfit) | [SIL Open Font License](https://openfontlicense.org) |
| Lettres grecques | [DejaVu Sans](https://dejavu-fonts.github.io) | licence libre (voir `fonts/DejaVu-LICENSE.txt`) |
| Algorithmes | J. Meeus, *Astronomical Algorithms* (2e éd.) | — |
