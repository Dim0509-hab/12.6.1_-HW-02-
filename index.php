<?php



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
    return $name. ' '. substr($surname, 0, 2). '.';
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
   
    // Округляем проценты 
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
    $result = ucfirst($name). ' '. substr($surname, 0, 2). '. + '. $randomShortName. ' = '.PHP_EOL;
    $result.= '♡ Идеально на '. number_format($compatibility, 2). '% ♡';
   
    return $result;
   }


/*
  
$fullname = getFullnameFromParts('Иванов', 'Иван', 'Иванович');
echo $fullname.PHP_EOL; // Выведет: Иванов Иван Иванович


$parts = getPartsFromFullname('Иванов Иван Иванович');
print_r($parts); // Выведет: Array ( [surname] => Иванов [name] => Иван [patronymic] => Иванович )



$shortName = getShortName('Иванов Иван Иванович');
echo $shortName.PHP_EOL; // Выведет: Иван И.


$gender = getGenderFromName('Иванов Иван Иванович');
echo $gender.PHP_EOL; // Выведет: 1 (мужской пол)

$gender = getGenderFromName('Иванова Анна Ивановна');
echo $gender.PHP_EOL; // Выведет: -1 (женский пол)

$gender = getGenderFromName('Петров Алексей Петрович');
echo $gender.PHP_EOL; // Выведет: 1 (мужской пол)

$gender = getGenderFromName('Сидорова Елена Александровна');
echo $gender.PHP_EOL; // Выведет: -1 (женский пол)



//  массив с ФИО
$example_persons_array = [
 'Иванов Иван Иванович',
 'Иванова Анна Ивановна',
 'Петров Алексей Петрович',
 'Сидорова Елена Александровна',
 'Сидоров Алексей Петрович',
 'Неизвестнов Неизвестно Неизвестно']
;


echo getPerfectPartner('Иванов', 'Иван', 'Иванович', $example_persons_array);


// Пример использования getGenderDescription
echo getGenderDescription($example_persons_array);

*/
