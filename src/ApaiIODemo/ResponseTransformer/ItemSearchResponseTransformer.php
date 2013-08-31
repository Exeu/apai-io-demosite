<?php
/*
 * Copyright 2013 Jan Eichhorn <exeu65@googlemail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
        foreach ($rootElements as $element) {
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
