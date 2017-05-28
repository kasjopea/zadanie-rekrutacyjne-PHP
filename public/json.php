<?php
$jsonUsers = file_get_contents('http://jsonplaceholder.typicode.com/users');
$users = json_decode($jsonUsers, true); //tablica asocjacyjna

//selekcja po zipcode
foreach ($users as $user) {
	$zipcode = $user["address"]["zipcode"];
	$idUser = $user["id"];
	if (preg_match("/^\d{5}-\d{4}$/",$zipcode)){
		$selectedUsers[] = $idUser;
	}
}

//albumy uzytkownikow
if (isset($selectedUsers)){
$jsonAlbums = file_get_contents('http://jsonplaceholder.typicode.com/albums');
$albums = json_decode($jsonAlbums, true); 
foreach ($selectedUsers as $selectedUser) {
	$albumsIds = [];
	foreach ($albums as $album) {
		if ($album["userId"] == $selectedUser) {
			$albumsIds[] = $album["id"];
		}
	}
	$usersAndAlbums[$selectedUser] = $albumsIds;//tablica asocjacyjna- klucz to numer użytkownika, wartość to tablica z numerami albumów tego użytkownika
}

//zdjęcia użytkowników, selekcja po tytule zdjecia
$jsonPhotos = file_get_contents('http://jsonplaceholder.typicode.com/photos');
$photos = json_decode($jsonPhotos, true);
foreach ($usersAndAlbums as $userId => $albumIds){
	foreach ($albumsIds as $albumId) {
		foreach ($photos as $photo) {
			if ($photo["albumId"] == $albumId) {
				if (preg_match('/voluptate/',$photo["title"])) {
					$usersSelectedFinal[] = $userId;
					break;
				}
			}
		}
	break;
	}

}
//echo '<pre>' . print_r($usersSelectedFinal, true) . '</pre>';
if (isset($userSelectedFinal)) {
foreach ($users as $user) {
	foreach ($usersSelectedFinal as $userSelectedFinal) {
		if ($user["id"] == $userSelectedFinal) {
			$fileContain[] = $user;
		}
	}

}
}
}//koniec if isset selectedusers

if (!(isset($fileContain))) {
	$fileContain = "Brak wyników wyszukiwania dla podanych warunków.";
}
$JSONfileContain = json_encode($fileContain, JSON_PRETTY_PRINT);

//echo '<pre>' . print_r($JSONfileContain, true) . '</pre>';


$myfile = fopen("files/newfile.json", "w");
$txt = $JSONfileContain;
fwrite($myfile, $txt);
fclose($myfile);



// File to download.
$file = 'files/newfile.json';

// Maximum size of chunks (in bytes).
$maxRead = 3 * 1024 * 1024; // 1MB

// Give a nice name to your download.
$fileName = 'download_file.json';

// Open a file in read mode.
$fh = fopen($file, 'r');

// These headers will force download on browser,
// and set the custom file name for the download, respectively.
//header('Content-Type: application/octet-stream');
header("Content-type: text/javascript; charset=utf-8");
header('Content-Disposition: attachment; filename="' . $fileName . '"');

// Run this until we have read the whole file.
// feof (eof means "end of file") returns `true` when the handler
// has reached the end of file.
while (!feof($fh)) {
    // Read and output the next chunk.
    echo fread($fh, $maxRead);

    // Flush the output buffer to free memory.
    ob_flush();
}

// Exit to make sure not to output anything else.
exit;
