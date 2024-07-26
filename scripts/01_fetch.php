<?php
$dateStartText = '2024-07-29';
while (1) {
    $dateEnd = strtotime($dateStartText);
    $dateStart = strtotime('-7 days', $dateEnd);
    $dateEndText = date('Y-m-d', $dateEnd);
    $dateStartText = date('Y-m-d', $dateStart);
    $text = exec("curl 'https://www.dprcflood.org.tw/SGDS/WS/FloodComputeWS.asmx/GetFloodComputeForLightweightData' \
      -H 'accept: application/json, text/javascript, */*; q=0.01' \
      -H 'accept-language: zh-TW,zh;q=0.9,en-US;q=0.8,en;q=0.7' \
      -H 'cache-control: no-cache' \
      -H 'content-type: application/json; charset=UTF-8' \
      -H 'origin: https://wrbswi.kcg.gov.tw' \
      -H 'pragma: no-cache' \
      -H 'priority: u=1, i' \
      -H 'referer: https://wrbswi.kcg.gov.tw/' \
      -H 'sec-ch-ua: \"Not/A)Brand\";v=\"8\", \"Chromium\";v=\"126\", \"Google Chrome\";v=\"126\"' \
      -H 'sec-ch-ua-mobile: ?0' \
      -H 'sec-ch-ua-platform: \"Linux\"' \
      -H 'sec-fetch-dest: empty' \
      -H 'sec-fetch-mode: cors' \
      -H 'sec-fetch-site: cross-site' \
      -H 'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36' \
      --data-raw '{\"beginDT\":\"{$dateStartText}T13:00:00.000Z\",\"endDT\":\"{$dateEndText}T13:00:00.000Z\",\"computeDistance\":500,\"CountyID\":2,\"dataType\":0}'");
    $json = json_decode($text, true);
    if (empty($json['d'])) {
        exit();
    }
    foreach ($json['d'] as $case) {
        if (!empty($case['NOTIFICATION_Data']['PK_ID'])) {
            $caseFile = dirname(__DIR__) . '/docs/json/case/' . $case['NOTIFICATION_Data']['PK_ID'] . '.json';
            file_put_contents($caseFile, json_encode($case, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}
