<?php
include 'header.php';
?>



    <main class="flex-1 p-8 overflow-y-auto">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-white">Tambah Data Pembayaran</h2>
            <p class="text-text-secondary-dark mt-1">Lengkapi detail pembayaran di bawah ini.</p>
        </div>

        <div class="bg-black/30 backdrop-blur-sm p-6 md:p-8 rounded-xl shadow-2xl border border-white/10 max-w-2xl mx-auto">

            <form action="index.php?page=pembayaran&action=create" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken() ?>">

                <div>
                    <label for="id_sewa" class="block mb-2 text-sm font-medium text-text-primary-dark">Transaksi (ID Sewa)</label>
                    <select
                        id="id_sewa"
                        name="id_sewa"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white appearance-none
                               <?= isset($errors['id_sewa']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                    >
                        <option value="" class="bg-surface-dark text-text-secondary-dark">-- Pilih Transaksi --</option>
                        <?php mysqli_data_seek($transaksi, 0); // Reset pointer loop ?>
                        <?php while($t = $transaksi->fetch_assoc()): ?>
                            <option value="<?= $t['id_sewa'] ?>" class="bg-surface-dark"
                                <?= ($data['id_sewa'] == $t['id_sewa']) ? 'selected' : '' ?>>
                                #<?= $t['id_sewa'] ?> - <?= htmlspecialchars($t['nama_pelanggan']) ?> (<?= htmlspecialchars($t['merk_kendaraan']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <?php if (isset($errors['id_sewa'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['id_sewa'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="tgl_bayar" class="block mb-2 text-sm font-medium text-text-primary-dark">Tanggal Bayar</label>
                    <input
                        type="date"
                        id="tgl_bayar"
                        name="tgl_bayar"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark
                               <?= isset($errors['tgl_bayar']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['tgl_bayar'] ?? '') ?>"
                    >
                    <?php if (isset($errors['tgl_bayar'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['tgl_bayar'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="jumlah_bayar" class="block mb-2 text-sm font-medium text-text-primary-dark">Jumlah Bayar (Rp)</label>
                    <input
                        type="number"
                        id="jumlah_bayar"
                        name="jumlah_bayar"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white placeholder:text-text-secondary-dark
                               <?= isset($errors['jumlah_bayar']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                        value="<?= htmlspecialchars($data['jumlah_bayar'] ?? '') ?>"
                        placeholder="Masukkan hanya angka, cth: 150000"
                    >
                    <?php if (isset($errors['jumlah_bayar'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['jumlah_bayar'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="metode_bayar" class="block mb-2 text-sm font-medium text-text-primary-dark">Metode Bayar</label>
                    <select
                        id="metode_bayar"
                        name="metode_bayar"
                        class="w-full px-4 py-3 border bg-white/5 rounded-lg focus:ring-primary transition-all duration-300 text-white appearance-none
                               <?= isset($errors['metode_bayar']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-white/20 focus:border-primary focus:ring-primary' ?>"
                    >
                        <option value="" class="bg-surface-dark text-text-secondary-dark">-- Pilih Metode --</option>
                        <option value="tunai" class="bg-surface-dark" <?= ($data['metode_bayar'] == 'tunai') ? 'selected' : '' ?>>Tunai</option>
                        <option value="kartu" class="bg-surface-dark" <?= ($data['metode_bayar'] == 'kartu') ? 'selected' : '' ?>>Kartu</option>
                        <option value="transfer" class="bg-surface-dark" <?= ($data['metode_bayar'] == 'transfer') ? 'selected' : '' ?>>Transfer</option>
                    </select>
                    <?php if (isset($errors['metode_bayar'])): ?>
                        <p class="text-red-400 text-xs italic mt-2"><?= $errors['metode_bayar'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="index.php?page=pembayaran" class="px-5 py-2.5 text-sm font-medium bg-white/10 border border-white/20 text-text-secondary-dark rounded-lg shadow-sm hover:bg-white/20 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all duration-300">
                        <span class="material-symbols-outlined text-base">save</span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>

<?php
include 'footer.php';
?>