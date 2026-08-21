<?php
require_once 'Conexion.php';

class datosMedicamentos
{
    const TABLA = 'farmacia';

    private $idmedicamento;
    private $nom_medicamento;
    private $stock;
    private $fechavencimiento;
    private $fechaingreso;

    public function __construct(
        $idmedicamento = null,
        $nom_medicamento = " ",
        $stock = 0,
        $fechavencimiento = null,
        $fechaingreso = null
    ) {
        $this->idmedicamento = $idmedicamento;
        $this->nom_medicamento = $nom_medicamento;
        $this->stock = $stock;
        $this->fechavencimiento = $fechavencimiento;
        $this->fechaingreso = $fechaingreso;
    }

    public function get_idmedicamento(){ return $this->idmedicamento; }
    public function get_nom_medicamento(){ return $this->nom_medicamento; }
    public function get_stock(){ return $this->stock; }
    public function get_fechavencimiento(){ return $this->fechavencimiento; }
    public function get_fechaingreso(){ return $this->fechaingreso; }

    public function set_idmedicamento($v){ $this->idmedicamento = $v; }
    public function set_nom_medicamento($v){ $this->nom_medicamento = $v; }
    public function set_stock($v){ $this->stock = $v; }
    public function set_fechavencimiento($v){ $this->fechavencimiento = $v; }
    public function set_fechaingreso($v){ $this->fechaingreso = $v; }

    public function guardarMedicamento()
    {
        $conexion = new Conexion();

        $consulta = $conexion->prepare(
            "INSERT INTO " . self::TABLA . " (nombre_medicamento, stock, fecha_vencimiento) VALUES (:pmedicamento, :pstock, :pfechavencimiento)"
        );

        $consulta->bindValue(':pmedicamento', $this->nom_medicamento);
        $consulta->bindValue(':pstock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':pfechavencimiento', $this->fechavencimiento);

        return $consulta->execute();
    }

    public function actualizarMedicamento()
    {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET nombre_medicamento = :pmedicamento, stock = :pstock, fecha_vencimiento = :pfechavencimiento WHERE id_medicamento = :pidmedicamento');
        $consulta->bindValue(':pmedicamento', $this->nom_medicamento);
        $consulta->bindValue(':pstock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':pfechavencimiento', $this->fechavencimiento);
        $consulta->bindValue(':pidmedicamento', $this->idmedicamento);

        $consulta->execute();
        $conexion = null; 
    }
    public static function actualizarStock($v_idmedicamento, $stockactual, $nuevacant)
    {
        $nuevo_stock = 0;
        if (isset($v_idmedicamento, $stockactual, $nuevacant)) {
            $nuevo_stock = $stockactual + $nuevacant;
        } else {
            exit;
        }

        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET stock = :pstock WHERE id_medicamento = :pidmedicamento');
        $consulta->bindValue(':pstock', $nuevo_stock, PDO::PARAM_INT);
        $consulta->bindValue(':pidmedicamento', $v_idmedicamento);

        $consulta->execute();
        return $consulta;
        $conexion = null; 
    }

    public static function todosMedicamentos()
    {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT COUNT(*) FROM ' . self::TABLA);
        $consulta->execute();
        $registros = $consulta->fetchColumn();
        return $registros;
    }

    public static function consultarMedicamentoCod($idmedicamento)
    {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE id_medicamento = :pidmedicamento');
        $consulta->bindValue(':pidmedicamento', $idmedicamento);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_OBJ);
        return $registros;
    }

    public static function medicamentosStockBajo()
    {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE stock <= 5 ORDER BY stock ASC');
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_OBJ);
        return $registros;
    }

    public function eliminarMedicamento()
    {
        $conexion = new Conexion();
        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE id_medicamento = :pidmedicamento');
        $consulta->bindValue(':pidmedicamento', $this->idmedicamento);
        $consulta->execute(); 
        $conexion = null; 
    }
}
