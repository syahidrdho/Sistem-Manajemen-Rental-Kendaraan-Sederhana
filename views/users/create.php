<?php 
// 1. Set variabel (sebelum memanggil header)
$page_title = "Tambah Pengguna Baru";
$active_page = "users";
$errors = $errors ?? [];
$data = $data ?? ['nama_lengkap' => '', 'username' => '', 'role' => 'karyawan'];

// 2. Panggil Header
include 'header.php';
?>

    <main class="flex-1 p-8 overflow-y-auto">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-white">Tambah Pengguna Baru</h2>
            <p class="text-text-secondary-dark mt-1">Silakan isi detail pengguna di bawah ini.</p>
        </div>

        <div class="bg-black/30 backdrop-blur-sm p-6 md:p-8 rounded-xl shadow-2xl border border-white/10 max-w-2xl mx-auto">
            
            <form action="index.php?page=users&action=create" method="POST" class="space-y-6">
                
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken() ?>">

                <div>
                    <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-text-primary-dark">Nama Lengkap</label>
                    <input 
                        type="text" 
                        id="nama_lengkap" 
                        name="nama_lengkap" 
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark 
                                <?= isset($errors['nama_lengkap']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['nama_lengkap'] ?? '') ?>"
                        placeholder="Cth: John Doe"
                        required
                    >
                    <?php if (isset($errors['nama_lengkap'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['nama_lengkap'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-text-primary-dark">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark 
                                <?= isset($errors['username']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['username'] ?? '') ?>"
                        placeholder="Cth: johndoe88 (tanpa spasi)"
                        required
                    >
                    <?php if (isset($errors['username'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['username'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-text-primary-dark">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark 
                                <?= isset($errors['password']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        placeholder="Minimal 6 karakter"
                        required
                    >
                    <?php if (isset($errors['password'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="role" class="block mb-2 text-sm font-medium text-text-primary-dark">Role</label>
                    <select 
                        name="role" 
                        id="role" 
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white appearance-none 
                                <?= isset($errors['role']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                    >
                        <option value="karyawan" class="bg-gray-800" <?= ($data['role'] ?? 'karyawan') == 'karyawan' ? 'selected' : '' ?>>Karyawan</option>
                        <option value="manajer" class="bg-gray-800" <?= ($data['role'] ?? '') == 'manajer' ? 'selected' : '' ?>>Manajer</option>
                        <option value="admin" class="bg-gray-800" <?= ($data['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
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
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </main> <?php
// 5. Panggil Footer
include 'footer.php';
?>