<?php
require_once 'barang.php';

class BarangManager {
    private $dataFile = 'data.json';
    private $barangList = [];

    public function __construct() {
        if (file_exists($this->dataFile)) {
            $data = file_get_contents($this->dataFile);
            $this->barangList = json_decode($data, true) ?? [];
        }
    }

    // Menambahkan barang
    public function tambahBarang($nama, $harga, $stok) {
        $id = uniqid(); // Generate ID unik
        $barang = new Barang($id, $nama, $harga, $stok);
        $this->barangList[] = $barang->toArray();
        $this->simpanData();
    }

    // Mendapatkan semua barang
    public function getBarang() {
        return $this->barangList;
    }

    // Menghapus barang berdasarkan ID
    public function hapusBarang($id) {
        $this->barangList = array_values(array_filter($this->barangList, function($barang) use ($id) {
            return $barang['id'] !== $id;
        }));
        $this->simpanData();
    }

    // Menyimpan data ke file JSON
    private function simpanData() {
        file_put_contents($this->dataFile, json_encode($this->barangList, JSON_PRETTY_PRINT));
    }
}
