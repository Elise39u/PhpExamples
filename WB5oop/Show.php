(48 sloc)  1.65 KB
<?php
require_once 'user.php';
require_once 'StudentCollection.php';
// make database connection or show error
$link =  mysqli_connect('localhost', 'root', '', 'student');
if (!$link) {
    die("Connection error: " . mysqli_connect_error());
}
// make students
$p = new Student('99004345', ' Marieke', 'van', 'Zante', 'Mollenburgseweg 82', '4205NB', 'Gorinchem', 'mvanzante@davinci.nl');
$x = new Student('10000000', 'Nina', '', 'Mulder', '','','', 'nina@davinci.nl' );
$tristan = new Student('99000000', 'Tristan', 'de', 'Jager', 'Straatweg 12', '5647PC', 'Gorinchem', 'tristan@debaas.nl');
$Justin = new Student('99033673', 'Justin', 'van de', 'Laar', 'Weverstraat 24A', '4204CW', 'Gorinchem', 'justin555@live.nl');

// make collection and add the 2 new students
$col = new StudentCollection();
$col->Add($p);
$col->Add($x);
$col->Add($tristan);
$col->Add($Justin);

// save all students
foreach($col->students as $s)
{
    $s->SaveToDb($link);
    echo '<br>Saved record $s->Id</br>';
}

// print all firstnames
echo $col->GetFirstNamesULLI();

// is er een student met FirstName == 'Justin' ?
echo '<br>Gevonden studenten met de naam Justin:<br>';
$results = $col->GetStudentsWithFirstName('Justin');
foreach($results as $rs) {
    echo $rs->FirstName . '<br>';
    $rs->SaveToDb($link);
}
echo '<br/><br/>';

// show the json
echo $col->ToJson();

// now write the contents to a file
$col->WriteJsonToFile('Student.php');
echo '<br/><br/>';

//Read form the table student
echo $col->ReadFormDB($link);
echo '<br/><br/>';

//Make a new collection
$news = new StudentCollection();

//Read the id of the student
echo $news->Findbyid($link, '1');
var_dump($news);