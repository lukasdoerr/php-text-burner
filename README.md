# php-text-burner

You can download the sources also over Releases here: https://github.com/lukasdoerr/php-text-burner/releases/tag/Public

PHP Text Burner is a tool to send pastes via link and after the first view to delete them.
You can easily host this tool on your own.

### Installation

1. Download the Files from this repository via https://github.com/lukasdoerr/php-text-burner/releases/tag/Public
2. Unzip the files and place them in the desired root directory.
3. Open the index.php in your desired Text-Editor.
4. Copy & Paste the SQL Commands on top into your database.
5. Change the top variables (See info on them on the next point)
6. Open the config.php in your desired Text-Editor.
7. Edit host, user, pass and db constants.

### Variables

You can edit the whole code. If you want to edit it minimal, you can only set the variables to your desire:
- $baseUrl: The URL the redirect will be generated to.
- $deleteAfterRead: true = text will be deleted out of database, false = text will be kept but is not accessable for frontend (Not recommended!)
- $logoUrl: If you want to use a logo, you can do that by providing absolute url to image (Best way is, to put it into resources folder!)
- $title: The page title and alt tag for logo (if wanted)
- $copyright: Copyright Text under the Burner tool. Don't forget to give credits to ldoerr.com!
- $additionalHeaders: If you want to use more than normal headers (like your own stylesheet/js), paste your code into it!

### Copyright
Don't forget to let the copyright text in these files. This software is made by ldoerr.com and you can use/edit it completely free when leaving the copyright text.

### Website + Example
Visit https://www.ldoerr.com/ for more infos

You can use a working example on https://paste.ldoerr.de/
