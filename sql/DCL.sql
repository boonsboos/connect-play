USE webshop; -- Selecting the Database

-- Creating Roles and Privileges
CREATE ROLE IF NOT EXISTS 'admin_role';
GRANT ALL PRIVILEGES ON webshop.* TO 'admin_role';

CREATE ROLE IF NOT EXISTS 'finance_role';
GRANT UPDATE ON webshop.game TO 'finance_role';
GRANT UPDATE ON webshop.workshop TO 'finance_role';
GRANT SELECT ON webshop.order TO 'finance_role';
GRANT SELECT ON webshop.cart_entry TO 'finance_role';
GRANT SELECT ON webshop.audit_prices TO 'finance_role';
GRANT SELECT ON webshop.audit_order TO 'finance_role';

CREATE ROLE IF NOT EXISTS 'process_manager_role';
GRANT SELECT ON webshop.* TO 'process_manager_role';

CREATE ROLE IF NOT EXISTS 'system_manager_role';
GRANT CREATE, ALTER, DROP ON webshop.* TO 'system_manager_role';
GRANT SELECT, INSERT, UPDATE, DELETE ON webshop.* TO 'system_manager_role';
GRANT SELECT ON webshop.audit_prices TO 'system_manager_role';
GRANT SELECT ON webshop.audit_order TO 'system_manager_role';

CREATE ROLE IF NOT EXISTS 'market_analyst_role';
GRANT SELECT ON webshop.game TO 'market_analyst_role';
GRANT SELECT ON webshop.workshop TO 'market_analyst_role';
GRANT SELECT ON webshop.order TO 'market_analyst_role';
GRANT SELECT ON webshop.cart_entry TO 'market_analyst_role';
GRANT SELECT ON webshop.review TO 'market_analyst_role';

-- Creating Users and Assigning Roles
CREATE USER IF NOT EXISTS 'admin'@'localhost' IDENTIFIED BY 's3cureAdm1nP4ssw0rd';
GRANT 'admin_role' TO 'admin'@'localhost';

CREATE USER IF NOT EXISTS 'finance'@'localhost' IDENTIFIED BY 'Fin4nc3P@ss!';
GRANT 'finance_role' TO 'finance'@'localhost';

CREATE USER IF NOT EXISTS 'process_manager'@'localhost' IDENTIFIED BY 'Pr0c3ssM@n4ger!';
GRANT 'process_manager_role' TO 'process_manager'@'localhost';

CREATE USER IF NOT EXISTS 'system_manager'@'localhost' IDENTIFIED BY 'SysM@n4gerP@ss!';
GRANT 'system_manager_role' TO 'system_manager'@'localhost';

CREATE USER IF NOT EXISTS 'market_analyst'@'localhost' IDENTIFIED BY 'M4rk3t@n4lyst!';
GRANT 'market_analyst_role' TO 'market_analyst'@'localhost';

FLUSH PRIVILEGES; -- Refreshing the Privileges a.k.a. Applying the Changes

-- Checking the Roles and Privileges
SELECT user, host FROM mysql.user WHERE user IN ('admin', 'finance', 'process_manager', 'system_manager', 'market_analyst');

SHOW GRANTS FOR 'admin'@'localhost';
SHOW GRANTS FOR 'admin_role';
SHOW GRANTS FOR 'finance'@'localhost';
SHOW GRANTS FOR 'finance_role';
SHOW GRANTS FOR 'process_manager'@'localhost';
SHOW GRANTS FOR 'process_manager_role';
SHOW GRANTS FOR 'system_manager'@'localhost';
SHOW GRANTS FOR 'system_manager_role';
SHOW GRANTS FOR 'market_analyst'@'localhost';
SHOW GRANTS FOR 'market_analyst_role';
