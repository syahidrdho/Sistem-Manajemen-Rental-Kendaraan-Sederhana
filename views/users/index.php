<?php
// File: views/users/index.php

$page_title = "Kelola Pengguna";
$active_page = "users";

include 'header.php'; // pastikan path benar

// $result dikirim dari UserController
?>

<main class="flex-1 p-8 overflow-y-auto">

    <!-- Header Halaman -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Kelola Pengguna</h1>
            <p class="text-text-secondary-dark mt-1">Daftar pengguna sistem beserta peran dan opsi pengelolaan.</p>
        </div>
        <a href="index.php?page=users&action=create" 
           class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all duration-300">
            <span class="material-symbols-outlined text-base">add</span>
            Tambah Pengguna
        </a>
    </div>

    <!-- Tabel Pengguna -->
    <div class="bg-black/30 backdrop-blur-sm rounded-xl shadow-2xl border border-white/10 overflow-hidden">
        <table class="min-w-full text-left text-sm text-text-secondary-dark">
            <thead class="bg-black/20 border-b border-white/10 uppercase text-xs tracking-wider text-text-secondary-dark">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">ID</th>
                    <th scope="col" class="px-6 py-3 font-medium">Nama Lengkap</th>
                    <th scope="col" class="px-6 py-3 font-medium">Username</th>
                    <th scope="col" class="px-6 py-3 font-medium">Role</th>
                    <th scope="col" class="px-6 py-3 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-white/5 transition-colors duration-200">
                        <td class="px-6 py-4"><?= htmlspecialchars($row['id_user']) ?></td>
                        <td class="px-6 py-4 text-text-primary-dark font-medium"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($row['username']) ?></td>
                        <td class="px-6 py-4">
                            <?php 
                                $role = htmlspecialchars($row['role']);
                                $color = 'bg-gray-600';
                                if ($role == 'admin') $color = 'bg-red-600/80';
                                if ($role == 'manajer') $color = 'bg-primary/80';
                                if ($role == 'karyawan') $color = 'bg-emerald-600/80';
                            ?>
                            <span class="px-2.5 py-1 text-xs font-medium text-white rounded-full <?= $color ?>">
                                <?= ucfirst($role) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="index.php?page=users&action=edit&id=<?= $row['id_user'] ?>" 
                                   class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 border border-white/20 text-white hover:bg-white/20 transition-all duration-200"
                                   title="Edit Pengguna">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>

                                <?php if ($row['id_user'] != $_SESSION['user_id']): ?>
                                    <a href="index.php?page=users&action=delete&id=<?= $row['id_user'] ?>" 
                                       class="flex items-center justify-center w-8 h-8 rounded-lg bg-danger/20 border border-danger/30 text-danger hover:bg-danger/30 transition-all duration-200"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                       title="Hapus Pengguna">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Jika tidak ada data -->
        <?php if ($result->num_rows === 0): ?>
            <div class="text-center py-6 text-text-secondary-dark text-sm">
                Tidak ada pengguna yang terdaftar.
            </div>
        <?php endif; ?>
    </div>

</main>

<?php include 'footer.php'; ?>
