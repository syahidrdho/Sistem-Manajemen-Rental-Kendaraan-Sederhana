<?php
include 'header.php';
?>

    <main class="flex-1 p-8 overflow-y-auto">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-white">Edit Transaksi Sewa</h2>
            <p class="text-text-secondary-dark mt-1">Perbarui detail transaksi di bawah ini.</p>
        </div>

        <div class="bg-black/30 backdrop-blur-sm p-6 md:p-8 rounded-xl shadow-2xl border border-white/10 max-w-2xl mx-auto">

            <form action="index.php?page=transaksi&action=edit&id=<?= htmlspecialchars($data['id_sewa']) ?>" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken() ?>">

                <div>
                    <label for="id_pelanggan" class="block mb-2 text-sm font-medium text-text-primary-dark">Pelanggan</label>
                    <select
                        id="id_pelanggan"
                        name="id_pelanggan"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white appearance-none
                               <?= isset($errors['id_pelanggan']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                    >
                        <option value="" class="bg-surface-dark text-text-secondary-dark">-- Pilih Pelanggan --</option>
                        <?php mysqli_data_seek($pelanggan, 0); // Reset pointer loop ?>
                        <?php while($p = $pelanggan->fetch_assoc()): ?>
                            <option value="<?= $p['id_pelanggan'] ?>" class="bg-surface-dark"
                                <?= ($data['id_pelanggan'] == $p['id_pelanggan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nama']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <?php if (isset($errors['id_pelanggan'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['id_pelanggan'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="id_kendaraan" class="block mb-2 text-sm font-medium text-text-primary-dark">Kendaraan</label>
                    <select
                        id="id_kendaraan"
                        name="id_kendaraan"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white appearance-none
                               <?= isset($errors['id_kendaraan']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                    >
                        <option value="" class="bg-surface-dark text-text-secondary-dark">-- Pilih Kendaraan --</option>
                        <?php mysqli_data_seek($kendaraan, 0); // Reset pointer loop ?>
                        <?php while($k = $kendaraan->fetch_assoc()): ?>
                            <option value="<?= $k['id_kendaraan'] ?>" class="bg-surface-dark"
                                <?= ($data['id_kendaraan'] == $k['id_kendaraan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['merk']) ?> (<?= htmlspecialchars($k['no_plat']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <?php if (isset($errors['id_kendaraan'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['id_kendaraan'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="tgl_sewa" class="block mb-2 text-sm font-medium text-text-primary-dark">Tanggal Sewa</label>
                    <input
                        type="date"
                        id="tgl_sewa"
                        name="tgl_sewa"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark
                               <?= isset($errors['tgl_sewa']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['tgl_sewa'] ?? '') ?>"
                    >
                    <?php if (isset($errors['tgl_sewa'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['tgl_sewa'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="tgl_kembali" class="block mb-2 text-sm font-medium text-text-primary-dark">Tanggal Kembali</label>
                    <input
                        type="date"
                        id="tgl_kembali"
                        name="tgl_kembali"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark
                               <?= isset($errors['tgl_kembali']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['tgl_kembali'] ?? '') ?>"
                    >
                    <?php if (isset($errors['tgl_kembali'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['tgl_kembali'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="total_biaya" class="block mb-2 text-sm font-medium text-text-primary-dark">Total Biaya (Rp)</label>
                    <input
                        type="number"
                        id="total_biaya"
                        name="total_biaya"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark
                               <?= isset($errors['total_biaya']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['total_biaya'] ?? '') ?>"
                        placeholder="Masukkan hanya angka, cth: 500000"
                    >
                    <?php if (isset($errors['total_biaya'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['total_biaya'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="index.php?page=transaksi" class="px-5 py-2.5 text-sm font-medium bg-white/10 border border-white/20 text-text-secondary-dark rounded-lg shadow-sm hover:bg-white/20 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all duration-300">
                        <span class="material-symbols-outlined text-base">save</span>
                        Update
                    </button>
                </div>
            </form>
        </div>

<?php
include 'footer.php';
?>