<?php
// === survey.php ===

// 1. Підключаємо конфігурацію (з'єднання з БД)
// require_once - гарантує, що файл підключиться 1 раз і зупинить скрипт, якщо файлу немає
require_once 'config.php'; 

// 2. Підключаємо файл з функціями
require_once 'functions.php';

// 3. Логіка обробки форми (якщо вона була надіслана)
// Ми перемістили саму логіку збереження у функцію save_survey_response()
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Отримуємо дані з форми
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $q1 = htmlspecialchars($_POST['question1']);
    $q2 = htmlspecialchars($_POST['question2']);
    $q3 = htmlspecialchars($_POST['question3']);

    // Викликаємо нашу нову функцію для збереження в БД
    // $pdo - це змінна, яка була створена у 'config.php'
    $time_saved = save_survey_response($pdo, $name, $email, $q1, $q2, $q3);

    if ($time_saved) {
        $message = "<div class='success'>
                        <h2>Дякуємо за ваш відгук!</h2>
                        <p>Вашу форму було надіслано: <strong>$time_saved</strong></p>
                    </div>";
    } else {
        $message = "<div class='error'>
                        <h2>Помилка!</h2>
                        <p>Не вдалося зберегти ваші дані в базу даних.</p>
                    </div>";
    }
}

// Подальший код - це чистий HTML (ми нічого в ньому не змінюємо)
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторна 6 - Анкета (з БД)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="email"], textarea, select {
            width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type="submit"] {
            background: #007BFF; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px;
        }
        input[type="submit"]:hover { background: #0056b3; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 4px; text-align: center; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; text-align: center; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Анкета: "Відгук про курс"</h1>

        <?php
        // Виводимо повідомлення (змінна $message прийшла з config.php і оновилася вище)
        echo $message;
        ?>

        <?php
        // Ховаємо форму, якщо вона була успішно надіслана
        if (strpos($message, 'success') === false) {
        ?>
        
        <form action="survey.php" method="POST">
            
            <label for="name">Ваше ім'я:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Ваш Email:</label>
            <input type="email" id="email" name="email" required>

            <hr>
            
            <label for="question1">1. Яка ваша улюблена технологія frontend?</label>
            <select id="question1" name="question1" required>
                <option value="">-- Оберіть --</option>
                <option value="React">React</option>
                <option value="Vue">Vue</option>
                <option value="Angular">Angular</option>
                <option value="Svelte">Svelte</option>
                <option value="Чистий JS">Чистий JS/HTML/CSS</option>
            </select>

            <label>2. Як ви оцінюєте цей курс?</label>
            <input type="radio" id="q2_5" name="question2" value="5 - Чудово" required> <label for="q2_5">5 - Чудово</label><br>
            <input type="radio" id="q2_4" name="question2" value="4 - Добре"> <label for="q2_4">4 - Добре</label><br>
            <input type="radio" id="q2_3" name="question2" value="3 - Задовільно"> <label for="q2_3">3 - Задовільно</label><br>
            <input type="radio" id="q2_2" name="question2" value="2 - Погано"> <label for="q2_2">2 - Погано</label><br>
            
            <label for="question3">3. Що б ви хотіли додати до курсу?</label>
            <textarea id="question3" name="question3" rows="4" required></textarea>

            <input type="submit" value="Надіслати відгук">
        </form>
        
        <?php
        } // Кінець блоку if, який ховає форму
        ?>
        
    </div>

</body>
</html>