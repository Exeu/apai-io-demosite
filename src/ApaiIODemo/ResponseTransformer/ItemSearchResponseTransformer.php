<?php
namespace ApaiIODemo\ResponseTransformer;

use ApaiIO\ResponseTransformer\ResponseTransformerInterface;

class ItemSearchResponseTransformer implements ResponseTransformerInterface
{
	public function transform($response)
	{
		$domDocument = new \DOMDocument("1.0", "UTF-8");
		$domDocument->loadXML($response);

		$xpath = new \DOMXPath($domDocument);
		$xpath->registerNamespace("amazon", "http://webservices.amazon.com/AWSECommerceService/2011-08-01");

		$rootElements = $xpath->query('//amazon:ItemSearchResponse/amazon:Items/amazon:Item');

		$output = array();
		foreach ($rootElements as $element)
		{
			$xml = $element->ownerDocument->saveXML($element);
			$elementDom = new \DOMDocument("1.0", "UTF-8");
			$elementDom->loadXML($xml);

			$elementXpath = new \DOMXPath($elementDom);

			$asinElement = $elementXpath->query('//Item/ASIN');
			$detailPageElement = $elementXpath->query('//Item/DetailPageURL');
			$largeImageElement = $elementXpath->query('//Item/LargeImage/URL');
			$titleElement = $elementXpath->query('//Item/ItemAttributes/Title');

			$largeImageUrl = $largeImageElement->item(0)->nodeValue;
			$asin = $asinElement->item(0)->nodeValue;
			$detailPageUrl = $detailPageElement->item(0)->nodeValue;
			$title = $titleElement->item(0)->nodeValue;

			if (true === empty($title) || true === empty($asin)) {
				continue;
			}

			$data = array(
				'title' => (mb_strlen($title) > 30) ?  substr($title,0, 30) : $title,
				'url' => $detailPageUrl,
				'img' => $largeImageUrl,
				'price' => 1,
				'asin' => $asin
			);

			array_push($output, $data);
		}

		return $output;
	}
}