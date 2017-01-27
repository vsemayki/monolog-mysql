<?php
namespace MySQLHandler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use PDO;
use PDOStatement;

/**
 * This class is a handler for Monolog, which can be used
 * to write records in a MySQL table
 *
 * Class MySQLHandler
 * @package wazaari\MysqlHandler
 */
class MySQLHandler extends AbstractProcessingHandler
{
    /**
     * @var PDO pdo object of database connection
     */
    protected $pdo;

    /**
     * @var PDOStatement statement to insert a new record
     */
    private $statement;

    /**
     * @var string the table to store the logs in
     */
    private $table = 'monolog';

    /**
     * @var array additional context (fields) to be stored in the database
     */
    private $context = [];

    /**
     * @inheritdoc
     *
     * @param PDO    $pdo     PDO Connector for the database
     * @param string $table   Table in the database to store the logs in
     * @param array  $context Additional Context Parameters to store in database
     */
    public function __construct($pdo, $table, $level = Logger::INFO, $bubble = true)
    {
        $this->pdo   = $pdo;
        $this->table = $table;
        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param [] $record
     *
     * @return void
     */
    protected function write(array $record)
    {
        $this->context['channel']    = $record['channel'];
        $this->context['level']      = $record['level'];
        $this->context['message']    = $record['message'];
        $this->context['created_at'] = $record['datetime']->format('Y-m-d H:i:s');
        $this->context['ip']         = null;
        $this->context['user_agent'] = null;

        // Be aware of running from CLI
        if (isset($_SERVER)) {
            $this->context['ip']         = $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);
            $this->context['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }

        $this->context = array_merge($this->context, $record['context']);

        $columns = implode(', ', array_keys($this->context));
        $values  = implode(', :', array_keys($this->context));

        $this->statement = $this->pdo->prepare(
            sprintf('INSERT INTO `%s` (%s) VALUES (:%s)', $this->table, $columns, $values)
        );

        $this->statement->execute($this->context);
    }
}