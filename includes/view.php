<?php
class View
{

    function __construct()
    {

    }

public static function printTop($location)
{
    echo'<style>
        .jumbotron {
            padding: 13rem 2rem;!important;
        }
    </style>';
    echo '<div class="jumbotron container-fluid" style="background-image: url(uploads/Jumbotron.png); background-size: 100%; margin-bottom:0">
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
            self::printNavBarItem("Top10", "top_movies.php", $location);
            
        }
        if ($_SESSION['role'] == "0") {

            self::printNavbarItem("Registruotis", "register.php", $location);
            self::printNavbarItem("Prisijungti", "login.php", $location);
        } else {
            if ($_SESSION['role'] == "Administratorius" && $_SESSION['busena'] !== 'Užblokuotas') {
                self::printNavbarItem("Valdymas", "admin.php", $location);
                self::printNavBarItem("Nuolaidos", "discount.php", $location);
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

        public function printCatalogSearchResults($catalogList, $themeAnsList)
    {
        if ($catalogList->num_rows > 0)
        {
            echo '<h1>Atrinkti katalogai:</h1>
            <ul class="list-group">';

            while ($row = $catalogList->fetch_assoc())
            {
                echo '<a href="./themes.php?id='.$row['id'].'"><li class="list-group-item">'.$row['pavadinimas'].'</li></a>';
            }

            echo '</ul>';
        }
        else
        {
            echo '<h2>Rastu katalogu nera!</h2>';
        }

        if ($themeAnsList->num_rows > 0)
        {
            echo '<h1>Atrinktos temos su temu atsakymais:</h1>
            <div class="list-group">';

            while($row = $themeAnsList->fetch_assoc())
            {
                echo '  <a href="./viewtheme.php?id='.$row['id'].'" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">'.$row['pavadinimas'].'</h5>
                </div>
                <p class="mb-1">'.$row['tekstas'].'</p>
              </a>';
            }

            echo '</div>';
        }
        else
        {
            echo '<h2>Rastu temu nera!</h2>';
        }

    }

        public function printSearchPage($searchText)
    {
        echo '<form method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">Raktažodis paieškai</label>
                <input type="text" class="form-control" id="exampleInputEmail1" value="' . $searchText . '" name="searchText" placeholder="Raktažodis paieškai">
            </div>
            <button type="submit" name="searchBtn" class="btn btn-primary">Ieškoti</button>
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

        public function printSettingsForm($username, $surname, $email, $id)
    {
        echo '<a href="movie_list.php"><button type="button" class="mt-2 btn btn-primary btn-sm float-right ">Filmų Sąrašas</button> </a>';
        echo '<form method=\'POST\' class=\'mainForm mt-2\'>
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
                    <div class=\'container\'><button type="submit" name="saveSettingsBtn" class="btn btn-primary">Išsaugoti nustatymus</button></div><br>
            </form>';
    }

        public function printChangePasswordForm()
    {
        echo '<div class=\'container\'><form method=\'POST\' class=\'mainForm\'>
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
            </form></div>';
    }

        public function printDeleteButton()
        {
            echo '<form method=\'POST\' class=\'mainForm\'>

                <div class="form-group text-center">
                <h2>Ištrinti profilį (paspaudus vartotojas bus ištrinamas iš puslapio)</h2>
                <button type="submit" name="deleteUserBtn" class="btn btn-danger">Ištrinti paskyrą</button>
                </div>
            </form>';
        }

    public function printAdminPanel($users)
    {
        
                echo '<ul class="list-group">';
                 echo '<li class="list-group-item">
                   Prisijungusio valdytojo vardas: '.$_SESSION['vardas'].'
                 </li>';
        if ($users) {

            while ($row = mysqli_fetch_assoc($users)) {
                echo'<li class="list-group-item">
                '.$row['vardas'].' 
                <form class="btn">
                <select class="btn btn-light" name="busena">';

                    if ($row['fk_naudotojo_busena'] == "Neutralus") {
                        echo '<option selected value = "Neutralus" > Neutralus</option >
                          <option value = "Užtildytas" > Užtildytas</option >
                          <option value = "Užblokuotas" > Užblokuotas</option >
                     </select>';
                     echo '<input type="hidden" name="busenaid" value="' . $row['id'] . '">
                <button type="submit" class="btn btn-primary btn-sm">Pakeisti buseną</button>
                </form>';
                    } else if ($row['fk_naudotojo_busena'] == "Užblokuotas") {
                        echo '<option value = "Užblokuotas" > Užblokuotas</option >
                        <option selected value = "Neutralus" > Neutralus</option >
                         <option value = "Užtildytas" > Užtildytas</option >
                     </select>';
                      echo '<input type="hidden" name="busenaid" value="' . $row['id'] . '">
                <button type="submit" class="btn btn-danger btn-sm">Pakeisti buseną</button>
                </form>';
                    }else if ($row['fk_naudotojo_busena'] == "Užtildytas") {
                        echo '<option value = "Užtildytas" > Užtildytas</option >
                        <option selected value = "Neutralus" > Neutralus</option >
                        <option value = "Užblokuotas" > Užblokuotas</option >
                     </select>';
                       echo '<input type="hidden" name="busenaid" value="' . $row['id'] . '">
                <button type="submit" class="btn btn-warning btn-sm">Pakeisti buseną</button>
                </form>';
                    }

                echo '

                <a href="edituser.php?id=' . $row['id'] . '"><button type="button" class="btn btn-primary btn-sm">Redaguoti naudotoją</button> </a>
                <form class="btn">
                <select class="btn btn-light" name="role">';
                

                    if ($row['fk_role'] == "Naudotojas") {
                        echo '
                        <option selected value = "Naudotojas" > Naudotojas</option >
                          <option value = "Administratorius" > Administratorius</option >
                     </select>';
                
                    } else if ($row['fk_role'] == "Administratorius") {
                        echo '
                          <option selected value = "Administratorius" > Administratorius</option >
                          <option value = "Naudotojas" > Naudotojas</option >
                     </select>';
                
                    }

                echo '<input type="hidden" name="id" value="' . $row['id'] . '">
                <button type="submit" class="btn btn-primary btn-sm">Pakeisti rolę</button>
                </form>
                <form class="btn">
                <input type="hidden" name="deleteid" value="' . $row['id'] . '">
                <button type="submit" class="btn btn-danger btn-sm">Naikinti</button>
                </form>

             </li>';

                }
                echo '<form>';
                echo '<a href="discount.php"><button type="button" class="btn btn-primary btn-sm">Nuolaidos</button> </a>';
                echo '</form>';
            }
        }

    public function printEditUserAsAdmin($content)
    {
        echo ' <form method="POST" class="mainForm">
            <h1>Koreguojamas '.$content['vardas'].' profilis</h1>
            <h1>Profilio nustatymai</h1>
            <input type="hidden" name="id" value="'.$content['id'].'">
            <div class="form-group">
                <label for="inputFor">Slapyvardis*</label>
                <input type="text" class="form-control" id="inputFor" value="'.$content['vardas'].'" disabled>
            </div>
                <div class="form-group">
                <label for="inputFor">Pavardė</label>
                <input type="text" class="form-control" id="inputFor" name="pavarde" placeholder="Pavardė" value="'.$content['pavarde'].'">
                </div>
            <div class="form-group">
                <label for="inputFor">El. pašto adresas*</label>
                <input type="email" class="form-control" id="inputFor" name="el_pastas" aria-describedby="emailHelp" placeholder="El. Paštas" value="'.$content['el_pastas'].'">
            </div>

                <button type="submit" name="request" value="visiDuomenys" class="btn btn-primary">Išsaugoti nustatymus</button>
        </form>

        <form method="POST" class="mainForm">
            <h1>Slaptažodžio keitimo forma</h1>
            <div class="form-group">
                <label for="inputFor">Naujas slaptažodis</label>
                <input type="password" class="form-control" name="slaptazodis" value="slaptazodis" id="inputFor" name="slaptazodis  " placeholder="Naujas slaptažodis">
            </div>
            <div class="form-group">
                <label for="inputFor">Pakartokite naują slaptažodį</label>
                <input type="password" class="form-control" name="slaptazodisPakartoti" value="slaptazodisPakartoti" id="inputFor" name="slaptazodisPakartoti" placeholder="Naujas slaptažodis">
            </div>
            <button type="submit" name="request" value="slaptazodisSubmit" class="btn btn-danger">Keisti slaptažodį</button>
        </form>';

    }

    public function printDiscountForm($discounts)
    {
        echo '<h1>Nuolaidų sąrašas</h1>';
            while ($row = mysqli_fetch_assoc($discounts)) {
                echo'<li class="list-group-item">';
                echo 'Kodo id: '.$row['id'].' |Kodas: '.$row['kodas'];
                echo " |Kodo nuolaida(procentais): ".$row['procentas'];
                echo '</li>';
            }
            echo '<form method="POST" class="mainForm">
            <h1>Kodo kurimo forma</h1>
            <div class="form-group">
                <label for="inputFor">Naujas kodas</label>
                <input type="text" class="form-control" name="kodas" id="inputFor" name="kodas" placeholder="Naujas kodas">
            </div>
            <div class="form-group">
                <label for="inputFor">Kodo nuolaida(procentais 0-100)</label>
                <input type="number" min=1 max=100 class="form-control" name="kodoproc" id="inputFor" name="naujanuolaida" placeholder="nuolaida">
            </div>
            <button type="submit" name="codeRequestBtn" value="createCode" class="btn btn-primary">Kurti koda</button>
        </form>';
    }

        public function printDiscount()
    {
            echo '<form method="POST" class="mainForm">
            <h1>Nuolaidos pritaikymas</h1>
            <div class="form-group">
                <input type="text" class="form-control" name="kodas" id="inputFor" name="kodas" placeholder="Kodas">
            </div>
            <button type="submit" name="codePutBtn" value="putCode" class="btn btn-primary">Kurti koda</button>
            <button type="submit" name="codeTakeBtn" value="takeCode" class="btn btn-danger">Nuimti koda</button>
        </form>';
    }

            public function printDiscountWith($code, $procent)
    {
            echo '<form method="POST" class="mainForm">
            <h1>Nuolaidos pritaikymas</h1>
            Dabartinis kodas: '.$code.' Nuolaida: '.$procent.'
            <div class="form-group">
                <input type="text" class="form-control" name="kodas" id="inputFor" name="kodas" placeholder="Kodas">
            </div>
            <button type="submit" name="codePutBtn" value="putCode" class="btn btn-primary">Kurti koda</button>
            <button type="submit" name="codeTakeBtn" value="takeCode" class="btn btn-danger">Nuimti koda</button>
        </form>';
    }

}

?>