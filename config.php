<?php
/**
 * Réglages du widget « Mot astro du jour ».
 */
return [
    // Lieu utilisé pour le passage au rouge (Soleil sous −6°)
    'lieu' => 'La Martyre',
    'latitude' => 48.436833,
    'longitude' => -4.150056,

    // Fuseau horaire : le mot change à minuit
    'fuseau' => 'Europe/Paris',

    // Couleurs : 'auto' (rouge dès que le Soleil passe sous -6°), 'rouge' ou 'normal'
    'theme' => 'auto',
    'rouge_soleil_sous' => -6,

    // Premier jour du cycle : ce jour-là, le widget montre le premier mot de la liste
    'debut' => '2026-11-01',

    // Durée de cache HTTP côté KWGT (secondes)
    'cache' => 600,
];
