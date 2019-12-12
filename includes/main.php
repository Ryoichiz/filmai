    <?php
class Model {
    private $server;
    private $dbName;
    private $dbUser;
    private $dbPassword;

    private $conn;

    public function logoutMe()
    {
        $_SESSION['id'] = "0";
        $_SESSION['slapyvardis'] = "0";
        $_SESSION['slaptazodis'] = "0";
        $_SESSION['email'] = "0";
        $_SESSION['role'] = "0";
        $_SESSION['uzblokuotas'] = "0";

        return true;
    }
}
?>