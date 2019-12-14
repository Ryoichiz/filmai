    <?php
class Model {
    private $server;
    private $dbName;
    private $dbUser;
    private $dbPassword;

    private $conn;

        function __construct()
    {
        $this->setDefaultSessions();
        date_default_timezone_set("Europe/Vilnius");
        $dbConfigFile = fopen("./includes/database.config", "r") or die("Unable to open file!");
        $dbConfigFileString =  fgets($dbConfigFile);
        $dbConfigLines = explode(":", $dbConfigFileString);
        fclose($dbConfigFile);
        $this->server = $dbConfigLines[0];
        $this->dbUser = $dbConfigLines[1];
        $this->dbPassword = $dbConfigLines[2];
        $this->dbName = $dbConfigLines[3];
        $this->conn = new mysqli($this->server, $this->dbUser, $this->dbPassword, $this->dbName);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->updateLoginStatus();
    }

    public function logoutMe()
    {
        $_SESSION['id'] = "0";
        $_SESSION['vardas'] = "0";
        $_SESSION['slaptazodis'] = "0";
        $_SESSION['el_pastas'] = "0";
        $_SESSION['role'] = "0";
        $_SESSION['busena'] = "Neutralus";

        return true;
    }

        public function updateLoginStatus()
    {
        if($_SESSION['id'] != "0")
        {
            $username = $this->secureInput($_SESSION['vardas']);
            $password = $this->secureInput($_SESSION['slaptazodis']);

            $sql = "SELECT * FROM naudotojas WHERE vardas='$username'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    if($password == $row['slaptazodis'])
                    {
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['vardas'] = $row['vardas'];
                        $_SESSION['slaptazodis'] = $row['slaptazodis'];
                        $_SESSION['el_pastas'] = $row['el_pastas'];
                        $_SESSION['role'] = $row['fk_role'];
                        $_SESSION['busena'] = $row['fk_naudotojo_busena'];
                        return true;
                    }
                    else
                    {
                        $this->logoutMe();
                        return false;
                    }
                }
            }
            else
            {
                $this->logoutMe();
            }
        }
    }

        public function loginMe($username, $password)
    {
        $username = $this->secureInput($username);
        $password = $this->secureInput($password);

        $sql = "SELECT * FROM naudotojas WHERE vardas='$username'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                if(password_verify($password, $row['slaptazodis']))
                {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['vardas'] = $row['vardas'];
                    $_SESSION['slaptazodis'] = $row['slaptazodis'];
                    $_SESSION['el_pastas'] = $row['el_pastas'];
                    $_SESSION['role'] = $row['fk_role'];
                    $_SESSION['busena'] = $row['fk_naudotojo_busena'];
                    //$date = date('Y-m-d H:i:s');
                    //$sql = "UPDATE naudotojai SET paskutini_karta_prisijunges='$date' WHERE slapyvardis='$username'";
                    //$this->conn->query($sql);
                    //$sql = "INSERT INTO naudotoju_ipai (ip, paskutinis_prisijungimas, fk_naudotojas) 
                            //VALUES (".$this->getUserIpAddr().", ".$date.", ".$row['id'].") 
                            //ON DUPLICATE KEY UPDATE 
                            //ip=VALUES(ip),
                            //paskutinis_prisijungimas=VALUES(paskutinis_prisijungimas)";
                    //$this->conn->query($sql);
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
    }

    public function updateUser($username, $newEmail, $newSurname)
    {
        $username = $this->secureInput($username);
        $newEmail = $this->secureInput($newEmail);
        $newSurname = $this->secureInput($newSurname);

        $sql = "UPDATE naudotojas SET el_pastas='$newEmail',
         pavarde='$newSurname' WHERE vardas='$username'";

        if($this->conn->query($sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

        public function setDefaultSessions()
    {
        // If ID is not set, it means we have to set default sessions, instead of rewriting whole function, I call logout() function. It does the same thing.
        if(!isset($_SESSION['id']) && empty($_SESSION['id']))
        {
            $this->logoutMe();
        }

        return true;
    }

    public function registerUser($username, $surname, $el_pastas, $password, $passwordRepeat, $date)
    {
        $conn = $this->conn;

        $username = $this->secureInput($username);
        $surname = $this->secureInput($surname);
        $el_pastas = $this->secureInput($el_pastas);
        $password = $this->secureInput($password);
        $passwordRepeat = $this->secureInput($passwordRepeat);
        $date = $this->secureInput($date);


        if (empty($username) || empty($password) || empty($passwordRepeat) || empty($el_pastas)) {

            return false;
        } else {
            $sql = "SELECT * FROM naudotojas WHERE vardas=? AND slaptazodis=?;";
            $stmt = mysqli_stmt_init($conn);
            //printf($stmt);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                
                return false;
            } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {

                return false;
            } else if ($password !== $passwordRepeat) {

                return false;
            } else {
                $sql = "SELECT vardas FROM naudotojas WHERE vardas=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    return false;
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $resultCheck = mysqli_stmt_num_rows($stmt);
                    if ($resultCheck > 0) {
                        return false;
                    } else {
                        $sql = ("SET CHARACTER SET utf16");
                        $conn->query($sql);
                        $sql = "INSERT INTO naudotojas (id, vardas, pavarde, slaptazodis, el_pastas, registracijos_data, fk_naudotojo_busena, fk_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            return false;
                        } else {
                            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                            $id = 0;
                            $role = "Naudotojas";
                            $blocked = "Neutralus";
                            $date = date('Y-m-d H:i:s');
                            mysqli_stmt_bind_param($stmt, "isssssss", $id, $username, $surname, $hashedPwd, $el_pastas, $date, $blocked, $role);
                            mysqli_stmt_execute($stmt);
                            return true;
                        }
                    }
                }
            }
        }
    }

        public function changePasswd($username, $password, $newPasswd, $repeatNewPasswd)
    {
        $conn = $this->conn;
        $username = $this->secureInput($username);
        $password = $this->secureInput($password);
        $newPasswd = $this->secureInput($newPasswd);
        $repeatNewPasswd = $this->secureInput($repeatNewPasswd);

        $sql = "SELECT * FROM naudotojas WHERE vardas='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                if(password_verify($password, $row['slaptazodis']))
                {
                    if ($newPasswd !== $repeatNewPasswd) {
                        echo("<script>location.href = 'settings.php?error=passwcheck';</script>");
                        exit();
                    } else {
                        $sql = "SELECT vardas FROM naudotojas WHERE vardas=?";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo("<script>location.href = 'settings.php?error=sqlerror';</script>");
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $username);
                            mysqli_stmt_execute($stmt);
                            $sql = ("SET CHARACTER SET utf8");
                            $conn->query($sql);
                            $hashedPwd = password_hash($newPasswd, PASSWORD_DEFAULT);
                            $sql = "UPDATE naudotojas SET slaptazodis=? WHERE vardas=?";
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                return false;
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $username);
                                mysqli_stmt_execute($stmt);
                                return true;
                            }
                        }
                    }
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
    }

        public function deleteData($table, $id)
        {
        $table = $this->secureInput($table);
        $id = $this->secureInput($id);
        $sql = "DELETE  FROM ".$table." WHERE id=".$id;
        if(mysqli_query($this->conn, $sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
        }

        public function changePasswdAdmin($id, $newPasswd, $repeatNewPasswd)
    {
        $conn = $this->conn;
        $id = $this->secureInput($id);
        $newPasswd = $this->secureInput($newPasswd);
        $repeatNewPasswd = $this->secureInput($repeatNewPasswd);

        $sql = "SELECT * FROM naudotojas WHERE id='$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                    if ($newPasswd !== $repeatNewPasswd) {
                        echo("<script>location.href = 'edituser.php?id=1&error=".$newPasswd."';</script>");
                        exit();
                    } else {
                        $sql = "SELECT vardas FROM naudotojas WHERE vardas=?";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo("<script>location.href = 'edituser.php?id=1&error=sqlerror';</script>");
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $username);
                            mysqli_stmt_execute($stmt);
                            $sql = ("SET CHARACTER SET utf8");
                            $conn->query($sql);
                            $hashedPwd = password_hash($newPasswd, PASSWORD_DEFAULT);
                            $sql = "UPDATE naudotojas SET slaptazodis=? WHERE id=?";
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                return false;
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $id);
                                mysqli_stmt_execute($stmt);
                                return true;
                            }
                        }
                    }
            }
        }
    }

    public function newCode($code, $number)
    {
        $conn = $this->conn;
        $code = $this->secureInput($code);
        $number = $this->secureInput($number);

        if (empty($code) || empty($number)) {
            return false;
        } else {
            $sql = "SELECT kodas FROM kodo_kurimas WHERE kodas=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                    return false;
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $code);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $resultCheck = mysqli_stmt_num_rows($stmt);
                    if ($resultCheck > 0) {
                        return false;
                    } else {
                        $sql = ("SET CHARACTER SET utf16");
                        $conn->query($sql);
                        $sql = "INSERT INTO kodo_kurimas (kodas, fk_busena, procentas) VALUES (?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            return false;
                        } else {
                            $fk_busena = "Sukurtas";
                            mysqli_stmt_bind_param($stmt, "ssi", $code, $fk_busena, $number);
                            mysqli_stmt_execute($stmt);
                            return true;
                        }
                    }
                }
        }
    }

        public function secureInput($input)
    {
        $input = mysqli_real_escape_string($this->conn, $input);
        $input = htmlspecialchars($input);
        return $input;
    }

        public function getDataByString($table, $column, $value)
    {
        $table = $this->secureInput($table);
        $column = $this->secureInput($column);
        $value = $this->secureInput($value);
        $sql = "SELECT * FROM ".$table." WHERE ".$column."='".$value."'";
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc())
            {
                return $row;
            }
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

        public function getDataByColumnFirst($table, $column, $value)
    {
        $table = $this->secureInput($table);
        $column = $this->secureInput($column);
        $value = $this->secureInput($value);
        $sql = "SELECT * FROM ".$table." WHERE ".$column."=".$value;
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc())
            {
                return $row;
            }
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

        public function updateDataOneColumn($table, $rowId,  $column, $newValue)
    {
        $table = $this->secureInput($table);
        $column = $this->secureInput($column);
        $newValue = $this->secureInput($newValue);
        $sql = "UPDATE ".$table." SET ".$column."='".$newValue."' WHERE id=".$rowId;
        $this->conn->set_charset("utf8");
        if($this->conn->query($sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

        public function getData($table)
    {
        $table = $this->secureInput($table);
        $this->conn->set_charset("utf8");
        $sql = "SELECT * FROM ".$table;
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            return $result;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

        public function returnConn()
    {
        return $this->conn;
    }
}
?>