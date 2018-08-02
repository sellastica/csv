<?php
namespace Sellastica\Csv;

class CsvReaderFactory
{
    /**
	 * @param string $encoding
     * @throws \RuntimeException
	 * @throws \InvalidArgumentException
     */
    private static function initialize(string $encoding)
    {
        if (!ini_get('auto_detect_line_endings')) {
            ini_set('auto_detect_line_endings', 1);
        }
    }

	/**
	 * @param string $path
	 * @param string $encoding
	 * @return \League\Csv\Reader
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 */
	public static function createFromPath(string $path, string $encoding)
	{
        self::initialize($encoding);
		$reader = \League\Csv\Reader::createFromPath($path);
		$reader->setDelimiter(';');

		switch ($encoding) {
			case 'win1250':
				//class is not in the vendor namespace
				require_once (__DIR__ . '/windows1250_to_utf8_filter.php');

				if (!stream_filter_register('convert.windows1250_to_utf8', 'windows1250_to_utf8_filter')) {
					throw new \RuntimeException('Failed to register convert.windows1250_to_utf8 stream filter');
				}

				$reader->addStreamFilter('convert.windows1250_to_utf8');
				break;
			case 'utf8':
				break;
			default:
				throw new \InvalidArgumentException("Unknown encoding $encoding");
				break;
		}

		return $reader;
	}
}