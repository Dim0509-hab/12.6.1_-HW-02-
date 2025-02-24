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



function getFullnameFromParts($surname, $name, $patronymic) {
    return $surname. ' '. $name. ' '. $patronymic;
   }

////////////////////////////////////////////////////////



function getPartsFromFullname($fullname) {
    $parts = explode(' ', $fullname);
    return [
    'surname' => $parts[0],
    'name' => $parts[1],
    'patronymic' => $parts[2]
    ];
   }



/////////////////////////////////////////////////////////////////


function getShortName($fullname) {
    // Разбиваем ФИО на части
    $parts = getPartsFromFullname($fullname);
   
    // Извлекаем имя и фамилию
    $name = $parts['name'];
    $surname = $parts['surname'];
   
    // Возвращаем строку вида «Иван И.»
    return $name. ' '. substr($surname, 0, 1). '.';
   }


///////////////////////////////////////////////////////////



function getGenderFromName($fullname) {
    // Разбиваем ФИО на части
    $parts = getPartsFromFullname($fullname);
   
    // Извлекаем фамилию, имя и отчество
    $surname = $parts['surname'];
    $name = $parts['name'];
    $patronymic = $parts['patronymic'];
   
    // Инициализируем суммарный признак пола
    $genderIndicator = 0;
   
    // Проверяем отчество
    if (str_ends_with($patronymic, 'ич')) {
    $genderIndicator++;
    } elseif (str_ends_with($patronymic, 'вна')) {
    $genderIndicator--;
    }
   
    // Проверяем имя
    if (str_ends_with($name, 'й') || str_ends_with($name, 'н')) {
    $genderIndicator++;
    } elseif (str_ends_with($name, 'а')) {
    $genderIndicator--;
    }
   
    // Проверяем фамилию
    if (str_ends_with($surname, 'в')) {
    $genderIndicator++;
    } elseif (str_ends_with($surname, 'ва')) {
    $genderIndicator--;
    }
   
    // Возвращаем результат в зависимости от суммарного признака пола
    if ($genderIndicator > 0) {
    return 1; // Мужской пол
    } elseif ($genderIndicator < 0) {
    return -1; // Женский пол
    } else {
    return 0; // Неопределённый пол
    }
   }

//////////////////////////////////////////////////////////////


function getGenderDescription($persons) {

    $maleCount = 0;
    $femaleCount = 0;
    $unknownCount = 0;
   
    
    foreach ($persons as $fullname) {
    $gender = getGenderFromName($fullname);
   
    if ($gender === 1) {
    $maleCount++;
    } elseif ($gender === -1) {
    $femaleCount++;
    } else {
    $unknownCount++;
    }
    }
   
    // Общее количество персон
    $totalCount = count($persons);
   
    
    $malePercent = ($totalCount > 0) ? ($maleCount / $totalCount) * 100 : 0;
    $femalePercent = ($totalCount > 0) ? ($femaleCount / $totalCount) * 100 : 0;
    $unknownPercent = ($totalCount > 0) ? ($unknownCount / $totalCount) * 100 : 0;
   
    // Округляем проценты до одного знака после запятой
    $malePercent = round($malePercent, 1);
    $femalePercent = round($femalePercent, 1);
    $unknownPercent = round($unknownPercent, 1);
   
    
    $result = "Гендерный состав аудитории:\n";
    $result .= "---------------------------\n";
    $result .= "Мужчины - ". $malePercent. "%\n";
    $result .= "Женщины - ". $femalePercent. "%\n";
    $result .= "Не удалось определить - ". $unknownPercent. "%\n";
   
    return $result;
   }


////////////////////////////////////////////////////

function getPerfectPartner($surname, $name, $patronymic, $persons) {
    // Приводим ФИО к привычному регистру
    $surname = ucwords(strtolower($surname));
    $name = ucwords(strtolower($name));
    $patronymic = ucwords(strtolower($patronymic));
   
    
    $fullname = getFullnameFromParts($surname, $name, $patronymic);
   
    // Определяем пол для ФИО
    $gender = getGenderFromName($fullname);
   
    // Выбираем случайного человека из массива
    do {
    $randomIndex = array_rand($persons);
    $randomPerson = $persons[$randomIndex];
    $randomGender = getGenderFromName($randomPerson);
    } while ($gender === $randomGender); // Повторяем выбор, если пол совпадает
   
    // Склеиваем ФИО случайного человека
    list($randomSurname, $randomName, $randomPatronymic) = explode(' ', $randomPerson);
    $randomShortName = getShortName($randomPerson);
   
    // Форматируем результат
    $compatibility = mt_rand(5000, 10000) / 100; // Случайное число от 50 до 100 с двумя знаками после запятой
    $result = ucfirst($name). ' '. substr($surname, 0, 1). '. + '. $randomShortName. ' = \n';
    $result.= '♡ Идеально на '. number_format($compatibility, 2). '% ♡';
   
    return $result;
   }