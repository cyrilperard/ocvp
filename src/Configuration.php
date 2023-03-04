<?php

namespace App;
use Symfony\Component\Yaml\Yaml;

class Configuration
{
    private const DIR_CONFIG = "config/modules/";

    private static $_instance = null;

    private $config_ged;
    private $config_badge_reader;
    private $config_notifications;

    /**
     * Config constructor.
     */
    private function __construct()
    {
        $rootPath = str_replace("public", "", $_SERVER['DOCUMENT_ROOT']);
        $this->config_ged = Yaml::parse(file_get_contents($rootPath . self::DIR_CONFIG . "ged.yaml"));
        $this->config_badge_reader = Yaml::parse(file_get_contents($rootPath . self::DIR_CONFIG . "badge_reader.yaml"));
        $this->config_notifications = Yaml::parse(file_get_contents($rootPath . self::DIR_CONFIG . "notifications.yaml"));
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Self;
        }

        return self::$_instance;
    }

    public function getNotifications($var)
    {
        return $this->array_search_key($var, $this->config_notifications["settings"]);
    }

    public function setNotifications($var, $data)
    {
        $rootPath = str_replace("public", "", $_SERVER['DOCUMENT_ROOT']);
        $searchVar =  $this->array_search_key($var, $this->config_notifications["settings"]["parameters"]);
        $this->config_notifications["settings"][$var] = $data;
        $new_yaml = Yaml::dump($this->config_notifications);
        file_put_contents($rootPath . self::DIR_CONFIG . "notifications.yaml", $new_yaml);
    }

    public function getGed($var)
    {
        return $this->array_search_key($var, $this->config_ged["settings"]);
    }

    public function setGed($var, $data)
    {
        $rootPath = str_replace("public", "", $_SERVER['DOCUMENT_ROOT']);
        $searchVar =  $this->array_search_key($var, $this->config_ged["settings"]["parameters"]);
        $this->config_ged["settings"][$var] = $data;
        $new_yaml = Yaml::dump($this->config_ged);
        file_put_contents($rootPath . self::DIR_CONFIG . "ged.yaml", $new_yaml);
    }

    public function getBadgeReader($var)
    {
        return $this->array_search_key($var, $this->config_badge_reader["settings"]);
    }

    public function setBadgeReader($var, $data)
    {
        $rootPath = str_replace("public", "", $_SERVER['DOCUMENT_ROOT']);
        $searchVar =  $this->array_search_key($var, $this->config_badge_reader["settings"]["parameters"]);
        $this->config_badge_reader["settings"][$var] = $data;
        $new_yaml = Yaml::dump($this->config_badge_reader);
        file_put_contents($rootPath . self::DIR_CONFIG . "badge_reader.yaml", $new_yaml);
    }

    private function array_search_key($needle_key, $array) {
        foreach($array AS $key=>$value){
            if($key == $needle_key) return $value;
            if(is_array($value)){
                if( ($result = $this->array_search_key($needle_key, $value)) !== false)
                    return $result;
            }
        }
        return false;
    }
}