<?php

namespace App\Rss;
use Symfony\Component\Validator\Constraints\DateTime;

class Xml
{
    public static function generate($shows)
    {
        $xml = <<<xml
			<?xml version='1.0' encoding='UTF-8'?>
			<rss version='2.0'>
			<channel>
			<title>Prochaines representations</title>
			<link>https://localhost:8000/rss</link>
			<description>Mise a jours des prochaines representations</description>
			<language>fr-be</language>
			xml;
			foreach ($shows as $show) {
				$title = $show->getTitle();
				$url = $show->getId();
				$slug = self::uri($show->getSlug());
				$location = $show->getLocation()->getDesignation();
				$representations = $show->getRepresentations();
				$representation = '';
				foreach($representations as $rep){
					$representation = $rep->getSchedule()->format('d/m/Y h:s');
				}
				$price = $show->getPrice();
				$date = new \DateTime();
				$pubDate = $date->format('d/m/Y h:s');
				$xml .= <<<xml
				<item>
					<title>{$title}</title>
					<link>http://localhost:8000/{$url}</link>
					<description>
						<![CDATA[
						Designation : $slug
						<br />
						Location : $location
						<br />
						Date : $representation
						<br />
						Prix : $price euros
						]]>
					</description>
					
					<pubDate>{$pubDate}</pubDate>
				</item>
			}
xml;
		}
    $xml .= "</channel></rss>";

        return $xml;
	}
	
	private static function uri($string) {
        return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
    }
}
