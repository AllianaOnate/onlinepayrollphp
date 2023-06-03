<!DOCTYPE html>
<html>
<head>
  <title>Payslip</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
  <style type="text/css">
    table {
      text-align: center;
      border-collapse: collapse;
      border: 3px solid black;
      padding: 10px;
    }
    th{
      text-align: center;
      border: 3px solid black;
      padding: 10px;
    }
    td {
      text-align: left;
      border: 3px solid black;
      padding: 10px;
    }
    h1{
      font-size: 3em;
      text-align: center;
    }
    .table-bordered {
      color: #000000;
      border: 3px solid black;
      padding: 10px;
    }
    .export-button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    }

  </style>
</head>
<body class="hold-transition skin-purple-light">
<?php include 'includes/session.php'; ?>
<?php
  include '../timezone.php';
  $range_to = date('m/d/Y');
  $range_from = date('m/d/Y', strtotime('-26 day', strtotime($range_to)));
?>
<?php include 'includes/header.php'; ?>
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <p></p>
      </ol>
    </section>
    <section class="content">
      <h1>GMC Employee Payslip</h1>
      <div class="box-body">
        <table id="example" class="table table-bordered" style="border:3px solid black">
          <p><strong> ID No: <?php echo $user['employee_id'].' '; ?></p>
          <p><strong> Name: <?php echo $user['firstname'].' '.$user['lastname']; ?></p>
          <p><strong> Date range: <em><?php echo $range_from. ' - '.$range_to; ?></p>
          <tr>
            <th style="border:3px solid black"> Gross</th>
            <th style="border:3px solid black" colspan="2">Deductions</th>
            <th style="border:3px solid black"> </th>
          </tr>
          <tbody>
            <?php
              $sql = "SELECT *, SUM(amount) as total_amount FROM deductions";
              $query = $conn->query($sql);
              $drow = $query->fetch_assoc();
              $deduction = $drow['total_amount'];

              $to = date('Y-m-d');
              $from = date('Y-m-d', strtotime('-26 day', strtotime($to)));

              if(isset($_GET['range'])){
                $range = $_GET['range'];
                $ex = explode(' - ', $range);
                $from = date('Y-m-d', strtotime($ex[0]));
                $to = date('Y-m-d', strtotime($ex[1]));
              }

              $sql = "SELECT *, SUM(num_hr) AS total_hr
              FROM attendance
              LEFT JOIN employees ON employees.id = attendance.employee_id
              LEFT JOIN position ON position.id = employees.position_id
              WHERE attendance.employee_id = '{$_SESSION['employees']}' AND date BETWEEN '$from' AND '$to'
              GROUP BY attendance.employee_id
              ORDER BY employees.lastname ASC, employees.firstname ASC";

              $query = $conn->query($sql);
              $total = 0;
              while($user = $query->fetch_assoc()){
                $employee_id = $user['employee_id'];

                $casql = "SELECT *, SUM(amount) AS cashamount 
                FROM cashadvance 
                WHERE employee_id = '{$_SESSION['employees']}' AND date_advance BETWEEN '$from' AND '$to'";

                $caquery = $conn->query($casql);
                $carow = $caquery->fetch_assoc();
                $cashadvance = $carow['cashamount'];

                $gross = $user['rate'] * $user['total_hr'];
                $perday = $user['rate'] * 8;
                $monthly_salary = $perday * 26;

                // Deductions SSS PAGIBIG PHILHEALTH
                //SSS
                $sss = $monthly_salary * 0.045;

                //PAGIBIG
                if ($monthly_salary >= 5000){
                  $pagibig = $monthly_salary * 0.03;
                }
                else {
                  $pagibig = $monthly_salary * 0.02;
                }

                // PHILHEALTH
                $philhealth = $monthly_salary * 0.045;

                //TOTAL DEDUCTIONS
                $total_deduction = $sss + $pagibig + $philhealth + $cashadvance;

                $net = $gross - $total_deduction;

                //13TH MONTH PAY
                $decpay = $perday * 22.5 * 12 / 12;

                echo "
                  <tr>
                    <td style='border: 3px solid black;'>".number_format($gross, 2)."</td>
                    <td style='border: 3px solid black; border-right: 3px dotted black;'>SSS<br>PagIbig<br>PhilHealth<br>Cash Advance<br><strong>Total</strong></td>
                    <td style='border: 3px solid black; border-left: 3px dotted black;'><em>$sss<br>$pagibig<br>$philhealth<br>$cashadvance<br>$total_deduction</td>
                  </tr>
                  <tr>
                    <td style='border: 3px solid black;'></td>
                    <td style='border: 3px solid black;'> </td>
                    <td style='border: 3px solid black; border-right: 3px dotted black;'><strong>Net Pay:</td>
                    <td style='border: 3px solid black; border-left: 3px dotted black;'><strong>$net</td>
                  </tr>
                ";
              }
            ?>
          </tbody>
        </table>
      </div>
      <button id="exportButton" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; font-size: 16px; margin-top: 10px;">Export as PDF</button>

    </section>
  </div>
  <?php include 'includes/scripts.php'; ?>
  

  <script>
    
  document.getElementById("exportButton").addEventListener("click", function() {
    document.getElementById("exportButton").style.display = "none";
    var content = document.querySelector(".content-wrapper");
    html2canvas(content).then(function(canvas) {
      var imgData = canvas.toDataURL("image/jpeg", 1.0);
      
      // Create a new PDF document
      var docDefinition = {
        content: [
          {
            image: imgData,
            width: 500,
          },
        ],
      };

      // Generate the PDF and initiate download
      pdfMake.createPdf(docDefinition).download("gmc_payslip.pdf");

      document.getElementById("exportButton").style.display = "block";
    });
  });
</script>

</div>
</body>
</html>