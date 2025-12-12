-- ================================================
--  SEED: ROLE + UŽIVATELÉ + PŘIŘAZENÍ ROLÍ
-- ================================================

-- 💡 Doporučené heslo pro všechny: heslo
-- Bcrypt hash: $2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK


INSERT INTO Roles (role_name)
VALUES
    ('superadmin'),
    ('admin'),
    ('reviewer'),
    ('author')
    ON DUPLICATE KEY UPDATE role_name = role_name;

SET @superadmin := (SELECT ID_roles FROM Roles WHERE role_name='superadmin');
SET @admin      := (SELECT ID_roles FROM Roles WHERE role_name='admin');
SET @reviewer   := (SELECT ID_roles FROM Roles WHERE role_name='reviewer');
SET @author     := (SELECT ID_roles FROM Roles WHERE role_name='author');

INSERT INTO Users (username, email, password, blocked)
VALUES
    ('superadmin', 'superadmin@example.com', '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0),
    ('admin1',     'admin1@example.com',     '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0),
    ('admin2',     'admin2@example.com',     '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0),

    ('author1',    'author1@example.com',    '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0),
    ('author2',    'author2@example.com',    '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0),

    ('reviewer1',  'reviewer1@example.com',  '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0),
    ('reviewer2',  'reviewer2@example.com',  '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0),
    ('reviewer3',  'reviewer3@example.com',  '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0),

    ('test',       'test@example.com',       '$2y$10$4G3TaltbVuJi43CN3Na1w.WcEzqyiixMSjBMOGy6247wdaQN2QulK', 0);


INSERT INTO UserRoles (ID_user, ID_role)
SELECT ID_user, @superadmin FROM Users WHERE username = 'superadmin';

INSERT INTO UserRoles (ID_user, ID_role)
SELECT ID_user, @admin FROM Users WHERE username IN ('admin1', 'admin2');

INSERT INTO UserRoles (ID_user, ID_role)
SELECT ID_user, @author FROM Users WHERE username IN ('author1', 'author2');

INSERT INTO UserRoles (ID_user, ID_role)
SELECT ID_user, @reviewer FROM Users WHERE username IN ('reviewer1', 'reviewer2', 'reviewer3');

INSERT INTO UserRoles (ID_user, ID_role)
SELECT ID_user, @author FROM Users WHERE username = 'test';


SELECT 'DATA ÚSPĚŠNĚ NAČTENA' AS status;
