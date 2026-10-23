<?php
// HAPUS setelah dipakai di produksi.
$password = 'admin123';
echo "<pre>Password : {$password}\nHash     : " . password_hash($password, PASSWORD_BCRYPT) . "</pre>";
echo "<p>Copy hash ke kolom <code>password</code> tabel <code>admin</code>.</p>";