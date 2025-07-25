<?php
// الاتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "attendance_db";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("فشل الاتصال: " . $conn->connect_error); }

// إضافة البيانات لو تم إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['person_name'];
  $count = $_POST['attendance_count'];
  if (!empty($name) && is_numeric($count)) {
    $stmt = $conn->prepare("INSERT INTO people_attendance (person_name, attendance_count) VALUES (?,?)");
    $stmt->bind_param("si", $name, $count);
    $stmt->execute();
    $stmt->close();
  }
}

// جلب البيانات من القاعدة
$result = $conn->query("SELECT * FROM people_attendance ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تسجيل الحضور</title>
<style>
  body {
    font-family: 'Tahoma', sans-serif;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    min-height: 100vh;
    padding: 20px;
    margin: 0;
    color: #333;
  }
  h1 {
    text-align: center;
    color: #fff;
    margin-bottom: 30px;
  }
  .form-box {
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    max-width: 500px;
    margin: auto;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  label {
    display: block;
    margin: 10px 0 5px;
    font-weight: bold;
  }
  input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    margin-bottom: 15px;
  }
  button {
    background: #2575fc;
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    width: 100%;
  }
  button:hover {
    background: #6a11cb;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
  }
  th, td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
  }
  th {
    background: #2575fc;
    color: #fff;
  }
  tr:hover {
    background: #f1f1f1;
  }
  .table-box {
    max-width: 700px;
    margin: 30px auto;
    overflow-x: auto;
  }
</style>
</head>
<body>

<h1>📋 تسجيل الحضور</h1>

<div class="form-box">
  <form method="POST">
    <label>اسم الشخص:</label>
    <input type="text" name="person_name" required placeholder="ادخل اسم الشخص">
    
    <label>عدد مرات الحضور:</label>
    <input type="number" name="attendance_count" required min="0" placeholder="مثال: 3">
    
    <button type="submit">➕ إضافة</button>
  </form>
</div>

<div class="table-box">
  <table>
    <tr>
      <th>الرقم</th>
      <th>الاسم</th>
      <th>عدد مرات الحضور</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['person_name']) ?></td>
        <td><?= $row['attendance_count'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>

<?php $conn->close(); ?>
