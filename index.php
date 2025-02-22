<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Шакиров Димитрий Раисович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Энзель Аида Маратовна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

///////////////////////////////////////////////////////
function getFullnameFromParts($example_persons_array) {
    $fullname1 = [];

    foreach ($example_persons_array as $person) {
        $fullname1[] = $person['fullname'];
    }
    /// Вывод в виде строк, содержащих сразу и фамилию, и имя, и отчество
    //print_r($fullname1);    
    return $fullname1;
}

$result1 = getFullnameFromParts($example_persons_array);


////////////////////////////////////////////////////////
function getPartsFromFullname($example_persons_array) {
    $fullname = [];

    foreach ($example_persons_array as $person) {
        // Разбиваем строку на части
        $parts = explode(' ', $person['fullname']);

        $surname = $parts[0];
        $name = $parts[1];
        $patronymic = $parts[2];

        // Добавляем элементы в новый массив
        $fullname[] = [$name, $surname, $patronymic];        
    }
    /// Вывод в виде строк, содержащих по сточно имя, фамилию и отчество
     //print_r($fullname);
     
    return $fullname;
}
$result2 = getFullnameFromParts($example_persons_array);
/////////////////////////////////////////////////////////////////
function getShortName($parts) {
    $name = $parts[0];
    $surname = $parts[1];

    // Форматируем строку вида «Иван И.»
    $formattedName = $name . ' ' . substr($surname, 0, 2) . '.';
    return $formattedName;
}

$shortNames = getPartsFromFullname($example_persons_array);

// Выводим строку вида «Иван И.»
foreach ($shortNames as $parts) {
    //echo getShortName($parts) . PHP_EOL;
    
}
///////////////////////////////////////////////////////////
$fullnames = getPartsFromFullname($example_persons_array);

function getGenderFromName($fullnames) {
    $surnamesEndV = [];
    
    foreach ($fullnames as $parts) {
        $surname = $parts[1];
        $name = $parts[0];
        $patronymic= $parts[2];
        $genderIndicator = 0;
        
        // Проверяем персоны на "...."
        if (str_ends_with($name, 'н')) {
            $genderIndicator++;
        }
        if (str_ends_with($name, 'й') ) {
            $genderIndicator++;
        }
         if (str_ends_with($name, 'а')) {
             $genderIndicator--;
         }
        if (str_ends_with($surname, 'в') ) {
                $genderIndicator++;
        } 
        if (str_ends_with($surname, 'ва') ) {
            $genderIndicator--;
        }
        if (str_ends_with($patronymic, 'ич') ) {
                $genderIndicator++;
        }        
        if (str_ends_with($patronymic, 'вна') ) {
            $genderIndicator--;
        }        
        if ($genderIndicator > 0 ) {
            $formattedName = $surname . ' ' . substr($name, 0, 2) . '.' . substr($patronymic, 0, 2) .' '. '1' . ' ' . '(Мужской пол)';
            $surnamesEndV[] = $formattedName;
            
        }
        if ($genderIndicator < 0 ) {
            $formattedName = $surname . ' ' . substr($name, 0, 2) . '.' . substr($patronymic, 0, 2) .' '. '-1' . ' ' . '(Женский пол)';
            $surnamesEndV[] = $formattedName;
        }
        if ($genderIndicator == 0 ) {
            $formattedName = $surname . ' ' . substr($name, 0, 2) . '.' . substr($patronymic, 0, 2) .' '. '0' . ' ' . '(неопределённый пол)';
            $surnamesEndV[] = $formattedName;
        }               
            //print_r(($genderIndicator). PHP_EOL);       
    }
    return $surnamesEndV;
}

$surnamesEndV = getGenderFromName($fullnames);

// Выводим определения пола по ФИО
foreach ($surnamesEndV as $surname) {
   //echo $surname . PHP_EOL;
}

//////////////////////////////////////////////////////////////

function getGenderDescription($fullnames) {
    $genderData = getGenderFromName($fullnames);
    $maleCount = 0;
    $femaleCount = 0;
    $unknownCount = 0;
    $totalCount = count($genderData);

    foreach ($genderData as $entry) {
        if (strpos($entry, '(Мужской пол)') !== false) {
            $maleCount++;
        } elseif (strpos($entry, '(Женский пол)') !== false) {
            $femaleCount++;
        } elseif (strpos($entry, '(неопределённый пол)') !== false) {
            $unknownCount++;
        }
    }

    $malePercentage = ($maleCount / $totalCount) * 100;
    $femalePercentage = ($femaleCount / $totalCount) * 100;
    $unknownPercentage = ($unknownCount / $totalCount) * 100;

    return [
        'Мужчины' => sprintf('%.1f', $malePercentage) . '%',
        'Женщины' => sprintf('%.1f', $femalePercentage) . '%',
        'Не удалось определить' => sprintf('%.1f', $unknownPercentage) . '%'
    ];
}

$genderDescription = getGenderDescription($fullnames);


// Выводим определение возрастно-полового состава
echo "Гендерный состав аудитории:\n";
echo "---------------------------\n";
echo "Мужчины - " . $genderDescription['Мужчины'] . "\n";
echo "Женщины - " . $genderDescription['Женщины'] . "\n";
echo "Не удалось определить - " . $genderDescription['Не удалось определить'] . "\n";

////////////////////////////////////////////////////

function getPerfectPartner($fullnames) {
    // Функция для определения пола по ФИО    
    function getGenderFromName1($fullname) {
        $name = $fullname[0];
        $surname = $fullname[1];
        $patronymic = $fullname[2];
        $genderIndicator = 0;

        // Проверяем имя
        if (str_ends_with($name, 'н')) {
            $genderIndicator++;
        }
        if (str_ends_with($name, 'й') ) {
            $genderIndicator++;
        }
        if (str_ends_with($name, 'а')) {
            $genderIndicator--;
        }

        // Проверяем фамилию
        if (str_ends_with($surname, 'в')) {
            $genderIndicator++;
        }
        if (str_ends_with($surname, 'ва')) {
            $genderIndicator--;
        }

        // Проверяем отчество
        if (str_ends_with($patronymic, 'ич')) {
            $genderIndicator++;
        }
        if (str_ends_with($patronymic, 'вна')) {
            $genderIndicator--;
        }

        if ($genderIndicator > 0) {
            return 'Мужской пол';
        } elseif ($genderIndicator < 0) {
            return 'Женский пол';
        } else {
            return 'Неопределённый пол';
        }
    }

    // Выбираем случайные персоны
    do {
        $randomIndex1 = array_rand($fullnames);
        $randomIndex2 = $randomIndex1;
        while ($randomIndex2 === $randomIndex1) {
            $randomIndex2 = array_rand($fullnames);
        }       
        $person1 = $fullnames[$randomIndex1];
        $person2 = $fullnames[$randomIndex2];       
        // Определяем пол выбранных персон
        $gender1 = getGenderFromName1($person1);
        $gender2 = getGenderFromName1($person2);
       
    } while (($gender1 === $gender2) || ($gender1 === 'Неопределённый пол') || ($gender2 === 'Неопределённый пол'));

    // Проверяем, что персоны противоположного пола
    if (($gender1 === 'Мужской пол' && $gender2 === 'Женский пол') || ($gender1 === 'Женский пол' && $gender2 === 'Мужской пол')) {
        // Форматируем ФИО
        $formattedName1 = $person1[1] . ' ' . substr($person1[0], 0, 2) . '.';
        $formattedName2 = $person2[1] . ' ' . substr($person2[0], 0, 2) . '.';

        // Генерируем случайный процент совместимости
        $compatibility = mt_rand(5000, 10000) / 100;

        // Формируем результат
        $result = $formattedName1 . ' + ' . $formattedName2 . ' = ' . "\n♡ Идеально на " . sprintf('%.2f', $compatibility) . "% ♡";
        return $result;
    } else {
        return "Не удалось найти пару противоположного пола.";
    }
}

echo getPerfectPartner($fullnames);