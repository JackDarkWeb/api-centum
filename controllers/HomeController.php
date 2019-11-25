<?php


class HomeController extends Controller
{
    function welcome(){
        $this->view('home.welcome');
    }
}