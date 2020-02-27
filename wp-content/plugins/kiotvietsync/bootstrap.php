<?php
function kiotviet_sync_get_data($key, $default = null)
{
    return get_option('kiotviet_sync_' . $key, $default);
}

/**
 * @param $key
 * @param $value
 * @param string $autoload
 * @return bool
 */
function kiotviet_sync_set_data($key, $value, $autoload = 'yes')
{
    return update_option('kiotviet_sync_' . $key, $value, $autoload);
}

function kiotviet_sync_delete_data($key)
{
    return delete_option('kiotviet_sync_' . $key);
}

function kiotviet_sync_get_request($key, $default = '')
{
    if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }

    return $default;
}

function kiotviet_sync_get_current_time()
{
    return date('Y-m-d H:i:s', time());
}

function kiotviet_sync_get_image_mime_type($image_path)
{
    $mimes  = array(
        IMAGETYPE_GIF => "gif",
        IMAGETYPE_JPEG => "jpg",
        IMAGETYPE_PNG => "png",
        IMAGETYPE_SWF => "swf",
        IMAGETYPE_PSD => "psd",
        IMAGETYPE_BMP => "bmp",
        IMAGETYPE_TIFF_II => "tiff",
        IMAGETYPE_TIFF_MM => "tiff",
        IMAGETYPE_JPC => "jpc",
        IMAGETYPE_JP2 => "jp2",
        IMAGETYPE_JPX => "jpx",
        IMAGETYPE_JB2 => "jb2",
        IMAGETYPE_SWC => "swc",
        IMAGETYPE_IFF => "iff",
        IMAGETYPE_WBMP => "wbmp",
        IMAGETYPE_XBM => "xbm",
        IMAGETYPE_ICO => "ico"
    );

    if (($image_type = exif_imagetype($image_path))
        && (array_key_exists($image_type, $mimes))
    ) {
        return $mimes[$image_type];
    } else {
        return FALSE;
    }
}

function kiotviet_sync_decode_json($string){
    return json_decode(html_entity_decode(stripslashes($string)), true);
}

function kiotviet_sync_do_insert($table, $place_holders, $values)
{
    global $wpdb;
    $query = "INSERT INTO `$table` (`option_name`, `option_value`, `option_created`, `option_edit`, `option_user`) VALUES ";
    $query .= implode(', ', $place_holders);
    $sql = $wpdb->prepare("$query ", $values);

    if ($wpdb->query($sql)) {
        return true;
    } else {
        return false;
    }
}
