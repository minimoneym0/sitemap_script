<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$dom = new domDocument("1.0", 'utf-8');
$urlset = $dom->createElement("urlset"); 
//$urlset->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
//$urlset->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:image','http://www.google.com/schemas/sitemap-image/1.1');

$arSelect = Array("ID", "NAME", "DETAIL_PAGE_URL", "DETAIL_PICTURE");
$arFilter = Array("IBLOCK_ID"=>34, "SECTION_ID"=>$arResult['ID'], "INCLUDE_SUBSECTIONS" => "Y"); //ID Инфоблока и ID раздела с элементами
$rsElement = CIBlockElement::GetList(Array("NAME" => "ASC"), $arFilter, false, Array("nPageSize"=>7500), $arSelect);
$arResult["ITEMS"] = array();
while($obElement = $rsElement->GetNextElement())
{
$arItem = $obElement->GetFields();
$arItem["PROPERTIES"] = $obElement->GetProperties();
$google_link =  'https://shop.cheaz.ru'.$arItem[DETAIL_PAGE_URL];

$fin_name = $arItem[NAME];

$timezone = date("Y-m-d H:i:s");
$pr = '0.7';

    $url = $dom->createElement("url"); 
    $loc = $dom->createElement("loc", $google_link);
    $url->appendChild($loc);

    $name = $dom->createElement("name", $fin_name);
    $url->appendChild($name);

    $lastmod = $dom->createElement("lastmod", $timezone);
    $url->appendChild($lastmod);

	$priority = $dom->createElement("priority", $pr);
    $url->appendChild($priority);

$urlset->appendChild($url);
};
$dom->appendChild($urlset);
$dom->save("sitemap.xml"); //в корне директории откуда запускаем скрипт
echo 'Готово';
