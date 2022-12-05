<?php
$dsn = 'mysql:dbname=homestead;host=localhost';
$username = 'homestead';
$password = 'secret';
$dbh = new PDO($dsn, $username, $password);

// 1.
try {
    //  Створити транзакцію, в транзакції повинні бути sql запити
    $dbh->beginTransaction();


    //  insert в таблицю user створення юзера
    $sql = 'INSERT INTO users (name, email, token, country_id) VALUES (:name, :email, :token, :country_id)';
    $sth = $dbh->prepare($sql);

    $name = 'Franco Johns Sr.';
    $email = 'luc1112s70@example.org' . rand(0, 9999);
    $token = 'RZy00SDdXJ1ItshVeJ3v2c0ccj94yKyd';
    $country_id = 1;

    $sth->bindValue('name', $name);
    $sth->bindValue('email', $email);
    $sth->bindValue('token', $token);
    $sth->bindValue('country_id', $country_id);
    $sth->execute();


    //  отримання id нового юзера
    $userId = $dbh->lastInsertId();


    //  далі привязуємо юзеру рандомний проект, тобто робимо insert в таблицю project_user
    $projectAll = $dbh->query('SELECT id FROM projects ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'INSERT INTO project_user (project_id, user_id) VALUES (:project_id, :user_id)';
    $sth = $dbh->prepare($sql);
    $sth->bindValue('project_id', $projectAll[rand(1, count($projectAll))]['id']);
    $sth->bindValue('user_id', $userId);
    $sth->execute();


    //  після цього створюємо лейб і прив'язуємо до нього юзера

    $sql = 'INSERT INTO labels (name, author_id) VALUES (:name, :author_id)';
    $sth = $dbh->prepare($sql);
    $sth->bindValue('name', 'randomName: ' . rand(0, 9999));
    $sth->bindValue('author_id', $userId);
    $sth->execute();


    $dbh->commit();
} catch (PDOException $exception) {
    $dbh->rollBack();
    echo $exception->getMessage();
}
