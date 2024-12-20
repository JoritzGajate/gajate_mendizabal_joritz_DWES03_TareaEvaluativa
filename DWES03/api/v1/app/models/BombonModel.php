<?php
//Modelo de la clase bombon
class Bombon
{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private string $imagen;
    private int $stock;

    public function __construct(int $id, string $nombre, string $descripcion, float $precio, string $imagen, int $stock)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->imagen = $imagen;
        $this->stock = $stock;
    }

}
