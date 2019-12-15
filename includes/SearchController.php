<?php

class SearchController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function printPageView()
    {

    }

    // search.php
    public function printSearchContent()
    {
        if($_SESSION['busena'] === 'Užblokuotas')
        {
            $this->redirect_to_another_page('index.php', 0);
        }

        if(isset($_POST['searchText']))
            $this->getView()->printSearchPage($_POST['searchText']);
        else
            $this->getView()->printSearchPage(null);

        if(isset($_POST['searchBtn']))
        {
            $movieList = $this->getModel()->getMovieListByPattern($_POST['searchText']);

            $this->getView()->printMovieSearchResults($movieList);
        }
    }

    public function getTitle()
    {
        echo 'Filmų nuoma - paieška';
    }
}