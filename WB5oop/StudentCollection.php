<?php
require_once "user.php";

class StudentCollection
{
    public $students;
    Public $id;

    // hier maken we een nieuwe array aan waar de studenten worden opgeslagen
    public function __construct()
    {
        $this->students = [];
    }


    // Hier mee kunnen we een student in de collection toevoegen
    public function Add($student)
    {
        array_push($this->students, $student);
    }


    // Gaat zoeken op studentcollection en haalt alle eerste namen terug en slaat ze op in de arrat $names
    public function GetFirstNamesArray() {
        $names = [];
        foreach ($this->students as $student) {
            array_push($names, $student->FirstName);
        }
        return $names;
    }


    // Deze funtie zorgt er voor dat we straks alle gevonden student als lijstje treug gegeven <ul> <li> $Student->Firstname
    public function GetFirstNamesULLI() {
        $out = '<ul>';
        foreach ($this->students as $student) {
            $out .= '<li>' .  $student->FirstName . '</li>' ;
        }
        $out .= '</ul>';
        return $out;
    }

    // Zoekt op studeten met de eerste opgegeven naam
    public function GetStudentsWithFirstName($searchtext) {
        $found = [];  // nothing found, so return nothing (null)
        // get 1 student from the list; compare firstname; if match then store reference to the student in $found
        foreach ($this->students as $student) {
            if ( $student->FirstName == $searchtext ) array_push($found, $student);
        }
        return $found;
    }

    // zorgt er voor dat de studeten worden opgeslagen als json encrpytie
    public function ToJson() {
        return json_encode($this->students);
    }

    // Slaat de $col op in een nieuw bestand
    public function WriteJsonToFile($filename) {
        return file_put_contents($filename, $this->ToJson() );
    }

//  Slaan de studeten op in de database of updaten ze als ze al bestaan
    public function WriteToDatabase($conn){
        foreach ($this->students as $student) {
            $result = mysqli_query($conn,"SELECT * FROM student WHERE Studentnumber = $student->Studentnumber");
            $num_rows = mysqli_num_rows($result);
            if ($num_rows > 0) {
                $sql = "UPDATE student SET Firstname='$student->Firstname', Prefix='$student->Prefix', Lastname='$student->Lastname' , Address='$student->Address', Postalcode='$student->Postalcode', City='$student->City', Email='$student->Email' WHERE Studentnumber = $student->Studentnumber;";
                if ($conn->query($sql) === TRUE) {
                    echo "<div class='col-sm-3 col-centered'>Student with that student number already found updated the changes.</div>";
                }
                else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            else {
                $sql = "INSERT INTO student (Firstname, Prefix, Lastname, Address, Postalcode, City, Email, Studentnumber)
                VALUES ('$student->Firstname', '$student->Prefix', '$student->Lastname', '$student->Address', '$student->Postalcode', '$student->City', '$student->Email', '$student->Studentnumber')";
                if ($conn->query($sql) === TRUE) {
                    echo "<br><div class='col-sm-3 col-centered'>New student added with id = " . $conn->insert_id . "</div>";
                }
                else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

        }
    }

    // Zorgt ervoor dat we studenten kunnen lezen uit de database
    public function ReadFormDB($conn)
    {
        $sql = "Select * from student";
        $result = mysqli_query($conn, $sql);
        $count_fields = mysqli_num_fields($result);
        $count_rows = mysqli_num_rows($result);
        $index = 0;
        while($row = mysqli_fetch_array($result))
        {
            echo "
        ". $row['StudentNumber']."</br>
        ". $row['FirstName']."</br>
        ". $row['Prefix']."</br>
        ". $row['LastName']."</br>
        ". $row['Address']."</br>
        ". $row['PostalCode']."</br>
        ". $row['City']."</br>
        ". $row['Email']."</br>";
        }
        echo "
            </table>
            ";
    }

    public function Findbyid($conn, $id) {
        $sql = "SELECT * FROM student WHERE Id=" . $id;
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            // output data of each row
            $row = $result->fetch_assoc();
            $this->Id = $row['id'];
            $this->StudentNumber = $row['StudentNumber'];
            $this->FirstName = $row['FirstName'];
            $this->Prefix = $row['Prefix'];
            $this->LastName = $row['LastName'];
            $this->Address = $row['Address'];
            $this->PostalCode = $row['PostalCode'];
            $this->City = $row['City'];
            $this->Email = $row['Email'];
        } elseif ($result->num_rows == 0) {
            echo "0 rows found";
        }
        elseif ($result->num_rows > 1) {
            echo "more than 1 rows found";
        }
    }
 // Zorgt voor een query die de gevonden Id kan verwijderen
    public function DeleteByID($conn, $id) {
        $sql = "DELETE * FROM student WHERE Id=" . $id;
        $result = $conn->query($sql);
    }
}