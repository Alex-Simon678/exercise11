<?php

interface DatabaseConnectionInterface {
    public function getConnection(): PDO;
}