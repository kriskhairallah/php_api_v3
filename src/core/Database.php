<?php
	
namespace App\Core;

/**
 * Database Connection Handler
 * 
 * This class wraps MeekroDB functionality and provides
 * a consistent interface for database operations.
 */
class Database
{
    /**
     * Initialize the database connection
     *
     * @param string $host     Database host
     * @param string $username Database username
     * @param string $password Database password
     * @param string $name     Database name
     * @param int    $port     Database port
     * 
     * @return void
     * @throws \Exception If connection fails
     */
    public static function init(
        string $host,
        string $username,
        string $password,
        string $name,
        int $port = 3306
    ): void {
        try {
            \DB::$host = $host;
            \DB::$user = $username;
            \DB::$password = $password;
            \DB::$dbName = $name;
            \DB::$port = $port;
            \DB::$encoding = 'utf8mb4';

            // Test connection
            self::query("SELECT 1");
        } catch (\Exception $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a query and return all results
     *
     * @param string $query  SQL query
     * @param mixed  ...$params Query parameters
     * 
     * @return array
     * @throws \Exception If query fails
     */
    public static function query(string $query, ...$params): array
    {
        try {
            return \DB::query($query, ...$params);
        } catch (\Exception $e) {
            throw new \Exception("Query failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a query and return the first row
     *
     * @param string $query  SQL query
     * @param mixed  ...$params Query parameters
     * 
     * @return array|null
     * @throws \Exception If query fails
     */
    public static function queryFirstRow(string $query, ...$params): ?array
    {
        try {
            return \DB::queryFirstRow($query, ...$params);
        } catch (\Exception $e) {
            throw new \Exception("Query failed: " . $e->getMessage());
        }
    }

    /**
     * Insert a row into a table
     *
     * @param string $table Table name
     * @param array  $data  Data to insert
     * 
     * @return int|null Last insert ID
     * @throws \Exception If insert fails
     */
    public static function insert(string $table, array $data): ?int
    {
        try {
            \DB::insert($table, $data);
            return \DB::insertId();
        } catch (\Exception $e) {
            throw new \Exception("Insert failed: " . $e->getMessage());
        }
    }

    /**
     * Update rows in a table
     *
     * @param string $table  Table name
     * @param array  $data   Data to update
     * @param string $where  Where clause
     * @param mixed  ...$params Query parameters
     * 
     * @return int Number of affected rows
     * @throws \Exception If update fails
     */
    public static function update(string $table, array $data, string $where, ...$params): int
    {
        try {
            \DB::update($table, $data, $where, ...$params);
            return \DB::affectedRows();
        } catch (\Exception $e) {
            throw new \Exception("Update failed: " . $e->getMessage());
        }
    }

    /**
     * Delete rows from a table
     *
     * @param string $table  Table name
     * @param string $where  Where clause
     * @param mixed  ...$params Query parameters
     * 
     * @return int Number of affected rows
     * @throws \Exception If delete fails
     */
    public static function delete(string $table, string $where, ...$params): int
    {
        try {
            \DB::delete($table, $where, ...$params);
            return \DB::affectedRows();
        } catch (\Exception $e) {
            throw new \Exception("Delete failed: " . $e->getMessage());
        }
    }

    /**
     * Begin a transaction
     *
     * @return void
     * @throws \Exception If transaction start fails
     */
    public static function startTransaction(): void
    {
        try {
            \DB::startTransaction();
        } catch (\Exception $e) {
            throw new \Exception("Transaction start failed: " . $e->getMessage());
        }
    }

    /**
     * Commit a transaction
     *
     * @return void
     * @throws \Exception If commit fails
     */
    public static function commit(): void
    {
        try {
            \DB::commit();
        } catch (\Exception $e) {
            throw new \Exception("Transaction commit failed: " . $e->getMessage());
        }
    }

    /**
     * Rollback a transaction
     *
     * @return void
     * @throws \Exception If rollback fails
     */
    public static function rollback(): void
    {
        try {
            \DB::rollback();
        } catch (\Exception $e) {
            throw new \Exception("Transaction rollback failed: " . $e->getMessage());
        }
    }
}