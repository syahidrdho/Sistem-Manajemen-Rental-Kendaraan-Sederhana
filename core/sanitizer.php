<?php
// File: core/Sanitizer.php (Versi Ultra-Bersih - Siap GitHub)

class Sanitizer {
    
    public static function text($input) {
        $cleaned = strip_tags(trim($input));
        $cased = ucwords(strtolower($cleaned));
        return htmlspecialchars($cased, ENT_QUOTES, 'UTF-8');
    }

    public static function alphanum($input) {
        $input = preg_replace("/[^a-zA-Z0-9\s]/", "", $input);
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function name($input) {
        $cleaned = preg_replace("/[^a-zA-Z\s]/", "", $input);
        $cleaned = trim($cleaned);
        $cased = ucwords(strtolower($cleaned));
        return htmlspecialchars($cased, ENT_QUOTES, 'UTF-8');
    }

    public static function numeric($input) {
        return preg_replace("/[^0-9]/", "", $input);
    }
    
    public static function phone($input) {
        return preg_replace("/[^0-9]/", "", $input);
    }
    
    public static function formatPhone($phone) {
        $clean_phone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($clean_phone)) {
            return "";
        }
        
        if (substr($clean_phone, 0, 2) === '62') {
            $clean_phone = '0' . substr($clean_phone, 2);
        } 
        else if (substr($clean_phone, 0, 1) !== '0') {
            $clean_phone = '0' . $clean_phone;
        }

        if (strlen($clean_phone) < 10) {
            return $clean_phone; 
        }

        $formatted = substr($clean_phone, 0, 4) . '-' . 
                       substr($clean_phone, 4, 4) . '-' . 
                       substr($clean_phone, 8);
        return $formatted;
    }
}
?>