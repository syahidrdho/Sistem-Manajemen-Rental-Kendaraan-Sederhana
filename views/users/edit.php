<?php 


// 1. Set variabel
$page_title = "Edit Pengguna";
$active_page = "users";
$errors = $errors ?? [];
$data = $data ?? ['id_user' => '', 'nama_lengkap' => '', 'username' => '', 'role' => ''];

// 2. Panggil Header
include 'header.php';
?>

    <main class="flex-1 p-8 overflow-y-auto">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-white">Edit Pengguna: <?= htmlspecialchars($data['username']) ?></h2>
            <p class="text-text-secondary-dark mt-1">Silakan perbarui detail pengguna di bawah ini.</p>
        </div>

        <div class="bg-black/30 backdrop-blur-sm p-6 md:p-8 rounded-xl shadow-2xl border border-white/10 max-w-2xl mx-auto">
            
            <form action="index.php?page=users&action=edit&id=<?= $data['id_user'] ?>" method="POST" class="space-y-6">
                
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken() ?>">

                <div>
                    <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-text-primary-dark">Nama Lengkap</label>
                    <input 
                        type="text" id="nama_lengkap" name="nama_lengkap" 
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark 
                                <?= isset($errors['nama_lengkap']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['nama_lengkap'] ?? '') ?>"
                        required
                    >
                    <?php if (isset($errors['nama_lengkap'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['nama_lengkap'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-text-primary-dark">Username</label>
                    <input 
                        type="text" id="username" name="username" 
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark 
                                <?= isset($errors['username']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['username'] ?? '') ?>"
                        required
                    >
                    <?php if (isset($errors['username'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['username'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-text-primary-dark">Password Baru (Opsional)</label>
                    <div class="relative">
                        <input 
                            type="password" id="password" name="password" 
                            class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark 
                                   <?= isset($errors['password']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>
                                   pr-12"
                            placeholder="Kosongkan jika tidak ingin diubah"
                        >
                        
                        <button 
                            type="button" id="togglePassword" 
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-text-secondary-dark hover:text-white"
                        >
                            <span id="eyeIcon" class="material-symbols-outlined text-base">visibility_off</span>
                        </button>
                    </div>
                    
                    <?php if (isset($errors['password'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="role" class="block mb-2 text-sm font-medium text-text-primary-dark">Role</label>
                    <select 
                        name="role" id="role" 
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white appearance-none 
                                <?= isset($errors['role']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                    >
                        <option value="karyawan" class="bg-gray-800" <?= ($data['role'] ?? 'karyawan') == 'karyawan' ? 'selected' : '' ?>>Karyawan</option>
                        <option value="manajer" class="bg-gray-800" <?= ($data['role'] ?? '') == 'manajer' ? 'selected' : '' ?>>Manajer</option>
                        
                        <?php if ($data['id_user'] == $_SESSION['user_id']): ?>
                            <option value="admin" class="bg-gray-800" selected>Admin (Tidak bisa diubah)</option>
                        <?php else: ?>
                            <option value="admin" class="bg-gray-800" <?= ($data['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <?php endif; ?>
                        
                    </select>
                    <?php if (isset($errors['role'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['role'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="index.php?page=users" class="px-5 py-2.5 text-sm font-medium bg-white/10 border border-white/20 text-text-secondary-dark rounded-lg shadow-sm hover:bg-white/20 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all duration-300">
                        <span class="material-symbols-outlined text-base">save</span>
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </main> <?php
// 5. Panggil Footer
include 'footer.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const toggleButton = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (toggleButton && passwordInput && eyeIcon) {
        
        toggleButton.addEventListener('click', function() {
            // Cek tipe input saat ini
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            if (isPassword) {
                // Jika sedang 'password' (tersembunyi), ubah ke 'text' (terlihat)
                passwordInput.setAttribute('type', 'text');
                
                // PERBAIKAN 2: Ubah ikon menjadi 'visibility' (mata biasa)
                eyeIcon.textContent = 'visibility';
            } else {
                // Jika sedang 'text' (terlihat), ubah ke 'password' (tersembunyi)
                passwordInput.setAttribute('type', 'password');
                
                // PERBAIKAN 2: Ubah ikon menjadi 'visibility_off' (mata dicoret)
                eyeIcon.textContent = 'visibility_off';
            }
        });
    }
});
</script>