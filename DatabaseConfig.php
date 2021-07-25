<?php

class DatabaseConfig
{
    // Default IPv4 MySQL server
    public $hostname = '127.0.0.1';
    public $port = '3306';
    // Default password from Laravel Sail MySQL server
    public $username = 'root';
    public $password = 'password';
    // 安裝時注意：資料庫須事先建立
    public $databaseName = 'primitive_auth_system_demo';

    public function newPdo()
    {
        $params = [
            'host=' . $this->hostname,
            'port=' . $this->port,
            'dbname=' . $this->databaseName,
            'charset=' . 'utf8',
        ];
        $conn = new PDO(
            'mysql:' . implode(';', $params),
            $this->username,
            $this->password
        );
        $this->createTablesIfNotExists($conn);
        return $conn;
    }

    public function createTablesIfNotExists(PDO $conn)
    {
        $conn->exec(
            "CREATE TABLE IF NOT EXISTS `users` (
                `username` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
                `password_salt_prefix` CHAR(20) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
                `password_hash` CHAR(40) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
                PRIMARY KEY (`username`) USING BTREE
            )
            COLLATE='utf8mb4_0900_ai_ci'
            ENGINE=InnoDB
            ;"
        );
    }
}
