# BrooklynSignup

## Inhoud
- Over dit project
- Installatie
- Auteurs

# Over dit project
BrooklynSignup is een moderne webapplicatie voor autorijschool Brooklyn Drive, ontworpen om het inschrijfproces voor leerlingen te vereenvoudigen en de administratie voor instructeurs te stroomlijnen. Gebruikers kunnen zich eenvoudig registreren, lessen plannen en hun voortgang volgen via een overzichtelijke interface.

Het doel van dit project is om Brooklyn Drive te voorzien van een gebruiksvriendelijk, efficiënt en schaalbaar platform dat zowel leerlingen, instructeurs en beheerders ondersteunt in hun dagelijkse workflow.
# Installatie

### Vereisten:
*Hier staan de vereisten die je nodig hebt voor het installeren van onze webapplicatie.* 
* Zorg er voor dat je versie 8.4 of hoger van [PHP](https://www.php.net/downloads.php) geïnstalleerd hebt.
* Zorg er voor dat je de laatste versie van [Laravel](https://laravel.com/docs/12.x/installation) geïnstalleerd hebt.
* Zorg er voor dat je de laatste versie van [Herd](https://herd.laravel.com/windows) geïnstalleerd hebt.
* Zorg er voor dat je de laatste versie van [Composer](https://getcomposer.org/download/) geïnstalleerd hebt.
* Zorg er voor dat je de laatste versie van [NodeJS](https://nodejs.org/en/download/) geïnstalleerd hebt.
* Zorg er voor dat je [Visual Studio Code](https://code.visualstudio.com/download) geïnstalleerd hebt.

Als je deze vereisten hebt voltooid kun je doorgaan met de volgende stappen:

1. Clone de github repository
```
git clone https://github.com/AventusCT/kerntaakexamens-01-2026-team-7-brooklyn-signup
```
2. Installeer npm packages (Hiervoor moet [NodeJS](https://nodejs.org/en/download, "NodeJS Download") geïnstalleerd zijn)
```
npm install
```
3. Installeer de composer packages (Hiervoor moet [Composer](https://getcomposer.org/download/, "Composer Download") geïnstalleerd zijn)
```
composer install
```
4. Creeër de database
```
php artisan migrate
```
5. Voeg data aan de database toe
```
php artisan db:seed
```
5. Run't de local host server
```
Npm run dev
```
# Auteurs
* Julian Huis in 't Veld
* Dion Gierman
* Nick Schrader
* Hessel Steenwoerd
