<?php
    use Aws\Credentials\CredentialProvider;
    use Aws\Textract\TextractClient;
    include "vendor/autoload.php";

    $client = new TextractClient([
        'region' => 'us-west-2',
        'version' => '2018-06-27',
        'credentials' => [
            'key'    => "AKIA4GC4GUJWXMLOE74G",
            'secret' => "9Ae18rH7gB12aEj/bfMPTIrgPbksDGXlk0VRqfvm",
        ]
    ]);

    $filename = "sample.pdf";
    $file = fopen($filename, "rb");
    $contents = fread($file, filesize($filename));
    fclose($file);
    $options = [
        'Document' => [
            'Bytes' => $contents
        ],
        'FeatureTypes' => ['FORMS'], // REQUIRED
    ];
    $result = $client->analyzeDocument($options);
    // If debugging:
    // echo print_r($result, true);
    $blocks = $result['Blocks'];
    // Loop through all the blocks:
    foreach ($blocks as $key => $value) {
        if (isset($value['BlockType']) && $value['BlockType']) {
            $blockType = $value['BlockType'];
            if (isset($value['Text']) && $value['Text']) {
                $text = $value['Text'];
                if ($blockType == 'WORD') {
                    echo "Word: ". print_r($text, true) . "\n";
                } else if ($blockType == 'LINE') {
                    echo "Line: <b>". print_r($text, true) . "</b><br>\n";
                }
            }
        }
    }