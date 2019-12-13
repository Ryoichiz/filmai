<?php
class View
{

    function __construct()
    {

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
        if($_SESSION['uzblokuotas'] !== '1') {
            self::printNavbarItem("Filmai", "movies.php", $location);
            
        }
        if ($_SESSION['role'] == "0") {

            self::printNavbarItem("Registruotis", "register.php", $location);
            self::printNavbarItem("Prisijungti", "login.php", $location);
        } else {
            if ($_SESSION['role'] >= 2 && $_SESSION['uzblokuotas'] !== '1') {
                self::printNavbarItem("Valdymas", "adminpanel.php", $location);
            }
            self::printNavbarItem("Krepšelis", "gallery.php", $location);
            self::printNavbarItem("Nustatymai", "settings.php", $location);
            self::printNavbarItem("Atsijungti", "logout.php", $location);
        }
        if( $_SESSION['uzblokuotas'] !== '1') {
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
}

?>