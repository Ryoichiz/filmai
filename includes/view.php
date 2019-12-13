<?php
class View
{

    function __construct()
    {

    }

public static function printTop($location)
{
    echo '<div class="jumbotron text-center" style="margin-bottom:0">
    <h1>My First Bootstrap 4 Page</h1>
    <p>Resize this responsive page to see the effect!</p> 
    </div>';
}

public static function printNavbar($location)
    {
        echo '<a class="navbar-brand" href="index.php">Filmų nuoma  </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">';
        self::printNavbarItem("Namai", "index.php", $location);
        if($_SESSION['busena'] !== 'Užblokuotas') {
            self::printNavbarItem("Filmai", "movies.php", $location);
            
        }
        if ($_SESSION['role'] == "0") {

            self::printNavbarItem("Registruotis", "register.php", $location);
            self::printNavbarItem("Prisijungti", "login.php", $location);
        } else {
            if ($_SESSION['role'] == "Administratorius" && $_SESSION['busena'] !== 'Užblokuotas') {
                self::printNavbarItem("Valdymas", "admin.php", $location);
            }
            self::printNavbarItem("Krepšelis", "gallery.php", $location);
            self::printNavbarItem("Nustatymai", "profile.php", $location);
            self::printNavbarItem("Atsijungti", "logout.php", $location);
        }
        if( $_SESSION['busena'] !== 'Užblokuotas') {
            echo '</ul>
            <form class="form-inline my-2 my-lg-0" method="POST" action="search.php">
                <input class="form-control mr-sm-2" type="search" name="searchText" placeholder="Raktažodis paieškai" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Ieškoti</button>
            </form>
            </div>
          </nav>
        ';
        }

    }

        private static function printNavbarItem($name, $location, $globalLocation)
    {
        if ($globalLocation == $location) {
            echo '
                  <li class="nav-item active">
                    <a class="nav-link" href="' . $location . '">' . $name . '</a>
                  </li>';
        } else {
            echo '
                  <li class="nav-item">
                    <a class="nav-link" href="' . $location . '">' . $name . '</a>
                  </li>';
        }
    }

        function printIndexPage()
    {
        echo '<h1>Sveiki atvykę į filmu nuoma!</h1>';
    }

        function printSuccess($text)
    {
        echo '<div class="alert alert-success" role="alert">' . $text . '</div>';
    }

    function printDanger($text)
    {
        echo '<div class="alert alert-danger" role="alert">' . $text . '</div>';
    }

    function printWarning($text)
    {
        echo '<div class="alert alert-warning" role="alert">' . $text . '</div>';
    }

        public function printRegisterForm()
    {
        echo '        <form method=\'POST\' class=\'mainForm\'>
            <div class="form-group">
                <label for="inputFor">Slapyvardis*</label>
                <input type="text" name="username" class="form-control" id="inputFor" placeholder="Vardas">
            </div>
            <div class="form-group">
                <label for="inputFor">Pavardė</label>
                <input type="text" name="surname" class="form-control" id="inputFor" placeholder="Pavardė">
            </div>
            <div class="form-group">
                <label for="inputFor">El. pašto adresas*</label>
                <input type="email" name="el_pastas" class="form-control" id="inputFor" aria-describedby="emailHelp" placeholder="El. Paštas">
            </div>
            <div class="form-group">
                <label for="inputFor">Slaptažodis*</label>
                <input type="password" name="password" class="form-control" id="inputFor" placeholder="Slaptažodis">
            </div>
            <div class="form-group">
                <label for="inputFor">Pakartoti slaptažodį*</label>
                <input type="password" name="passwordRepeat" class="form-control" id="inputFor" placeholder="Pakartoti slaptažodį">

            </div>
                <button type="submit" name="registerButton" class="btn btn-primary">Registruotis</button>
        </form>';
    }

        public function printLoginPage()
    {
        echo '
        <form method="POST" class="mainForm">
            <div class="form-group">
                <label for="inputFor">Slapyvardis</label>
                <input name="username" type="text" class="form-control" id="inputFor" placeholder="Slapyvardis">
            </div>
            <div class="form-group">
                <label for="inputFor">Slaptažodis</label>
                <input name="password" type="password" class="form-control" id="inputFor" placeholder="Slaptažodis">
            </div>
                <button type="submit" name="loginButton" class="btn btn-primary">Prisijungti</button>
                <a href="remindpass.php">Pamiršai slaptažodį?</a>
        </form>';
    }

        public function printSettingsForm($username, $surname, $email)
    {
        echo '
            <form method=\'POST\' class=\'mainForm\'>
                <h1>Profilio nustatymai</h1>
                <div class="form-group">
                    <label for="inputFor">Slapyvardis*</label>
                    <input type="text" class="form-control" id="inputFor" value="'.$username.'" disabled>
                </div>
                <div class="form-group">
                    <label for="inputFor">Pavardė</label>
                    <input type="text" name="surname" class="form-control" id="inputFor" placeholder="Pavardė" value="'.$surname.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">El. pašto adresas*</label>
                    <input type="email" name="email" class="form-control" id="inputFor" aria-describedby="emailHelp" placeholder="El. Paštas" value="'.$email.'">
                </div>
                </div>
                    <button type="submit" name="saveSettingsBtn" class="btn btn-primary">Išsaugoti nustatymus</button>
            </form>';
    }

        public function printChangePasswordForm()
    {
        echo '<form method=\'POST\' class=\'mainForm\'>
                <h1>Slaptažodžio keitimo forma</h1>
                <div class="form-group">
                    <label for="inputFor">Dabartinis slaptažodis</label>
                    <input type="password" name="oldPasswd" class="form-control" id="inputFor" placeholder="Senas slaptažodis">
                </div>
                <div class="form-group">
                    <label for="inputFor">Naujas slaptažodis</label>
                    <input type="password" name="newPasswd" class="form-control" id="inputFor" placeholder="Naujas slaptažodis">
                </div>
                <div class="form-group">
                    <label for="inputFor">Pakartokite naują slaptažodį</label>
                    <input type="password"  name="repeatNewPasswd" class="form-control" id="inputFor" placeholder="Naujas slaptažodis">
                </div>
                <button type="submit" name="changePasswdBtn" class="btn btn-danger">Keisti slaptažodį</button>
            </form>';
    }

    public function printAdminPanel($users)
    {
        echo '
                <ul class="list-group">';
                 echo '<li class="list-group-item">
                   Prisijungusio valdytojo vardas: '.$_SESSION['vardas'].'
                 </li>';
        if ($users) {

            while ($row = mysqli_fetch_assoc($users)) {
                echo'<li class="list-group-item">
                '.$row['vardas'].' 
                <form class="btn">
                <input type="hidden" name="id" value="'.$row['id'].'">
                <button type="submit" name="uztildytas" value="1" class="btn btn-warning btn-sm">'; if($row['uztildytas'] == '0') { echo 'uztildyti'; } else { echo 'atitildyti'; }  echo '</button>
                </form>';
                if($_SESSION['role'] == "Administratorius") {
                    echo '
                <form class="btn">
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <button type="submit" name="uzblokuotas" value="1" class="btn btn-danger btn-sm">'; if($row['uzblokuotas'] == '0') { echo 'uzblokuoti'; } else { echo 'atblokuoti'; }  echo '</button>
                </form>
                <a href="edituser.php?id=' . $row['id'] . '"><button type="button" class="btn btn-primary btn-sm">Redaguoti naudotoją</button> </a>
                <form class="btn">
                <select class="btn btn-light" name="role">
                ';

                    if ($row['role'] == "Administratorius") {
                        echo '<option selected value = "1" > Naudotojas</option >
                          <option value = "2" > Administratorius</option >
                     </select>
                ';
                    } else if ($row['role'] == "Naudotojas") {
                        echo '<option value = "1" > Naudotojas</option >
                          <option selected value = "2" > Administratorius</option >
                     </select>
                ';
                    }

                    echo '
                
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <button type="submit" class="btn btn-primary btn-sm">Pakeisti rolę</button>
                </form>
             </li>';
                }

            }
        }

        echo '</ul>';
    }



}

?>