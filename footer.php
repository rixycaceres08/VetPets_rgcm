<footer class="w3-container w3-dark-grey w3-center w3-margin-top w3-padding-16 w3-round">
        <p class="w3-small w3-text-light-grey" style="margin:0;">
            &copy; <?php echo date('Y'); ?> <?php echo $nombreClinica; ?> — Desarrollado por
            <b>Rixy Gisselle Cáceres Moncada</b>
        </p>
    </footer>

</div>

<script>
function toggleSubmenu(id, btn) {
    var submenu = document.getElementById(id);
    var arrow   = document.getElementById('arrow-' + id);

    if (submenu.style.display === "block") {
        submenu.style.display = "none";
        arrow.classList.remove('rotate');
    } else {
        document.querySelectorAll('.submenu').forEach(function (s) { s.style.display = "none"; });
        document.querySelectorAll('.menu-arrow').forEach(function (a) { a.classList.remove('rotate'); });
        submenu.style.display = "block";
        arrow.classList.add('rotate');
    }
}

function mostrarSeccion(id) {
    var el = document.getElementById(id);
    if (el) { el.scrollIntoView({ behavior: "smooth" }); }
}

function abrirSidebar() { document.getElementById("mySidebar").style.display = "block"; }
function cerrarSidebar() { document.getElementById("mySidebar").style.display = "none"; }

window.onresize = function () {
    if (window.innerWidth >= 768) {
        document.getElementById("mySidebar").style.display = "block";
    }
};

// Resalta el enlace del submenú activo (y abre/resalta su menú principal)
document.addEventListener("DOMContentLoaded", function () {
    var actual = window.location.pathname.split("/").pop();

    if (actual === "dashboard_vet.php" || actual === "") {
        var linkPanel = document.getElementById("link-panel");
        if (linkPanel) linkPanel.classList.add('menu-activo');
    }

    document.querySelectorAll(".submenu a").forEach(function (a) {
        var href = a.getAttribute("href").split("#")[0];
        if (href === actual) {
            a.classList.add('enlace-activo');

            var submenu = a.closest(".submenu");
            if (submenu) {
                submenu.style.display = "block";
                var arrow = document.getElementById('arrow-' + submenu.id);
                if (arrow) arrow.classList.add('rotate');

                var botonMenu = submenu.previousElementSibling;
                if (botonMenu) botonMenu.classList.add('menu-activo');
            }
        }
    });
});
</script>

</body>
</html>