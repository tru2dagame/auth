<?php

class UbntMysql {
    private $db;
    private $result = '';

    private $host;
    private $username;
    private $passwd;
    private $dbname;
    private $port;
    private $charset;

    public function __construct($db_config) {
        $this->host = $db_config['host'];
        $this->username = $db_config['user'];
        $this->passwd = $db_config['pass'];
        $this->dbname = $db_config['name'];
        $this->port = $db_config['port'];
        $this->charset = isset($db_config['char']) ? $db_config['char'] : 'utf8';
    }

    public function query($query, $result_mode='default', $paramter='') {
        $this->db = new mysqli($this->host, $this->username, $this->passwd, 
        $this->dbname, $this->port);
        if ($this->db->connect_error) {
            $this->db = new mysqli($this->host, $this->username, $this->passwd, 
            $this->dbname, $this->port);
            if ($this->db->connect_error) {
                throw new Exception('数据库维护中，请稍后再试', $this->db->errno);
            }
        }
        $this->result = '';
        if ($this->charset)
            $this->db->set_charset($this->charset); 
        $res = $this->db->query($query);
        if ($res === FALSE) {
            throw new Exception('数据库维护中，请稍后再试', $this->db->errno);
        }
        switch ($result_mode) {
        case '1':
            $this->result = $res->fetch_row();
            if ($this->result)
                $this->result = $this->result[0];
            break;
        case 'array':
            $this->result = $res->fetch_array(MYSQLI_ASSOC);
            break;
        case 'row':
            $this->result = $res->fetch_row();
            break;
        case 'all':
            while ($row = $res->fetch_array(MYSQLI_ASSOC))
                $this->result[] = $row;
            break;
        case 'param':
            while ($row = $res->fetch_array(MYSQLI_ASSOC))
                $this->result[] = $row[$paramter];
            break;
        case 'id':
            $this->result = $this->db->insert_id;
            break;
        default:
            $this->result = $res;
            break;
        }
        $this->close();
        return $this->result;
    }

    private function close() {
        $this->db->close();
    }

 }