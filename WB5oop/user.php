<?php
// the output of all functions assume that the info goes to a
// database. No HTML formatting is done, so linebreaks <br>
// must be added to replace the PHP_EOL's

class Student {
    public function __construct($sn, $fn, $pf, $ln, $add, $pc, $ct, $em) {
        $this->StudentNumber = $sn;
        $this->FirstName = $fn;
        $this->Prefix = $pf;
        $this->LastName = $ln;
        $this->Address = $add;
        $this->PostalCode = $pc;
        $this->City = $ct;
        $this->Email = $em;
    }
    
    public $Id;				// 0
    public $StudentNumber;	// 99033673
    public $FirstName;		// Justin
    public $Prefix;			// Van De
    public $LastName;		// Laar
    public $Address;		// Weverstraat 24A
    public $PostalCode;		// 4204 CW
    public $City;			// Gorinchem
    public $Email;			// Justin555@live.nl
    
    public function ToStringDisplayName() {
        return $this->FirstName . ' '
        . $this->Prefix . ' '
        . $this->LastName
        . ' (' . $this->StudentNumber . ')';
    }
    
    public function ToStringAddress() {
        return $this->FirstName . ' '
        . $this->Prefix . ' '
        . $this->LastName . PHP_EOL
        . $this->Address  . PHP_EOL
        . $this->PostalCode . '  ' . $this->City;
    }
    
    // example: David de Vos <david.de.vos@live.nl>
    public function ToStringEmail() {
        return $this->FirstName . ' '
        . $this->Prefix . ' '
        . $this->LastName
        . ' &lt;' . $this->Email . '&gt;';
    }


    // pass a valid MySQLi Database Connection
    //Checks if StudentNumber is in database if so update the entry else insert it.
    public function SaveToDb($conn){
        $result = mysqli_query($conn,"SELECT * FROM student WHERE StudentNumber = $this->StudentNumber");
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            $sql = "UPDATE student SET Firstname='$this->FirstName', Prefix='$this->Prefix', LastName='$this->LastName' , Address='$this->Address', PostalCode='$this->PostalCode', City='$this->City', Email='$this->Email' WHERE StudentNumber = $this->StudentNumber;";
            echo "Student updated";
            if ($conn->query($sql) === TRUE) {
                return "<div class='col-sm-3 col-centered'>Student with that student number already found updated the changes.</div>";
            }else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }else {
            $sql = "INSERT INTO student (StudentNumber, Firstname, Prefix, Lastname, Address, Postalcode, City, Email)
        VALUES ('$this->StudentNumber', '$this->FirstName', '$this->Prefix', '$this->LastName', '$this->Address', '$this->PostalCode', '$this->City', '$this->Email'
        )";
            echo "Student Added";
            if ($conn->query($sql) === TRUE) {
                return "<br><div class='col-sm-3 col-centered'>New student added with id = " . $conn->insert_id . "</div>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    public function LoadFromDb($conn, $id) {
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
}