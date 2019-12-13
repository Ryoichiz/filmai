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
        $_SESSION['email'] = "0";
        $_SESSION['role'] = "0";
        $_SESSION['uzblokuotas'] = "0";

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
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['uzblokuotas'] = $row['uzblokuotas'];
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

        public function setDefaultSessions()
    {
        // If ID is not set, it means we have to set default sessions, instead of rewriting whole function, I call logout() function. It does the same thing.
        if(!isset($_SESSION['id']) && empty($_SESSION['id']))
        {
            $this->logoutMe();
        }

        return true;
    }

    public function registerUser($username, $surname, $email, $password, $passwordRepeat, $date)
    {
        $conn = $this->conn;

        $username = $this->secureInput($username);
        $surname = $this->secureInput($surname);
        $email = $this->secureInput($email);
        $password = $this->secureInput($password);
        $passwordRepeat = $this->secureInput($passwordRepeat);
        $date = $this->secureInput($date);


        if (empty($username) || empty($password) || empty($passwordRepeat) || empty($email)) {
            return false;
        } else {
            $sql = "SELECT * FROM naudotojas WHERE vardas=? AND slaptazodis=?;";
            $stmt = mysqli_stmt_init($conn);
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
                            $role = 1;
                            $blocked = 1;
                            $date = date('Y-m-d H:i:s');
                            mysqli_stmt_bind_param($stmt, "isssssss", $id, $username, $surname, $hashedPwd, $email, $date, $blocked, $role);
                            mysqli_stmt_execute($stmt);
                            return true;
                        }
                    }
                }
            }
        }
    }
}
?>