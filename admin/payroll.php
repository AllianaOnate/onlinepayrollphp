<?php include 'includes/session.php'; ?>
<?php
  include '../timezone.php';
  $range_to = date('m/d/Y');
  $range_from = date('m/d/Y', strtotime('-26 day', strtotime($range_to)));
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-purple-light sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Payroll
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Payroll</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <div class="pull-right">
                <form method="POST" class="form-inline" id="payForm">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range" value="<?php echo (isset($_GET['range'])) ? $_GET['range'] : $range_from.' - '.$range_to; ?>">
                  </div>
                </form>
              </div>
            </div>
            <div class="box-body">
              <table id="example" class="table table-bordered">
                <thead>
                  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Gross</th>
                  <th>Work Day</th>
                  <th>Per Day</th>
                  <th>SSS</th>
                  <th>Pag-ibig</th>
                  <th>PhilHealth</th>
                  <th>Cash Advance</th>
                  <th>Total Deductions</th>
                  <th>Net Pay</th>
                  <th>13th Month Pay</th>
                </thead>
                <tbody>
                  <?php
                    $to = date('Y-m-d');
                    $from = date('Y-m-d', strtotime('-26 day', strtotime($to)));

                    if(isset($_GET['range'])){
                      $range = $_GET['range'];
                      $ex = explode(' - ', $range);
                      $from = date('Y-m-d', strtotime($ex[0]));
                      $to = date('Y-m-d', strtotime($ex[1]));
                    }

                    $sql = "SELECT *, SUM(num_hr) AS total_hr, attendance.employee_id AS empid FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id LEFT JOIN position ON position.id=employees.position_id WHERE date BETWEEN '$from' AND '$to' GROUP BY attendance.employee_id ORDER BY employees.lastname ASC, employees.firstname ASC";

                    $query = $conn->query($sql);
                    $total_working_days = 0;
                    $total_gross = 0;
                    $total_deductions = 0;
                    $total_net_pay = 0;
                    while($row = $query->fetch_assoc()){
                      $empid = $row['empid'];
                      
                      $casql = "SELECT *, SUM(amount) AS cashamount 
                      FROM cashadvance 
                      WHERE employee_id='$empid' AND date_advance BETWEEN '$from' AND '$to'";
                      
                      $caquery = $conn->query($casql);
                      $carow = $caquery->fetch_assoc();
                      $cashadvance = $carow['cashamount'];

                      $gross = $row['rate'] * $row['total_hr'];
                      $perday = $row['rate'] * 8;
                      $monthly_salary = $perday * 26;

                      // Deductions SSS PAGIBIG PHILHEALTH
                      // SSS
                      $sss = $monthly_salary * 0.045;
                      
                      // PAGIBIG
                      if ($monthly_salary >= 5000){
                        $pagibig = $monthly_salary * 0.03;
                      }
                      else {
                        $pagibig = $monthly_salary * 0.02;
                      }
                      
                      // PHILHEALTH
                      $philhealth = $monthly_salary * 0.045;

                      // TOTAL DEDUCTIONS
                      $total_deduction = $sss + $pagibig + $philhealth + $cashadvance;

                      $net = $gross - $total_deduction;
                      
                      // 13TH MONTH PAY
                      $decpay = $perday * 22.5 * 12 / 12;

                      // TOTAL WORK DAYS
                      $total_working_days += $row['total_hr'] / 8;

                      // Calculate totals
                      $total_gross += $gross;
                      $total_deductions += $total_deduction;
                      $total_net_pay += $net;

                      echo "
                        <tr>
                          <td>".$row['employee_id']."</td>
                          <td>".$row['lastname'].", ".$row['firstname']."</td>
                          <td>".number_format($gross, 2)."</td>
                          <td>".number_format($total_working_days)."</td>
                          <td>".number_format($perday, 2)."</td>
                          <td>".number_format($sss, 2)."</td>
                          <td>".number_format($pagibig, 2)."</td>
                          <td>".number_format($philhealth, 2)."</td>
                          <td>".number_format($cashadvance, 2)."</td>
                          <td>".number_format($total_deduction, 2)."</td>
                          <td>".number_format($net, 2)."</td>
                          <td>".number_format($decpay, 2)."</td>
                        </tr>
                      ";
                    }

                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="2">Total:</th>
                    <th><?php echo number_format($total_gross, 2); ?></th>
                    <th><?php echo number_format($total_working_days); ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?php echo number_format($total_deductions, 2); ?></th>
                    <th><?php echo number_format($total_net_pay, 2); ?></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/payroll_modal.php'; ?>


</div>
<?php include 'includes/scripts.php'; ?> 
<script>
$(function(){
  $('.edit').click(function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $('.delete').click(function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $("#reservation").on('change', function(){
    var range = encodeURI($(this).val());
    window.location = 'payroll.php?range='+range;
  });

  $('#payroll').click(function(e){
    e.preventDefault();
    $('#payForm').attr('action', 'payroll_generate.php');
    $('#payForm').submit();
  });

  $('#payslip').click(function(e){
    e.preventDefault();
    $('#payForm').attr('action', 'payslip_generate.php');
    $('#payForm').submit();
  });

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'payroll_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('#posid').val(response.id);
      $('#edit_title').val(response.description);
      $('#edit_rate').val(response.rate);
      $('#del_posid').val(response.id);
      $('#del_position').html(response.description);
      $('#attid').val(response.attid);
      $('#employee_name').html(response.firstname+' '+response.lastname);
      $('#del_attid').val(response.attid);
      $('#del_employee_name').html(response.firstname+' '+response.lastname);
    }
  });
}
</script>
</body>
</html>
