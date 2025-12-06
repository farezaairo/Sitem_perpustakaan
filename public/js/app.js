document.addEventListener("DOMContentLoaded", () => {

    console.log("SCRIPT LOGIN JALAN");
    const form = document.getElementById("loginForm");
    if (!form) return;

    form.addEventListener("submit", async function (e) {
        e.preventDefault();
console.log("SUBMIT DITANGKAP");
        const email = document.getElementById("loginEmail").value;
        const password = document.getElementById("loginPassword").value;
        const errorDiv = document.getElementById("loginError");

        try {
            console.log("AMBIL CSRF COOKIE");
            // WAJIB: ambil CSRF cookie dari Laravel Sanctum
            await fetch("/sanctum/csrf-cookie", {
                method: "GET",
                credentials: "include"
            });
console.log("KIRIM LOGIN");
            const res = await fetch("/api/login", {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ email, password })
            });

            const data = await res.json();
            console.log("DATA LOGIN:", data);

            if (!res.ok) {
                errorDiv.textContent = data.message || "Email atau password salah";
                errorDiv.classList.remove("hidden");
                return;
            }

            localStorage.setItem("user", JSON.stringify(data.user));
            localStorage.setItem("token", data.token);

            // redirect berdasarkan peran
            switch (data.user.peran) {
               case "admin":
        return window.location.href = "/dashboard-admin";

    case "siswa":
        return window.location.href = "/app";  // diarahkan ke main app

    case "kepala_perpustakaan":
    case "kepala":
        return window.location.href = "/app";  // diarahkan ke main app

    default:
        errorDiv.textContent = "Peran tidak dikenali";
        errorDiv.classList.remove("hidden");
}

        } catch (err) {
            console.error(err);
            errorDiv.textContent = "Kesalahan koneksi";
            errorDiv.classList.remove("hidden");
        }
    });
});
