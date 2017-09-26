<html>
<meta charset="utf-8">
<title>Lipscomb</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
    <img class="logo" src="./logo.png" width="440" height="140">
  <h2>  OncoEval V1.0 </h2>

    Based on the file you have uploaded the results are as below:

<br />
  </body>
</html>
<?php
if (isset($_POST['submit'])) {
    //$file = $_FILES['file'];

    //Collecting inforamtion on the file being submitted
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    $fileDestination = '/Applications/MAMP/htdocs/CX3/uploads/';
    $wholepath= $fileDestination.$fileName;
    $python_csv='/Applications/MAMP/htdocs/CX3/uploads/Results.csv';


    //Getting file type extension
    $fileExplode = explode('.', $fileName);
    $fname=$fileExplode[0];
    $fext=$fileExplode[1];
  //  echo $_FILES['file']['name'];
    //echo "fname: ".$fname."<br />";
    //echo "file extention:".$fext."<br />";
    //echo $wholepath;
    //File types to allow
    $allowed = array('csv');

    //Checks size of file
    if (in_array($fext, $allowed)) {
        if ($fileError === 0) {
            if($fileSize < 100000000) {

          if(is_uploaded_file($fileTmpName))
          {//echo "================================file uploaded=============================\n";
          }
          if (move_uploaded_file($fileTmpName,"/Applications/MAMP/htdocs/CX3/uploads/".$fileName))

          {

               //echo "==========================================Your file is successfully moved=============================\n";

               //echo "==========================================Initiating Python script =============================\n";
              shell_exec ("python /Applications/MAMP/htdocs/CX3/uploads/OncoEval_BCV2.py $wholepath");


            shell_exec ("python /Applications/MAMP/htdocs/CX3/uploads/OncoEval_BCV2.py $wholepath 2>> ./python_error.log");

            // echo "================You fiile is being processedXPHPXXX ==========================\n";

//Reading form CSV  Result file and getting the Biomarkers
             if (($handle = fopen($python_csv, "r")) !== FALSE) {
                 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
                        $pyout= $data[0];
                           echo "<br /> <br />Number of Positve Biomarkers in your file was :  ==>".$pyout;
                           echo "<br />";
                    // }
                 }
               fclose($handle);
             }
// Checking Biomarkers

if ($pyout >=0 && $pyout<=3)
{
  echo "<br>Suggested Treatments: NA <br />";
  echo "<br />";
 echo "Treatments to Avoid : NA <br />";
 echo "<br />";
 }
 elseif ($pyout >=4 && $pyout<=6)
 {
   echo "<br>Suggested Treatments: Radiation Therapy, Simple Mastectomy or Lumpectomy <br />";
   echo "<br />";
    echo "Treatments to Avoid : Modified Radical Mastectomy <br />";
    echo "<br />";
 }
 elseif (($pyout >=7 && $pyout<=9))
 {
   echo "<br>Suggested Treatments: Post-Op Drug Treatment<br />";
   echo "<br />";
    echo "Treatments to Avoid : NA <br />";
    echo "<br />";
 }

//Array for life expectacny

             $result = array( array('90-95%', '95-100%' ,'95-100%'),
               array('80-85%', '95%' , '95-100%'),
               array('60-65%', '85-90%' , '	95%'),
               array('50%', '80-85%' , '90-95'),
               array('40%', '75-80%' , '90%'),
               array('35%', '75%' , '85-90%'),
               array('25-30%', '70%' , '85%'),
               array('20%', '50%-650%' , '80-85%'),
               array('15%', '55%' , '75-80%'),
               array('5%', '40-45%' , '70%'));

          //  }   //added for debugging

        }
        else {
          echo "The file upload is failed";
        }
            }else {
                echo "Your file is too big!";
            }
        }else {
            echo "There was an error uplading your file";
        }
    //}
  //  else {
    //    echo "You cannot upload files of this type!"; }

}
?>

<html>
Life expectancy of the patient is based on the Biomarkers. The below chart shows the rate of life expectacny in years VS the number of positive Biomarkers
<p></p>
<p>Life Expectancy rating.</p>
<table class="GeneratedTable">
<thead>
<tr>
<th>Count of Positive bio Marker <br />/ Life expectancy</th>
<th>0 to 3</th>
<th>4 to 6</th>
<th>7 to 9</th>
</tr>
</thead>
<tbody>
<tr>
<td>1 Year</td>
<td>90-95%</td>
<td>95-100%</td>
<td>95-100%</td>
</tr>
<tr>
<td>2 Years</td>
<td>80-85%</td>
<td>95%</td>
<td>95-100%</td>
</tr>
<tr>
<td>3 Years</td>
<td>60-65%</td>
<td>85-90%</td>
<td>95%</td>
</tr>
<tr>
<td>4 Years</td>
<td>50%</td>
<td>80-85%</td>
<td>90-95%</td>
</tr>
<tr>
<td>5 Years</td>
<td>40%</td>
<td>75-80%</td>
<td>90%</td>
</tr>
<tr>
<td>6 Years</td>
<td>35%</td>
<td>75%</td>
<td>85-90%</td>
</tr>
<tr>
<td>7 Years</td>
<td>25-30%</td>
<td>70%</td>
<td>85%</td>
</tr>
<tr>
<td>8 Years</td>
<td>20%</td>
<td>60-65%</td>
<td>80-85%</td>
</tr>
<tr>
<td>9 Years</td>
<td>15%</td>
<td>55%</td>
<td>75-80%</td>
</tr>
<tr>
<td>10 Years</td>
<td>5%</td>
<td>40-45%</td>
<td>70%</td>
</tr>
</tbody>
</table>

<p></p>
</html>
