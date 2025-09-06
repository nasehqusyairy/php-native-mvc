<?php

class HomeController
{
    public function index(): void
    {
        echo "Welcome to Home";
    }

    public function greet($nama, $umur): void
    {
        echo "Hello, $nama! Umurmu $umur tahun.";
    }
}
