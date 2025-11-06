<?php

/**
 * Funcion que genera un código de verificación aleatorio de N dígitos
 * @param int $longitud Cantidad de dígitos (por defecto 6)
 * @return string Código generado
 */
function generarCodigoVerificacion($longitud = 6) {
    return str_pad(random_int(0, pow(10, $longitud) - 1), $longitud, '0', STR_PAD_LEFT);
}

/**
 * Funcion que valida que un código tenga el formato correcto (solo números de 6 dígitos)
 * @param string $codigo Código a validar
 * @param int $longitud Longitud esperada del código
 * @return bool True si es válido, false si no
 */
function validarFormatoCodigo($codigo, $longitud = 6) {
    return preg_match('/^\d{' . $longitud . '}$/', $codigo);
}

/**
 * Funcion para calcular la fecha de expiración del código (15 minutos desde ahora)
 * @param int $minutos Minutos de validez (por defecto 15)
 * @return string Fecha de expiración en formato MySQL
 */
function calcularExpiracion($minutos = 15) {
    return date('Y-m-d H:i:s', strtotime("+{$minutos} minutes"));
}

/**
 * Funcion para verificar si un código ha expirado
 * @param string $fechaExpiracion Fecha de expiración del código
 * @return bool True si expiró, false si aún es válido
 */
function codigoExpirado($fechaExpiracion) {
    if (!$fechaExpiracion) {
        return true; // Si no hay fecha, considerar expirado
    }
    
    $ahora = new DateTime();
    $expira = new DateTime($fechaExpiracion);
    
    return $ahora > $expira; // True si la fecha actual es mayor (ya expiró)
}