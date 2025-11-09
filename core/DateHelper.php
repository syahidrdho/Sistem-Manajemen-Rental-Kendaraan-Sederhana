<?php
// File: core/DateHelper.php (Versi Ultra-Bersih - Siap GitHub)

class DateHelper {
    public static function indoFull(string $datetime): string {
        $bulan = [
            1 => 'Januari', 
            'Februari', 
            'Maret', 
            'April', 
            'Mei', 
            'Juni', 
            'Juli', 
            'Agustus', 
            'September', 
            'Oktober', 
            'November', 
            'Desember'
        ];
        
        $parts = explode(' ', $datetime);
        if (count($parts) < 2) {
            $parts[1] = '00:00:00'; 
        }

        $tanggal_str = $parts[0];
        $waktu_str = $parts[1];

        $tgl_parts = explode('-', $tanggal_str);
        if (count($tgl_parts) !== 3) {
            return $datetime; 
        }

        $tahun = $tgl_parts[0];
        $bulan_index = (int)$tgl_parts[1];
        $hari = $tgl_parts[2];

        return $hari . ' ' . $bulan[$bulan_index] . ' ' . $tahun . ', ' . $waktu_str;
    }
}
?>