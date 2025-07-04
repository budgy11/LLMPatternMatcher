#VARIABLE CLASSIFICATIONS
#for variables that usually store sensitive data in code
with open('./variables/sensitive_vars.txt') as fh:
    sensitive = fh.read().splitlines()

#for variables that usually take user input 
with open('./variables/dangerous_vars.txt') as fh:
	dangerous = fh.read().splitlines()

with open('./variables/safe_vars.txt') as fh:
	safe = fh.read().splitlines()

#callbacks are bad - https://github.com/FloeDesignTechnologies/phpcs-security-audit/blob/master/Security/Sniffs/BadFunctions/CallbackFunctionsSniff.php#L32
callback_function_list = [
			'ob_start', 'array_diff_uassoc', 'array_diff_ukey', 'array_filter', 'array_intersect_uassoc', 'array_intersect_ukey', 'array_map', 'array_reduce',
			'array_udiff_assoc', 'array_udiff_uassoc', 'array_udiff', 'array_uintersect_assoc', 'array_uintersect_uassoc', 'array_uintersect', 'array_walk_recursive',
			'array_walk', 'assert_options', 'uasort', 'uksort', 'usort', 'preg_replace_callback', 'spl_autoload_register', 'iterator_apply', 'call_user_func',
			'call_user_func_array', 'register_shutdown_function', 'register_tick_function', 'set_error_handler', 'set_exception_handler', 'session_set_save_handler',
			'sqlite_create_aggregate', 'sqlite_create_function'
]

#filesystem functions are bad - https://github.com/FloeDesignTechnologies/phpcs-security-audit/blob/master/Security/Sniffs/Utils.php#L69
filesystem_function_list = [
            'basename', 'chgrp', 'chmod', 'chown', 'clearstatcache', 'copy', 'delete', 'dirname', 'disk_free_space', 'disk_total_space',
			'diskfreespace', 'fclose', 'feof', 'fflush', 'fgetc', 'fgetcsv', 'fgets', 'fgetss', 'file_exists', 'file_get_contents', 'file_put_contents',
			'file', 'fileatime', 'filectime', 'filegroup', 'fileinode', 'filemtime', 'fileowner', 'fileperms', 'filesize', 'filetype', 'flock', 'fnmatch',
			'fopen', 'fpassthru', 'fputcsv', 'fputs', 'fread', 'fscanf', 'fseek', 'fstat', 'ftell', 'ftruncate', 'fwrite', 'glob', 'is_dir', 'is_executable',
			'is_file', 'is_link', 'is_readable', 'is_uploaded_file', 'is_writable', 'is_writeable', 'lchgrp', 'lchown', 'link', 'linkinfo', 'lstat', 'mkdir',
			'move_uploaded_file', 'parse_ini_file', 'parse_ini_string', 'pathinfo', 'readfile', 'readlink', 'realpath_cache_get',
			'realpath_cache_size', 'realpath', 'rename', 'rewind', 'rmdir', 'set_file_buffer', 'stat', 'symlink', 'tempnam', 'tmpfile', 'touch', 'umask', 'unlink',
            'chdir', 'chroot', 'dir', 'opendir', 'scandir','finfo_open','xattr_get', 'xattr_list', 'xattr_remove', 'xattr_set', 'xattr_supported','readgzfile', 'gzopen', 'gzfile',
            'getimagesize', 'imagecreatefromgd2', 'imagecreatefromgd2part', 'imagecreatefromgd', 'imagecreatefromgif', 'imagecreatefromjpeg', 'imagecreatefrompng',
			'imagecreatefromwbmp', 'imagecreatefromwebp', 'imagecreatefromxbm', 'imagecreatefromxpm',
			'imagepsloadfont', 'jpeg2wbmp', 'png2wbmp','exif_imagetype', 'exif_read_data', 'exif_thumbnail', 'read_exif_data','hash_file', 'hash_hmac_file', 'hash_update_file',
            'highlight_file', 'php_check_syntax', 'php_strip_whitespace', 'show_source','get_meta_tags', 'hash_file', 'hash_hmac_file', 'hash_update_file', 'md5_file', 'sha1_file',
			'bzopen','curl_exec','curl_multi_exec'
]
functionhandling_function_list = [
			'create_function', 'forward_static_call', 'forward_static_call_array','function_exists', 'register_shutdown_function', 'register_tick_function'
]

def gen_regex_var_portion(var_list):
    var_regex = "("
    for var in var_list:
        var_regex += var
        var_regex += "|"
    var_regex = var_regex[:-1] + ")" #replace final '|' with ')'
    return var_regex

safe_vars_regex = gen_regex_var_portion(safe)
#print(safe_vars_regex)

dangerous_vars_regex = gen_regex_var_portion(dangerous)
#print(dangerous_vars_regex)

sensitive_vars_regex = gen_regex_var_portion(sensitive)
#print(sensitive_vars_regex)

callback_func_regex = gen_regex_var_portion(callback_function_list)
#print(callback_func_regex)

filesystem_func_regex = gen_regex_var_portion(filesystem_function_list)
#print(filesystem_func_regex)

functionhandling_func_regex = gen_regex_var_portion(functionhandling_function_list)
#print(functionhandling_func_regex)
