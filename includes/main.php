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
}
?>