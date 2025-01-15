<?php


/*
 * La documentation php : https://www.php.net/manual/fr/class.datetimeinterface.php
 */

/**
 * Créer des objets DateTime implantant l'interface DateTimeInterface
 */
// Créer un objet DateTimeImmutable à partir d'une chaine de caractères en précisant le format
$d1 = DateTimeImmutable::createFromFormat('Y-m-d H:i', '2024-09-02 09:00');
// Créer un objet DateTimeImmutable à partir d'une chaine de caractères sans préciser le format qui est deviné par le constructeur
$d2 = new DateTimeImmutable('2024-09-02T09:00');  // T est le séparateur entre la date et l'heure
$d3 = new DateTimeImmutable('2024/09/02T09:00');
print "d1==d2 : " . (($d1 == $d2) ? "true" : "false") .PHP_EOL;
print "d1==d3 : " . (($d1 == $d3) ? "true" : "false") .PHP_EOL;

print_r($d1);
print_r($d2);
print_r($d3);

// Créer un objet DateTimeImmutable à partir d'une chaine de caractères sans préciser l'heure
$d4 = new DateTimeImmutable('2024-09-02');
print_r($d4);

// préciser la TimeZone
$d5 = new DateTimeImmutable('2024-09-02T08:30', new DateTimeZone('Europe/Paris'));



// modifier un objet DateTimeImmutable : les fonctions retournent un nouvel objet
$d5 = $d4->modify('O9:00')  // ajoute/modifie  l'heure 09:00
         ->modify('+1 day')  // ajoute 1 jour
         ->modify('+1 hour') // ajoute 1 heure
         ->modify('+30 minutes'); // ajoute 30 minutes

// formatter une date :
print $d5->format('l d/m/Y à  H:i') . PHP_EOL;

// récupérer le n° de jour de la semaine (0=dimanche, 1=lundi, 2=mardi, 3=mercredi, 4=jeudi, 5=vendredi, 6=samedi)
print $d5->format('w') . PHP_EOL;

// Gérer les TimeZone :
$d5 = new DateTimeImmutable('2024-09-02T08:30', new DateTimeZone('Europe/Paris'));
print $d5->format('D d M Y H:i:s  P e') . PHP_EOL;
// la même heure dans une autre timezone
$d6 = $d5->setTimezone(new DateTimeZone('America/New_York'));
print $d6->format('D d M Y H:i:s  P e') . PHP_EOL;

// créer des périodes itérables : DatePeriod
$d1 = new DateTimeImmutable('2024-09-02T09:00');
$d2 = new DateTimeImmutable('2024-09-06T12:00');
$interval = new DateInterval('P1D'); // intervalle de 1 jour : P-> Period, 1 -> 1 unité, D -> Day

$period = new DatePeriod($d1, $interval, $d2, DatePeriod::EXCLUDE_START_DATE); // EXCLUDE_START_DATE pour exclure la date de début
foreach ($period as $date) {
    print $date->format('l d/m/Y') . PHP_EOL;
}

$interval = new DateInterval('PT1H'); // intervalle de 1 heure P -> Period , T séparateur de time, 1 -> 1 unité, H -> Hour
$period = new DatePeriod($d1, $interval, $d2, DatePeriod::EXCLUDE_START_DATE); // EXCLUDE_START_DATE pour exclure la date de début
foreach ($period as $date) {
    print $date->format('l d/m/Y H:i') . PHP_EOL;
}

