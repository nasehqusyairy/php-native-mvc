<?php

function view($viewPath, $data = [], $layoutPath = 'default')
{
    // ekstrak array jadi variabel
    extract($data);
    /** PENJELASAN
     * 
     * extract() adalah fungsi PHP yang mengubah elemen-elemen dalam array asosiatif menjadi variabel individual.
     * Misalnya, $data = ['title' => 'Hello', 'name' => 'World'],
     * maka extract($data), akan menghasilkan variabel $title dan $name.
     * 
     */

    // simpan isi file view ke dalam variabel $content
    ob_start();
    require __DIR__ . "/../views/$viewPath.php";
    $content = ob_get_clean();
    /** PENJELASAN
     * 
     * ob_start() memulai penangkapan output.
     * require memasukkan file view, dan outputnya ditangkap oleh ob_start().
     * ob_get_clean() mengambil output yang ditangkap dan membersihkan buffer output.
     * Hasilnya disimpan dalam variabel $content.
     * 
     * Dengan cara ini, layout dapat menggunakan variabel $content untuk menampilkan isi view.
     * 
     */

    if ($layoutPath) {
        require __DIR__ . "/../views/layouts/$layoutPath.php";
    } else {
        echo $content;
    }
}
