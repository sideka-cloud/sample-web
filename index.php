<!DOCTYPE html>
<html>
<head>
  <title>sys-ops.id</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php
  $conn = mysqli_connect("192.168.100.53", "user_sys_ops", "pass_sys_ops", "sys_ops_db");
  $hostname = gethostname();
  $phpversion = phpversion();
  exec("/sbin/ip a", $output);
  $ipAddresses = [];
  foreach ($output as $line) {
    if (preg_match('/inet (\d+\.\d+\.\d+\.\d+)/', $line, $matches)) {
        $ip = $matches[1];
        if ($ip !== '127.0.0.1') {
            $ipAddresses[] = $ip;
          }
      }
  }
  ?>

  <header>
    <h1>Welcome To My Page!</h1>
    <h3>Load Balancing MariaDB Galera Cluster with MariaDB MaxScale</h3>
  </header>

  <main>
    <img src="img/cat2.png" alt="cat" />
    <p>Server hostname: <?php echo $hostname; ?></p>
    <p>Server IP address:
      <?php foreach ($ipAddresses as $ipAddress) {
        echo $ipAddress;
      } ?>
    </p>
    <p>PHP version: <?php echo $phpversion; ?></p>
    
    <p>Show table data:</p>
    <?php
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT id, data_base, webserver FROM webdata";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<table><tr><th>ID</th><th>DataBase</th><th>WebServer</th></tr>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["data_base"] . "</td><td>" . $row["webserver"] . "</td></tr>";
      }
      echo "</table>";
    } else {
      echo "0 results";
    }

    $query = "SELECT @@hostname AS hostname;";
    $result = $conn->query($query);
    if ($result) {
      $row = $result->fetch_assoc();
      echo "<p>Database Hostname: " . $row["hostname"];
    }
    $conn->close();
    ?>
  </main>

  <footer>
    <p>&copy; 2023 sys-ops.id</p>
  </footer>
</body>
</html>

