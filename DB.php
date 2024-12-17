<?php

class DB
{
  public $servername = "localhost";
  public $username = "root";
  public $password = "";
  public $dbname = "iot_monitoring";
  public $conn;

  public function __construct()
  {
    // Set zona waktu ke Asia/Jakarta
    date_default_timezone_set('Asia/Jakarta');    
    $this->conn = new mysqli(
      $this->servername,
      $this->username,
      $this->password,
      $this->dbname
    );

    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }

  // Fungsi untuk menyisipkan data suhu dan kelembapan
  public function insert($temperature, $humidity)
  {
    // Validasi input: pastikan suhu dan kelembapan berbeda
    if ($temperature === $humidity) {
      return "Error: Temperature and Humidity values cannot be the same!";
    }

    // Validasi bahwa suhu dan kelembapan adalah angka
    if (!is_numeric($temperature) || !is_numeric($humidity)) {
      return "Error: Temperature and Humidity must be numeric values!";
    }

    // Format timestamp dengan zona waktu Asia/Jakarta
    $timestamp = date('Y-m-d H:i:s');

    // Pastikan nilai suhu dan kelembapan tidak null
    if ($temperature !== null && $humidity !== null && $timestamp !== null) {
      // Persiapkan query untuk memasukkan data
      $sql = "INSERT INTO data (temperature, humidity, timestamp) VALUES (?, ?, ?)";

      // Persiapkan statement
      $stmt = $this->conn->prepare($sql);

      if ($stmt === false) {
        die("Error preparing statement: " . $this->conn->error);
      }

      // Bind parameter: menggunakan tipe data double untuk temperature dan humidity
      $stmt->bind_param("dds", $temperature, $humidity, $timestamp);

      // Eksekusi query dan cek hasilnya
      if ($stmt->execute()) {
        return "New record created successfully!";
      } else {
        return "Error: " . $stmt->error;
      }

      $stmt->close();
    } else {
      return "Missing parameters in the URL!";
    }
  }

  // Fungsi untuk mengambil data suhu dan kelembapan dari database
  public function getSensorsData()
  {
    $sql = "SELECT * FROM data ORDER BY id DESC LIMIT 20";
    $result = $this->conn->query($sql);

    if ($result === false) {
      return "Error: " . $this->conn->error;
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }
}

?>
