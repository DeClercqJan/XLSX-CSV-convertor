# CSV to/from XML convertor (yes XML, not XLXS at the moment)

## basic idea

## implementation
- heb alles maar veranderd naar XML in plaats van XLXS om tussentijdse verwarring te voorkomen(of vergroten). Waarom? verkeere API gekozen en ondertussen al zo ver (pad afhankelijkheid) dat ik in de eerste plaats wil verder doen om wat te leren. Misschien ook nog leuke uitdaging om het later te proberen aanpassen
- on mar 25, 2002 using https://faisalman.github.io/simple-excel-php/api/0.3/ to convert xml and csv. Yet xml isn't xlxs ... Maybe a good thing to try to change later on to see if code can be adapted easily
- will try out test-driven development (mocks for controller?) edit: not really doing it this way as I'm still quite uncomfortable with many things: forms, file uploads, services ...
- wil try to follow symfony coding standards, using php-cs-fixer)
- bootstrap 

## to do's 
- spoor van XML naar CSV (of beter, er een functie ofzo van maken die extendable is enzo)
- er staan nog steeds paar bestanden in die misschien weg mogen: convertorControllertest, Str.php ... opkuisen dus

## paar persoonlijke notities 

ELDERS TE ZETTEN
set autosave in gedit preferences want mogelijk weer wat kwijt


OUDE LOGICA
moet precies POST gebruiken om files op te laden

$_FILES bevat dan bestand

meerdere buttons inzelfde form: (https://symfony.com/doc/current/form/multiple_buttons.html)
$form->get("SubmitCSV")

moest move_uploaded_file gebruike https://www.php.net/manual/en/function.move-uploaded-file.php

upload path was neit relatief aan root van site, maar path van mijn computer
(php.ininiet meoten veranderne; géén path voor temporary files moeten zetten)

// basename() may prevent filesystem traversal attacks;

gemakkelijke manier om extensie te scheiden van naam $nameOnly = pathinfo($name, PATHINFO_FILENAME);

ANDERE
over composer
https://getcomposer.org/doc/01-basic-usage.md
moest iets toevoegen aan composer.json (in concreto https://faisalman.github.io/simple-excel-php/api/0.3/ convertor xml-cvs
dan om het te installeren: php composer.phar install
dan moest ik het met use importeren daar waar ik het wou gebruiken

heb dan eens alles proberen te groeperen wat samenhoorde, te verwijderen wat ik niet meteen (en misschien ook nooit zou gebruiken))

put a lot of of the work (like storing object on right place) in this object, using methods of Symfony\Component\HttpFoundation\File\UploadedFile;
question: does this have to do with FileType in FormType voor mijn form-field
https://symfony.com/doc/current/controller/upload_file.html how I've read it: FileType: to render front-end, but you can already call on File in its buildform props

te bekijken: Doctrine doet ook iets van file uploads. nope: niet meer goed volgens https://symfony.com/doc/current/controller/upload_file.html


# original assignment below

# Symfony Challenge

## Context
> The task is not difficult, but it will show how well you can make use of information available online and your 
> knowledge of PHP / HTML / CSS / JS to come to a good solution.

## Briefing
Using the latest version of Symfony, create a page where  one can upload an XLSX file and receive a CSV file in return, and inversely, send a CSV file and receive a XLSX file in return. 
You can of course use a library for the files conversion.

In order to test your JS / CSS / HTML knowledge a bit:

- Provide two beautiful buttons (one for csv and one for .xlsx) so you do not have to show the ugly  default browser buttons.
- Line from the buttons on the center (vertical + horizontal) of the display.
- Make sure everything looks the same in IE11, Edge, Chrome and Firefox
- On mobile are the buttons under each other over the entire width
- Make sure only the file format (.csv or .xlsx) can be uploaded.

