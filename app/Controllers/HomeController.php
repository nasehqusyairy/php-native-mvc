<?php

class HomeController
{
    public function index()
    {
        view('home/index', ['title' => 'Home Page', 'name' => 'Naseh']);
    }

    public function greet($nama, $umur)
    {
        echo "Hello, $nama! Umurmu $umur tahun.";
    }
}
