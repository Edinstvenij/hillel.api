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


// 2
//  створюємо таблицю logs (id, action, entity_id, entity_name, action, content)
$sql = 'CREATE TABLE logs (
    id int primary key auto_increment,
    `action` ENUM("create", "update", "delete") not null,
    entity_id int not null ,
    entity_name  VARCHAR(255) not null ,
    content json not null )';
$dbh->query($sql);


//  створюємо трігер, який буде спрацьовувати після insert (тут записуємо інформацію в таблицю logs)
$sql = "CREATE TRIGGER `insert_projects`
    AFTER INSERT ON projects
    FOR EACH ROW BEGIN
    INSERT INTO logs (`action`, entity_id, entity_name, content)
    VALUES ('insert', NEW.id, 'project',
            JSON_OBJECT('id', NEW.id, 'name', NEW.name, 'author_id', NEW.author_id, 'created_at', NEW.created_at, 'updated_at', NEW.updated_at));
END;";
$dbh->query($sql);


//  створюємо трігер, який буде спрацьовувати перед оновленням (тут записуємо інформацію в таблицю logs)
$sql = "CREATE TRIGGER `update_projects`
    BEFORE UPDATE
    ON projects
    FOR EACH ROW
BEGIN
    INSERT INTO logs (`action`, entity_id, entity_name, content)
    VALUES ('update', OLD.id, 'project',
            JSON_OBJECT('id', OLD.id, 'name', OLD.name, 'author_id', OLD.author_id, 'created_at', OLD.created_at, 'updated_at', OLD.updated_at));
END;";
$dbh->query($sql);


//  створюємо трігер, який буде спрацьовувати перед видаленням (тут записуємо інформацію в таблицю logs)
$sql = "CREATE TRIGGER `delete_projects`
    BEFORE DELETE
    ON projects
    FOR EACH ROW
BEGIN
    INSERT INTO logs (`action`, entity_id, entity_name, content)
    VALUES ('delete', OLD.id, 'project',
            JSON_OBJECT('id', OLD.id, 'name', OLD.name, 'author_id', OLD.author_id, 'created_at', OLD.created_at, 'updated_at', OLD.updated_at));
END;";
$dbh->query($sql);


// Після цього написати sql запити, на створення нового запису в project
$dbh->query("INSERT INTO projects (`name`, author_id, created_at, updated_at)
VALUES ('zxzxzxzxzxz', 1, '2022-05-17 17:57:59', '1970-10-16 18:57:18')");

// UPdate
$dbh->query("UPDATE projects SET name = 'Test123' where id = 10");

// Delete
$dbh->query("
DELETE
FROM label_project
where project_id = 11;
");
$dbh->query("
DELETE
FROM project_user
where project_id = 11;
");
$dbh->query("
DELETE
FROM projects
where id = 11;
");



/**
 * Команды для консоли БД
 *
 *
 * CREATE TABLE logs
 * (
 * id          int primary key auto_increment,
 * `action`    ENUM ('create', 'update', 'delete') not null,
 * entity_id   int                                 not null,
 * entity_name VARCHAR(255)                        not null,
 * content     json                                not null
 * );
 *
 *
 * CREATE TRIGGER `insert_projects`
 * AFTER INSERT
 * ON projects
 * FOR EACH ROW
 * BEGIN
 * INSERT INTO logs (`action`, entity_id, entity_name, content)
 * VALUES ('insert', NEW.id, 'project',
 * JSON_OBJECT('id', NEW.id, 'name', NEW.name, 'author_id', NEW.author_id, 'created_at', NEW.created_at, 'updated_at', NEW.updated_at));
 * END;
 *
 *
 * CREATE TRIGGER `update_projects`
 * BEFORE UPDATE
 * ON projects
 * FOR EACH ROW
 * BEGIN
 * INSERT INTO logs (`action`, entity_id, entity_name, content)
 * VALUES ('update', OLD.id, 'project',
 * JSON_OBJECT('id', OLD.id, 'name', OLD.name, 'author_id', OLD.author_id, 'created_at', OLD.created_at, 'updated_at', OLD.updated_at));
 * END;
 *
 *
 * CREATE TRIGGER `delete_projects`
 * BEFORE DELETE
 * ON projects
 * FOR EACH ROW
 * BEGIN
 * INSERT INTO logs (`action`, entity_id, entity_name, content)
 * VALUES ('delete', OLD.id, 'project',
 * JSON_OBJECT('id', OLD.id, 'name', OLD.name, 'author_id', OLD.author_id, 'created_at', OLD.created_at, 'updated_at', OLD.updated_at));
 * END;
 *
 * #   CREATE
 * INSERT INTO projects (`name`, author_id, created_at, updated_at)
 * VALUES ('zxzxzxzxzxz', 1, '2022-05-17 17:57:59', '1970-10-16 18:57:18');
 *
 * #   UPDATE
 * UPDATE projects
 * SET name = 'Test123'
 * where id = 10;
 *
 *
 * #   DELETE id 11
 * DELETE
 * FROM label_project
 * where project_id = 11;
 *
 * DELETE
 * FROM project_user
 * where project_id = 11;
 *
 * DELETE
 * FROM projects
 * where id = 11;
 *
 *
 * DROP TRIGGER insert_projects;
 * DROP TRIGGER update_projects;
 * DROP TRIGGER delete_projects;
 */

