</div>
</main>

<?php
use App\Framework\Authentication;

$isLoggedIn = Authentication::isLoggedIn();
$user = Authentication::user();
if (!is_array($user)) {
    $user = [];
}
$role = $user['role'] ?? null;


$homeHref = (!$isLoggedIn || $role === 'customer') ? '/salons' : '/appointments';
?>

<footer class="footer py-4 mt-auto">
    <div class="container">
        <ul class="nav justify-content-center mb-3">
            <li class="nav-item">
                <a href="<?= htmlspecialchars($homeHref) ?>" class="nav-link px-2">Home</a>

            </li>
        </ul>
        <p class="text-center mb-0">© 2026 Booking System</p>
    </div>
</footer>

</div> <!-- /.app -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>


