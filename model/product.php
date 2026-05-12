<?php
class Produto {
    public int $id;
    public string $name;
    public float $price;
    public string $created_at;
    public string $updated_at;

    public function __construct($name, $price, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }
}