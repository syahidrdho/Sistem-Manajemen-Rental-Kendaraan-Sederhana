<?php
// File: views/auth/login.php (SUDAH DISESUAIKAN DESAINNYA)

$error_message = $error_message ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - Rental Kendaraan</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <script>
        // ======================================================
        // == INI ROMBAKAN 1: Samakan Config dengan header.php ==
        // ======================================================
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#4F46E5", // (Warna yang sama dengan internal)
                        secondary: "#10B981", 
                        warning: "#F59E0B", 
                        danger: "#EF4444", 
                        "background-dark": "#121212",
                        "surface-dark": "#1E1E1E",
                        "text-primary-dark": "#E2E8F0",
                        "text-secondary-dark": "#94A3B8",
                    },
                    fontFamily: {
                        display: ["Plus Jakarta Sans", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="font-display bg-gradient-to-br from-gray-900 to-black text-text-primary-dark flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-400 to-blue-500 bg-clip-text text-transparent drop-shadow-sm">Rental Kendaraan</h1>
            <p class="text-text-secondary-dark mt-2">Silakan login untuk melanjutkan</p>
        </div>

        <form action="index.php?page=auth&action=login" method="POST"
            class="bg-black/30 backdrop-blur-sm p-8 rounded-xl shadow-2xl border border-white/10 space-y-6 transition-all duration-300 hover:shadow-primary/20">

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-500/20 border border-red-500/40 text-red-300 px-4 py-3 rounded-lg text-sm">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-text-primary-dark">Username</label>
                <input type="text" id="username" name="username" required
                       class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark border-white/20 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-text-primary-dark">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark border-white/20 focus:border-primary focus:ring-primary">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-5 py-3 text-sm font-medium text-white bg-primary rounded-lg shadow-lg shadow-primary/30 hover:bg-primary/90 active:scale-[0.98] transition-all duration-300">
                    <span class="material-symbols-outlined text-base">login</span>
                    Login
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-text-secondary-dark mt-8">© <?= date('Y') ?> Rental Kendaraan. All rights reserved.</p>
    </div>

</body>
</html>