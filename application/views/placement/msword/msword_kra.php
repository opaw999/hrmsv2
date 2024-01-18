<?php
//include("header.php");
header("Content-Type: application/msword");
header("Content-Disposition: attachment; filename=KRA ($position).doc");

?>
<html>
<body>

<center> <h3> KEY RESPONSIBILITY AREA </h3> </center> 

<?php
if($company) {
    echo "Company: <i>" . ucwords(strtolower($company)) . "</i> <br>";
}
if($bunit) {
    echo "Business Unit: <i>" . ucwords(strtolower($bunit)) . "</i> <br>";
}
if($dept) {
    echo "Department: <i>" . ucwords(strtolower($dept)) . "</i> <br>";
}
if($section) {
    echo "Section: <i>" . ucwords(strtolower($section)) . "</i> <br>";
}
if($subsec) {
    echo "Sub Section: <i>" . ucwords(strtolower($subsec)) . "</i> <br>";
}
if($unit) {
    echo "Unit: <i>" . ucwords(strtolower($unit)) . "</i> <br>";
}
?>

<br><b>Position</b> <br> <?php echo $position;?> <br>

<?php echo "<br><h4>Job Summary</h4><p style='text-align:justify'>" . $summary . "</p><h4>Job Description</h4>" . $jobdesc;?>


</body>
</html>

