<?php
session_start();
include("database.php");
include("header.php");

$group_id = $_SESSION["group_id"];
$sql = "SELECT * FROM groups WHERE group_id = ?";
/** @var \mysqli $conn */

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $group_id);
$stmt->execute();
$result = $stmt->get_result();
$actual_group = $result->fetch_assoc();

$user_id = $_SESSION["user_id"];
$month = $_SESSION["current_month"];
$year = $_SESSION["current_year"];

error_reporting(E_ALL);
ini_set('display_errors', 1);

function get_total_income_and_expense($month, $year)
{
    global $conn, $group_id;

    $sql = "SELECT u.user_id, t.transac_type, SUM(t.amount) as total FROM grp_" . $group_id . "_data t INNER JOIN users u ON t.inserted_by = u.user_id WHERE MONTH(t.created_at) = $month AND YEAR(t.created_at) = $year GROUP BY u.user_id, t.transac_type";
    /** @var \mysqli $conn */
    $result = mysqli_query($conn, $sql);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['transac_type'] == 'income') {
            $data[$row['user_id']]['income'] = $row['total'];
        } else if ($row['transac_type'] == 'expense') {
            $data[$row['user_id']]['expense'] = $row['total'];
        }
    }

    return $data;
}

$totals = get_total_income_and_expense($month, $year);
$income_total = $totals['income'] ?? 0;
$expense_total = $totals['expense'] ?? 0;

$data = get_total_income_and_expense($month, $year);

function get_user_name_by_id($user_id)
{
    /** @var \mysqli $conn */
    global $conn;
    $sql = "SELECT username FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die('Error en la consulta SQL: ' . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
    return $row['username'];
}

$rows = array();

foreach ($data as $user_id => $user_data) {
    $user_name = get_user_name_by_id($user_id); // 
    $income = $user_data['income'] ?? 0;
    $expense = $user_data['expense'] ?? 0;
    $total = $income - $expense;
    $total = floatval($total);
    $rows[] = array($user_name, $total);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">

    <title>Monthly Budget</title>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <section id="stats-titles">
        <h1 class="white">Stats</h1>
        <h1 id="group-name-header-stats"> 📈 <?php echo strtoupper($actual_group["group_name"]); ?> 📈</h1>
    </section>

    <!--date-->
    <section>
        <article id="date-prev-next-container">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <input type="hidden" name="form_id" value="form_date_selector">
                <input type="submit" name="prev_date_select" id="prev-date-select" value="<">

                <p>
                    <?php
                    //month to string format
                    $dateObj = DateTime::createFromFormat('!m', $_SESSION["current_month"]);
                    $monthName = $dateObj->format('M');

                    echo "<div id='actual-month-container'><p>" . $monthName . "/" . $_SESSION["current_year"] . "</p></div>";
                    ?>
                </p>

                <input type="submit" name="next_date_select" id="next-date-select" value=">">
            </form>
        </article>

        <section id="accordions-container">


            <?php
            //only process if at least two members are present, otherwise it´s pointless (and it breaks!)
            if (count($rows) > 1) {
                echo '
            <div class="accordion" >
                <div class="label">
                    <p>Ver por usuario <span class="arrow">🔻</span></p>
                </div>
                <div class="content">
                    <section>

                        <div>
                            <canvas id="chart_users"></canvas>
                        </div>';


                // Define colors
                $colors = [
                    'rgba(221, 120, 90, 0.2)', // Salmon custom
                    'rgba(90, 121, 149, 0.2)', // Deep Blue custom
                    'rgba(255, 206, 86, 0.2)', // Amarillo
                    'rgba(75, 192, 192, 0.2)', // Verde
                    'rgba(153, 102, 255, 0.2)' // Morado
                ];

                // Get groups´ total
                $total_group = 0;
                foreach ($rows as $row) {
                    $total_group += $row[1];
                }

                // Get each user´s percentage
                $labels = array();
                $data = array();
                $backgroundColor = array();
                foreach ($rows as $i => $row) {
                    $labels[] = $row[0];
                    $data[] = ($row[1] / $total_group) * 100;
                    $backgroundColor[] = $colors[$i % count($colors)]; // One colour for each user
                    $user_totals[] = $row[0] . ': ' . $row[1];
                }

                // Make Pie Graphic -  Chart.js
                echo '<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>';
                echo '<script>';
                echo 'const ctx = document.getElementById("chart_users").getContext("2d");';
                echo 'const myChart = new Chart(ctx, {';
                echo ' type: "pie",';
                echo ' data: {';
                echo '  labels: ' . json_encode($labels) . ',';
                echo '  datasets: [{';
                echo '      data: ' . json_encode($data) . ',';
                echo '      backgroundColor: ' . json_encode($backgroundColor) . ',';
                echo '  }],';
                echo ' },';
                echo ' options: {';
                echo '  plugins: {';
                echo '      datalabels: {';
                echo '          formatter: (value, ctx) => {';
                echo '             let sum = 0;';
                echo '             let dataArr = ctx.chart.data.datasets[0].data;';
                echo '             dataArr.map(data => {';
                echo '               sum += data;';
                echo '             });';
                echo '             let percentage = (value * 100 / sum).toFixed(2) + "%";';
                echo '             return percentage;';
                echo '          },';
                echo '          color: "#b5b5b5",';
                echo '          font: {';
                echo '              size: 24,';
                echo '              weight: "bold",';
                echo '              family: "Kreon"';
                echo '          }';
                echo '      }';
                echo '  }';
                echo ' },';
                echo ' plugins: [ChartDataLabels],';
                echo '});';
                echo '</script>';

                // Print each user´s totals
                echo '<div style="text-align:center;">';
                echo '<div class="underline">Totales parciales</div>';
                foreach ($rows as $row) {
                    echo '<p>' . $row[0] . ': $' . number_format($row[1], 2, '.', ',') . '</p>';
                }
                echo '</div>';

                // Calculate equilibrium for each user
                $equilibrium_per_user = array();
                foreach ($rows as $row) {
                    $equilibrium_per_user[] = ($total_group - $row[1]) / count($rows);
                }

                // Calculate total average
                $average_total = $total_group / count($rows);

                // Calculate each user´s rest till equality
                $equilibrium_per_user = array();
                foreach ($rows as $row) {
                    $equilibrium_per_user[] = $average_total - $row[1];
                }

                echo '<hr style="width:75%">';

                // Print each user equality´s left
                echo '<div style="text-align:center;">';
                echo '<div class="underline">Compensación</div>';
                foreach ($equilibrium_per_user as $i => $equilibrium) {
                    if ($equilibrium > 0) {
                        $action = 'dar';
                        $color = 'red';
                    } else {
                        $action = 'recibir';
                        $color = 'blue';
                        $equilibrium = abs($equilibrium); // Convertir el equilibrio a un valor absoluto
                    }
                    echo '<p>' . $labels[$i] . ' debe ' . $action . ': $<span style="color: ' . $color . '">' . number_format($equilibrium, 2, '.', ',') . '</span></p>';
                }
                echo '</div>';
            }
            ?>
        </section>
        </div>
        </div>


        <div class="accordion">
            <div class="label">
                <p>Valores absolutos <span class="arrow">🔻</span></p>
            </div>
            <div class="content">
                <section>

 <?php

 
                   
                    $data = get_total_income_and_expense($month, $year);

                    $income_total = 0;
                    $expense_total = 0;

                    foreach ($data as $user_data) {
                        $income_total += $user_data['income'] ?? 0;
                        $expense_total += $user_data['expense'] ?? 0;
                    }

 if($income_total > 0 && $expense_total > 0){

                    $total = $income_total + $expense_total;

                    if ($total == 0) {
                        $income_percentage = 0;
                        $expense_percentage = 0;
                    } else {
                        $income_percentage = ($income_total / $total) * 100;
                        $expense_percentage = ($expense_total / $total) * 100;
                    }
                    // Calcula la relación porcentual entre el ingreso total y el egreso total.
                    if ($income_total == 0 && $expense_total == 0) {
                        $income_percentage = 0;
                        $expense_percentage = 0;
                     } else {
                        $income_percentage = ($income_total / ($income_total + $expense_total)) * 100;
                        $expense_percentage = ($expense_total / ($income_total + $expense_total)) * 100;
                     }


                    $income_percentage = ($income_total / ($income_total + $expense_total)) * 100;
                    $expense_percentage = ($expense_total / ($income_total + $expense_total)) * 100;


                    // Show graph
                    echo '<div>';
                    echo ' <canvas id="chart_percentages"></canvas>';
                    echo '</div>';

                    echo '<script>';
                    echo ' const ctx2 = document.getElementById("chart_percentages").getContext("2d");';
                    echo ' const myChart2 = new Chart(ctx2, {';
                    echo ' type: "pie",';
                    echo ' data: {';
                    echo '  labels: ["Ingresos", "Egresos"],';
                    echo '  datasets: [{';
                    echo '    data: [' . $income_percentage . ', ' . $expense_percentage . '],';
                    echo '    backgroundColor: ["rgba(75, 192, 192, 0.2)", "rgba(255, 99, 132, 0.2)"],';
                    echo '    borderColor: ["rgba(75, 192, 192, 1)", "rgba(255, 99, 132, 1)"],';
                    echo '    borderWidth: 1';
                    echo '  }]';
                    echo ' },';
                    echo ' options: {';
                    echo '  responsive: true,';
                    echo '  plugins: {';
                    echo '    datalabels: {';
                    echo '      formatter: (value, ctx) => {';
                    echo '        let sum = 0;';
                    echo '        let dataArr = ctx.chart.data.datasets[0].data;';
                    echo '        dataArr.map(data => {';
                    echo '          sum += data;';
                    echo '        });';
                    echo '        let percentage = (value * 100 / sum).toFixed(2) + "%";';
                    echo '        return percentage;';
                    echo '      },';
                    echo '      color: "#b5b5b5",';
                    echo '      font: {';
                    echo '        family: "Kreon",';
                    echo '        size: 14';
                    echo '      }';
                    echo '    }';
                    echo '  }';
                    echo ' }';
                    echo ' });';
                    echo '</script>';


                    // Show totals
                    echo '<div style="text-align:center;">';
                    echo'<div class="underline">Totales</div>';
                    echo "<p>Ingreso: $" . number_format($income_total, 2, '.', ',') . " (<span class='make-bold'>" .number_format($income_percentage, 2, '.', ',') . "%</span>)" . "</p>";
                    echo "<p>Egreso: $" . number_format($expense_total, 2, '.', ',') . " (<span class='make-bold'>" . number_format($expense_percentage, 2, '.', ',') . "%</span>)". "</p>";
                    echo"<p>Resultado: " . number_format($income_total-$expense_total, 2, '.', ',') . "</p>";

                    } 
                    else {
                        echo'<p class="less-width" style="padding:16px 0;">este mes aún no tiene suficientes transacciones cargadas como para brindarte este gráfico.</p>';
                    }
                    ?>

                </section>



            </div>
        </div>


        <div class="accordion">
            <div class="label">
                <p>Comparativo 12 meses<span class="arrow">🔻</span></p>
            </div>
            <div class="content">
              <section>
<?php
function get_total_income_and_expense_for_month_dolarblue($month, $year)
{
   global $conn, $group_id;

   $sql = "SELECT u.user_id, t.transac_type, SUM(t.amount / t.dolarblue) as total FROM grp_" . $group_id . "_data t INNER JOIN users u ON t.inserted_by = u.user_id WHERE MONTH(t.created_at) = $month AND YEAR(t.created_at) = $year GROUP BY u.user_id, t.transac_type";
 
   /** @var \mysqli $conn */
   $result = mysqli_query($conn, $sql);

   $data = array();
   while ($row = mysqli_fetch_assoc($result)) {
       if ($row['transac_type'] == 'income') {
           $data[$row['user_id']]['income'] = $row['total'];
       } else if ($row['transac_type'] == 'expense') {
           $data[$row['user_id']]['expense'] = $row['total'];
       }
   }

   return $data;
}

$labels = array();
$incomes = array();
$expenses = array();

for ($i = 0; $i < 12; $i++) {
   $month = $_SESSION["current_month"] - $i;
   $year = $_SESSION["current_year"];

   if ($month < 1) {
       $month += 12;
       $year--;
   }

   $data = get_total_income_and_expense_for_month_dolarblue($month, $year);
   $income_total = 0;
   $expense_total = 0;

   foreach ($data as $user_data) {
       $income_total += $user_data['income'] ?? 0;
       $expense_total += $user_data['expense'] ?? 0;
   }

   $labels[] = date('M', mktime(0, 0, 0, $month, 1, $year));
   $incomes[] = $income_total;
   $expenses[] = $expense_total;
}

echo '<div>';
echo ' <canvas id="chart_bars"></canvas>';
echo '</div>';

echo '<script>';
echo ' const ctx3 = document.getElementById("chart_bars").getContext("2d");';
echo ' const myChart3 = new Chart(ctx3, {';
echo ' type: "bar",';
echo ' data: {';
echo ' labels: ' . json_encode($labels) . ',';
echo ' datasets: [{';
echo '    label: "Ingresos",';
echo '    data: ' . json_encode($incomes) . ',';
echo '    backgroundColor: "rgba(75, 192, 192, 0.2)",';
echo '    borderColor: "rgba(75, 192, 192, 1)",';
echo '    borderWidth: 1';
echo ' }, {';
echo '    label: "Egresos",';
echo '    data: ' . json_encode($expenses) . ',';
echo '    backgroundColor: "rgba(255, 99, 132, 0.2)",';
echo '    borderColor: "rgba(255, 99, 132, 1)",';
echo '    borderWidth: 1';
echo ' }]';
echo ' },';
echo ' options: {';
echo ' scales: {';
echo '    x: {';
echo '        stacked: false,';
echo '        reverse: true'; // Sort x axis
echo '    },';
echo '    y: {';
echo '        stacked: false';
echo '    }';
echo ' }';
echo ' }';
echo ' });';
echo '</script>';

        // Print text behind
        echo '<div style="text-align:center;">';
        echo '<p class="small-text">Los valores están expresados en dolar blue (venta) que se toma al día de cada transacción, para facilitar el seguimiento de su evolución aún en contextos de alta inflación</p>';
?>

              </section>

            </div>
        </div>
    </section>



    </section>




    <div id="gray-mask-for-body"></div>
    <script src="./script.js"></script>


    <script src="./stats.js"></script>


</body>

</html>


<!--actualizar mes -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["form_id"] == "form_date_selector")) {
    if (isset($_POST["prev_date_select"])) {
        $date = DateTime::createFromFormat('Y-m', $_SESSION["current_year"] . '-' . $_SESSION["current_month"]);
        $date->modify('-1 month');
        $_SESSION["current_month"] = $date->format('m');
        $_SESSION["current_year"] = $date->format('Y');

        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    } else if (isset($_POST["next_date_select"])) {
        $date = DateTime::createFromFormat('Y-m', $_SESSION["current_year"] . '-' . $_SESSION["current_month"]);
        $date->modify('+1 month');
        $_SESSION["current_month"] = $date->format('m');
        $_SESSION["current_year"] = $date->format('Y');

        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}

?>
<?php include './footer.php'; ?>