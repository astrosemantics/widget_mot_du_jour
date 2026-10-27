<?php
/**
 * Image PNG du widget « Mot astro du jour » (4×3) pour KWGT.
 *
 * Un mot du glossaire par jour (12 thèmes à tour de rôle), ou un mot lié à l'actualité
 * du ciel (phases de la Lune, équinoxes, solstices, étoiles filantes…). En rouge la nuit.
 *
 * GET mot-widget.php                     → rouge automatique la nuit
 * GET mot-widget.php?theme=rouge|normal  → forcer les couleurs
 * GET mot-widget.php?t=<unix>            → un autre jour (tests)
 *
 * Image transparente de 1002 × 675 px (334 × 225 dp à densité ×3), pour un widget 4×3.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib/garde.php';
require_once __DIR__ . '/lib/MotDuJour.php';
require_once __DIR__ . '/lib/Pictos.php';
require_once __DIR__ . '/lib/Theme.php';
$cfg = require __DIR__ . '/config.php';

$maintenant = isset($_GET['t']) && ctype_digit((string) $_GET['t']) ? (int) $_GET['t'] : time();
$palette = Theme::choisir($cfg, $maintenant);
$rouge = $palette['rouge'];
const H = 225;          // format 4×3
$d = new Dessin($palette['couleurs'], H);
$mot = MotDuJour::choisir($cfg, $maintenant);
$L = 334.0;

// Fond de la carte, presque opaque pour la lisibilité
$d->rectangle(0, 0, $L, H, 24, $d->hex($d->hex['fond'], 0.95));
// Le texte « discret » est trop sombre en rouge : on l'éclaircit
$cEx = $rouge ? $d->nuance('texte', 0.8) : $d->couleur('discret');

// En-tête
$d->texteEspace('MOT ASTRO DU JOUR', 11, 'gras', 'accent', 16, 24);
if ($mot['evenement']) {
    $lb = $d->largeur('Événement du jour', 11.5, 'gras') + 18;
    $d->rectangle($L - 14 - $lb, 9, $lb, 22, 11, $d->couleur('alerte'));
    $d->texte('Événement du jour', 11.5, 'gras', 'fond', $L - 14 - $lb / 2, 24.5, true);
} else {
    $num = ' · ' . $mot['rang'] . ' / ' . $mot['total'];
    $ln = $d->largeur($num, 11.5, 'normal');
    $theme = $d->raccourcir($mot['theme'], 11.5, 'normal', 150 - $ln);
    $d->texteDroite($theme . $num, 11.5, 'normal', 'discret', $L - 16, 24);
}

// Pictogramme (en haut à droite, 72 dp)
$avecPicto = $mot['picto'] !== '';
if ($avecPicto) {
    $c = ['a' => $d->couleur('accent'), 't' => $d->couleur('texte'), 'd' => $d->couleur('discret'),
          'f' => $d->hex($rouge ? '1c0d0c' : '161a26'), 'o' => $d->couleur('alerte')];
    (new Pictos($d, $c, 72 / 64))->dessiner($mot['picto'], $L - 16 - 72, 36);
}
$finPicto = 36 + 72 + 4;         // sous le picto, le texte reprend toute la largeur

// Bouton « Wikipédia ↗ » (zone tactile KWGT au même endroit)
$lw = $d->largeur('Wikipédia ↗', 12.5, 'medium') + 24;
$yb = H - 12 - 30;
$d->pilule($L - 12 - $lw, $yb, $lw, 30, $d->couleur('accent'), $d->hex($d->hex['fond'], 0.95), 1.4);
$d->texte('Wikipédia ↗', 12.5, 'medium', 'texte', $L - 12 - $lw / 2, $yb + 19.5, true);

/** Coupe un texte en lignes dont la largeur dépend de la hauteur (à côté du picto ou non). */
$couper = static function (string $txt, float $t, float $y0, float $h, callable $largeur) use ($d): array {
    $lignes = [];
    $ligne = '';
    $y = $y0;
    foreach (preg_split('/ +/u', trim($txt)) as $m) {
        $essai = $ligne === '' ? $m : $ligne . ' ' . $m;
        if ($ligne !== '' && $d->largeur($essai, $t, 'normal') > $largeur($y)) {
            $lignes[] = [$ligne, $y];
            $y += $h;
            $ligne = $m;
        } else {
            $ligne = $essai;
        }
    }
    if ($ligne !== '') {
        $lignes[] = [$ligne, $y];
    }
    return $lignes;
};
$plein = $L - 32;
$cote = $avecPicto ? $L - 32 - 72 - 12 : $plein;

// Titre
$titre = $mot['mot'];
$tt = 25.0;
while ($d->largeur($titre, $tt, 'gras') > $cote && $tt > 17) {
    $tt -= 1;
}
$titre = $d->raccourcir($titre, $tt, 'gras', $cote);
$d->texte($titre, $tt, 'gras', 'texte', 16, 60);

// Définition puis exemple : la plus grande taille qui tient
$largeurEx = $L - 16 - $lw - 28;
foreach ([15.5, 15, 14.5, 14, 13.5, 13] as $tdef) {
    $h = $tdef * 1.3;
    $def = $couper($mot['definition'], $tdef, 60 + $h + 2, $h, static fn($y) => $y - $h < $finPicto ? $cote : $plein);
    $y = end($def)[1] + $h + 5;
    $te = $tdef - 1;
    $he = $te * 1.3;
    $ex = $couper('→ ' . $mot['exemple'], $te, $y, $he, static fn($yy) => $yy > $yb - 2 ? $largeurEx : ($yy - $he < $finPicto ? $cote : $plein));
    if (end($ex)[1] <= H - 14 && count($ex) <= 3) {
        break;
    }
}
foreach ($def as [$l, $y]) {
    $d->texte($l, $tdef, 'normal', 'texte', 16, $y);
}
foreach ($ex as $i => [$l, $y]) {
    if ($y > H - 10) {
        break;
    }
    if ($i === 0) {
        $d->texte('→', $te, 'normal', 'accent', 16, $y);
        $d->texteCouleur(mb_substr($l, 2), $te, 'normal', $cEx, 16 + $d->largeur('→ ', $te, 'normal'), $y);
    } else {
        $d->texteCouleur($l, $te, 'normal', $cEx, 16, $y);
    }
}

$d->envoyer((int) $cfg['cache']);
