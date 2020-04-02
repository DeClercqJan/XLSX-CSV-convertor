# CSV to/from XML convertor (yes XML, not XLXS at the moment)

## basic idea
- upload to/from CSV-XML
- Becode PHP challenge

## implementation
- didn't choose the right external app to transform to/from XLXS (whoop!) (https://faisalman.github.io/simple-excel-php/api/0.3/). Limits this app's use, but I'm doing this to improve my process anyway
- to understand why only 2003 xml-files are supproted: go to the xml-parser and you'll see things like Workbook. This text is not present in other xml-files. 
- had massive issues with xml-files: unrecognized index and still, I had to delete all dump()'s as these would be written to the csv-file that gets exported!? No idea why
- bootstrap, encore
- tried to pay attention to code quality
- symfony coding standards, using php-cs-fixer

## to do's 
- unit tests
- find other service that does supports XLXS (would be nice to see how modular my app is)
- styling (looks ok across browser, but did not check systemaically? How can I do this automatically?
- question: should I have extended UploadedFile to my FileToBeConverted instead of 'use'-ing it. Anyway, it was very helpful to look through existing classes and see what methods I could just use (starting to like this Symfony thing!)

## paar persoonlijke notities 


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

