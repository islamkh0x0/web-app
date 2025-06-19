<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>

<?php
// ✅ 1. إنهاء الـ Session لو كانت موجودة
session_start();
if (isset($_SESSION)) {
    $_SESSION = [];
    session_destroy();
}

// ✅ 2. حذف كوكي JWT لو كانت موجودة
if (isset($_COOKIE['jwt'])) {
    setcookie('jwt', '', time() - 3600, '/', '', false, true); // حذف الكوكي
}

// ✅ 3. إعادة التوجيه لصفحة تسجيل الدخول (حسب النظام اللي شغال بيه)
header("Location: jwt_login.php");
exit();
