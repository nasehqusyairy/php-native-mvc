<?php

/**
 * @param array<string, mixed> $data  Array untuk data yang dikirim ke view
 */
function view(string $viewPath, $data = [], $layoutPath = 'default')
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

    if (isset($_SESSION['_old'])) {
        unset($_SESSION['_old']);
    }
}

function old(string $key, string $default = ''): string
{
    // Ambil dari session old input
    if (isset($_SESSION['_old'][$key])) {
        return $_SESSION['_old'][$key];
    }

    return $default;
}

/**
 * @param array<string, mixed> $flash  Array asosiatif untuk flash message
 */
function redirect(string $url, array $flash = []): void
{
    // Simpan flash message (sekali pakai)
    if ($flash) {
        $_SESSION['_flash'] = $flash;
    }

    header("Location: $url");
    exit;
}


/**
 * @param array<string, mixed> $flash  Array asosiatif untuk flash message
 */
function back(array $flash = [])
{
    // Simpan input lama (supaya bisa dipanggil dengan old())
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['_old'] = $_POST;
    }
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    redirect($referer, $flash);
}

/**
 * @param array<string, mixed> $flash  Array asosiatif untuk flash message
 */
function flash($key): string|null
{
    if (isset($_SESSION['_flash'][$key])) {
        $message = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $message;
    }
    return null;
}
