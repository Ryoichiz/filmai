<?php

class ForumController extends MainController implements iController
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
            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Naudotojas neleistinai bandė panaudoti puslapįu", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }

        if(isset($_POST['searchText']))
            $this->getView()->printSearchPage($_POST['searchText']);
        else
            $this->getView()->printSearchPage(null);

        if(isset($_POST['searchBtn']))
        {
            $catalogList = $this->getModel()->getCatalogListByPattern($_POST['searchText']);
            $themeList = $this->getModel()->getThemeListByPattern($_POST['searchText']);

            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Atlikta paieška: ".$_POST['searchText']."", $ip);

            $this->getView()->printCatalogSearchResults($catalogList, $themeList);
        }
    }

    public function getTitle()
    {
        echo 'Gaming forum - forumas';
    }
}