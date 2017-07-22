<?php class CoreClass {

    private $root;
    private $relativePath;
    private $version = '1.0';
    private $database;
    private $session;
    private $environment;
    private $log;

    function __construct($relativePath = '') {
        $this->relativePath = $relativePath;
        $this->root = $_SERVER["DOCUMENT_ROOT"].$this->relativePath;
        $this->version .= '-'.((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'beta' : 'dev');

        try {
            if (file_exists($envPath = $this->getCorePath().'/environments/'.($this->isDebug() ? 'dev' : 'prod').'.json')) $this->environment = json_decode(file_get_contents($envPath), true);
        } catch (Exception $e) { echo json_encode(['status' => 0, 'code' => 'no-environment']); die; }
    }

    function __destruct() {
        if (isset($this->log)) {
            if (!isset($this->database)) $this->database = $this->getDatabase();

            $sentencia = $this->database->prepare('INSERT INTO log (user, area, body) VALUES (:user, :area, :body)');

            foreach ($this->log as $logEntry) {
                $sentencia->bindValue(':user', isset($this->session) ? $this->session['id'] : null);
                $sentencia->bindParam(':area', $logEntry['area']);
                $sentencia->bindParam(':body', $logEntry['body']);

                $sentencia->execute();
            }
        }
    }

    /**
     *  getRootPath()
     *  Esta funcion devuelve el path absoluto del directorio de la aplicación
     */
    function getRootPath() {
        return $this->root;
    }

    /**
     *  getRootPath()
     *  Esta funcion devuelve el path absoluto del directorio de la aplicación
     */
    function getRelativePath() {
        return $this->relativePath;
    }

    /**
     *  getCorePath()
     *  Esta funcion devuelve el path absoluto del directorio core
     */
    function getCorePath() {
        return $this->root.'/core';
    }

    /**
     *  getEnvironment()
     *  Esta funcion devuelve la instancia o entorno para el que la apliación está trabajando
     */
    function getEnvironment() {
        return $this->environment;
    }

    /**
     *  getDatabase()
     *  Esta funcion devuelve el PDO para una determinada base de datos
     *  @param string name Name of the BD config
     */
    function getDatabase() {
        $config = $this->environment['database'];
        try {
            if (!isset($this->database)) $this->database = new PDO('mysql:host='.$config['server'].';dbname='.$config['dbname'].';charset=utf8mb4', $config['user'], $config['password'], array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
            return $this->database;
        } catch (PDOException $e) {
            echo json_encode(['status' => 0, 'code' => 'no-database']); die;
        }
    }

    /**
     *  getData()
     *  Esta funcion leer un fichero de datos
     *  @param string name Name of the file
     */
    function getData($name, $parse = true) {
        if (file_exists($path = $this->getCorePath().'/data/'.$name.($parse ? '.json' : ''))) {
            $data = file_get_contents($path);
            return $parse ? json_decode($data, true) : $data;
        } else return false;
    }

    /**
     *  setData()
     *  Esta funcion escribe un fichero de datos
     *  @param string name Name of the file
     */
    function setData($name, $data, $parse = true) {
        if (is_writable(dirname($path = $this->getCorePath().'/data/'.$name.($parse ? '.json' : '')))) {
            return file_put_contents($path, $parse ? json_encode($data) : $data);
        } else return false;
    }

    /**
     *  hasPermission()
     *  Esta funcion devuelve si un determinado privitegio está concedido para
     *  el conjunto de permisos asociados a la sesion
     *  @param string name of the privilege
     */
    function hasPermission($privilege, $permissions = null) {
        foreach ((!is_null($permissions) ? $permissions : $this->session['permissions']) as $permission) {
            if (fnmatch($permission, $privilege)) return true;
        } return false;
    }

    /**
     *  log()
     *  Esta funcion almacena un log para que al finalizar la ejecución se inserte en base de datos
     */
    function log($area, $body) {
        if (!isset($this->log)) $this->log = array(array('area' => $area, 'body' => $body));
        else $this->log[] = array('area' => $area, 'body' => $body);
    }

    /**
     *  getVersion()
     *  Esta funcion devuelve la versión en la que se encuentra el proyecto
     */
    function getVersion() {
        return $this->version;
    }

    /**
     *  isDebug()
     *  Esta funcion devuelve si la aplicación está en modo de desarrollo
     */
    function isDebug() {
        return substr($this->version, -3) == 'dev';
    }

    /**
     *  isCLI()
     *  Esta funcion devuelve si la aplicación está siendo invocada por la linea de comandos
     */
    function isCLI() {
        return php_sapi_name() === 'cli';
    }

    /**
     *  setSession()
     *  Esta funcion recoge la session del cliente
     */
    function setSession($session) {
        $this->session = $session;
    }
} $core = new CoreClass(dirname($_SERVER['SCRIPT_NAME']));