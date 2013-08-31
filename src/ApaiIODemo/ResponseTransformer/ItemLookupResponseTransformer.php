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

class ItemLookupResponseTransformer implements ResponseTransformerInterface
{
  public function transform($response)
  {
    $domDocument = new \DOMDocument("1.0", "UTF-8");
    $domDocument->loadXML($response);

    $xpath = new \DOMXPath($domDocument);
    $xpath->registerNamespace("amazon", "http://webservices.amazon.com/AWSECommerceService/2011-08-01");

    $rootElements = $xpath->query('//amazon:ItemLookupResponse/amazon:Items/amazon:Item');

    if ($rootElements->length <= 0) {
      return false;
    }

    $rootElement = $rootElements->item(0);

    $xml = $domDocument->saveXML($rootElement);

    $domDocumentItem = new \DOMDocument("1.0", "UTF-8");
    $domDocumentItem->loadXML($xml);

    $elementXpath = new \DOMXPath($domDocumentItem);

    $output = array(
      'features' => array(),
      'editoriealReviews' => array(),
      'offerSummary' => array('newPrice' => null, 'usedPrice' => null),
      'reviews' => null
    );

    $output['largeImageUrl'] = $elementXpath->query('//Item/LargeImage/URL')->item(0)->nodeValue;
    $output['asin'] = $elementXpath->query('//Item/ASIN')->item(0)->nodeValue;
    $output['detailPageUrl'] = $elementXpath->query('//Item/DetailPageURL')->item(0)->nodeValue;
    $output['title'] = $elementXpath->query('//Item/ItemAttributes/Title')->item(0)->nodeValue;
    $output['productGroup'] = $elementXpath->query('//Item/ItemAttributes/ProductGroup')->item(0)->nodeValue;

    $featureElements = $elementXpath->query('//Item/ItemAttributes/Feature');

    if ($featureElements->length > 0) {
      foreach ($featureElements as $featureElement) {
         $output['features'][] = $featureElement->nodeValue;
      }
    }

    $editorialReviews = $elementXpath->query('//Item/EditorialReviews/EditorialReview');

    if ($editorialReviews->length > 0) {
      foreach ($editorialReviews as $editorialReview) {
        $output['editoriealReviews'][] = $editorialReview->getElementsByTagName('Content')->item(0)->nodeValue;
      }
    }

    $offerSummary = $elementXpath->query('//Item/OfferSummary');

    if ($offerSummary->length > 0) {
        $offerSummaryElement = $offerSummary->item(0);

        $newPrice = $offerSummaryElement->getElementsByTagName('LowestNewPrice');

        if ($newPrice->length > 0) {
            $output['offerSummary']['newPrice'] = $newPrice->item(0)->getElementsByTagName('FormattedPrice')->item(0)->nodeValue;
        }

        $usedPrice = $offerSummaryElement->getElementsByTagName('LowestUsedPrice');

        if ($usedPrice->length > 0) {
            $output['offerSummary']['usedPrice'] = $usedPrice->item(0)->getElementsByTagName('FormattedPrice')->item(0)->nodeValue;
        }
    }

    $reviews = $elementXpath->query('//Item/CustomerReviews');

    if ($reviews->length > 0) {
        $output['reviews'] = $reviews->item(0)->getElementsByTagName('IFrameURL')->item(0)->nodeValue;
    }

    return $output;
  }
}
