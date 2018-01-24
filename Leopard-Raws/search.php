<?php
class TorrentSearchLeopard
{
	private $qurl = 'http://leopard-raws.org/rss.php?search=';
	public function __construct()
	{
	}

	public function prepare($curl, $query)
	{
		$url = $this->qurl . urlencode($query);
		curl_setopt($curl, CURLOPT_URL, $url);
	}

	public function size_format($sizestr)
	{
		$size_map = array(
			"KiB" => 1024,
			"MiB" => 1048576,
			"GiB" => 1073741824,
		);
		foreach ($size_map as $n => $mux) {
			if (strstr($sizestr, $n)) {
				$sizestr = floatval($sizestr) * $mux;
				break;
			}
		}
		return $sizestr;
	}

	public function parse($plugin, $response)
	{
		$xml = simplexml_load_string($response);
		$count = 0;
		foreach ($xml->channel->item as $child) {
			$title = (string)$child->title;
			$download = (string)$child->link;
			$size = (int)0;
			$datetime = (string)$child->pubDate;
			$page = (string)$child->link;
			$hash = (string)$child->guid;
			$seeds = (int)0;
			$leechs = (int)0;
			$category = "";
			$count++;
			$plugin->addResult($title, $download, $size, $datetime, $page, $hash, $seeds, $leechs, $category);
		}
		return $count;
	}
}
?>
