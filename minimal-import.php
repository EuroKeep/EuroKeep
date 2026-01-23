<?php declare(strict_types = 1);


$content = file_get_contents('bargeld.csv');
# var_dump($content);

$rows = explode(chr(10), $content);
# var_dump($rows);

$columns = [];
$inserts = [];
foreach ($rows as $rowNumber => $row) {
    $row = str_replace("'", "\'", $row);
    $cols = explode(';', $row);
    if ($rowNumber === 0) {
        $columns = $cols;
        continue;
    }

    $myInsert = [];
    foreach($columns as $columnIndex => $column) {
        $myInsert[$column] = $cols[$columnIndex];
    }

    $inserts[]= $myInsert;
}

$sql = <<<SQL
INSERT INTO `movement` (`account_id`, `user_id`, `transactiontype_string`, `created`,  `modified`, `cardtime`, `balance_value`, `target_name`, `target_iban`, `target_bic`, `usage`, `comment`,`category_string_1`, `category_string_2`)
 VALUES
SQL;
foreach ($inserts as $insert) {
    if (!empty($insert['DATUM'])) {
        $buchungsdatum = new \DateTime($insert['DATUM']);
        $insert['DATUM'] = $buchungsdatum->format('Y-m-d H:i:s');
    }


    $insert['BETRAG'] = (float) $insert['BETRAG'];



    $sql .= chr(10) . <<<SQL
( 13, 1, 'bargeld', '{$insert['DATUM']}', '{$insert['DATUM']}', '{$insert['DATUM']}', {$insert['BETRAG']}, 'BARGELD', '', '', '{$insert['BETREFF']}', '', '', ''),
SQL;
}

echo $sql;

file_put_contents('bargeld2.sql', $sql);