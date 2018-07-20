<?php
class windows1250_to_utf8_filter extends \php_user_filter
{
	function filter($in, $out, &$consumed, $closing)
	{
		while ($bucket = stream_bucket_make_writeable($in)) {
			$bucket->data = iconv('windows-1250', 'utf-8//IGNORE', $bucket->data);
			$consumed += $bucket->datalen;
			stream_bucket_append($out, $bucket);
		}

		return PSFS_PASS_ON;
	}
}