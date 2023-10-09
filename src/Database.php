<?php

namespace Hasirciogli\Hdb;

use Hasirciogli\Hdb\Interfaces\Database\Config\DatabaseConfigInterface;

use PDO;

class Database
{
    public DatabaseConfigInterface $Config;

    function __construct(DatabaseConfigInterface $Config)
    {
        $this->Config = $Config;
    }

    public PDO|null $Connection = null;

    private false|\PDOStatement $TV = false;
    private bool $TV2 = false;
    private string $TempSql = "";
    private array $TempBParams = [];

    private function CheckDB()
    {
        if ($this->Connection)
            return $this;

        try {
            $this->Connection = new PDO("mysql:host=" . $this->Config::DB_HOST . ";charset=utf8;dbname=" . $this->Config::DB_NAME, $this->Config::DB_USER, $this->Config::DB_PASS);
            return $this;
        } catch (\Exception $e) {
            die("Database connection error" . $e->getMessage());
        }

    }

    private function CloseConnection(): void
    {
        if (!$this->Connection)
            return;

        $this->Connection = null;
    }




    public function Use (string $DbName): Database
    {
        if (!$this->Connection)
            $this->CheckDB();

        $this->TempSql = "USE " . $DbName . ";";

        return $this;
    }

    public function LastInsertId(): int
    {
        if (!$this->Connection)
            $this->CheckDB();

        return $this->Connection->LastInsertId() ?? -1;
    }

    public function Select(string $TableName): Database
    {

        $this->TempSql = "SELECT * FROM " . $TableName;

        return $this;
    }

    public function Insert(string $TableName, $Dataset): Database
    {

        $Tf = "";
        $Tt = "";

        foreach ($Dataset as $Item) {
            if (strlen($Tf) <= 0)
                $Tf .= "$Item";
            else
                $Tf .= ", $Item";

            if (strlen($Tt) <= 0)
                $Tt .= ":$Item";
            else
                $Tt .= ", :$Item";

        }

        $this->TempSql = "INSERT INTO $TableName($Tf) VALUES ($Tt)";

        return $this;
    }

    public function BindParam(string $Key, string $Value): Database
    {

        $this->TempBParams[$Key] = $Value;


        return $this;
    }

    public function Where(string $Key, bool $BinaryMode = false): Database
    {

        if (str_contains($this->TempSql, "WHERE"))
            $this->TempSql .= " AND" . ($BinaryMode ? " BINARY" : "") . " $Key=:$Key";
        else
            $this->TempSql .= " WHERE" . ($BinaryMode ? " BINARY" : "") . " $Key=:$Key";

        return $this;
    }

    public function WhereOr(string $Key, bool $BinaryMode = false): Database
    {

        if (str_contains($this->TempSql, "WHERE"))
            $this->TempSql .= " OR" . ($BinaryMode ? " BINARY" : "") . " $Key=:$Key";
        else
            $this->TempSql .= " WHERE" . ($BinaryMode ? " BINARY" : "") . " $Key=:$Key";

        return $this;
    }

    public function Limit($Start, $Limit = -1): Database
    {
        $this->TempSql .= ' LIMIT ' . $Start . ($Limit != -1 ? ", $Limit" : "");

        return $this;
    }

    public function OrderBy($Column, $Direction = 'ASC')
    {
        $sql = $this->TempSql .= ' ORDER BY ' . $Column . ' ' . $Direction;

        return $this;
    }

    public function CustomSql($Sql): Database
    {
        $this->TempSql = $Sql;

        return $this;
    }

    public function Run(): false|Database
    {
        $Fdb = $this->CheckDB();

        $this->TV = $Fdb->Connection->prepare($this->TempSql);
        $this->TV2 = $this->TV->execute($this->TempBParams);

        return $this->TV2 ? $this : false;
    }


    public function Get(string $Type = ""): mixed
    {
        if ($this->TV2) {
            if ($Type == "all")
                return $this->TV->fetchAll(PDO::FETCH_ASSOC);
            else
                return $this->TV->fetch(PDO::FETCH_ASSOC);
        } else
            return false;
    }








    public static function cfun(...$params): Database
    {
        return new Database(...$params);
    }
}