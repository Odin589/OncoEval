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
    $cancer_type=$_POST['cancer_type'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    $fileDestination = '/var/www/html/oncoeval/uploads/';
    $wholepath= $fileDestination.$fileName;
    $python_csv='/var/www/html/oncoeval/pyt/Results.csv';
    $servername = "localhost:8889";
    $username = "root";
    $password = "root";



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

       // if(is_uploaded_file($fileTmpName))
         // {echo "================================file uploaded=============================\n";
         // }
          if (move_uploaded_file($fileTmpName,"/var/www/html/oncoeval/uploads/".$fileName))

          { echo"file moved";
            if ($cancer_type=='Breast')
            {
                shell_exec ("python /var/www/html/oncoeval/pyt/OncoEval_Breast_Only.py $wholepath");


            shell_exec ("python /var/www/html/oncoeval/pyt/OncoEval_BCV2.py $wholepath 2>> ./python_error.log");



//Reading form CSV  Result file and getting the Biomarkers
             if (($handle = fopen($python_csv, "r")) !== FALSE) {
                 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
                        $pyout= $data[0];
                           echo "<br /> <br />Number of Positve Biomarkers in your file is :  ==> ".$pyout;
                           echo "<br />";
                           echo "Cancer type selected is ". $cancer_type. " cancer";
                           echo "<br />";
                           echo "<br />";

                    // }
                 }
               fclose($handle);

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
                echo "<table border=\"1\">\n";
                echo "<tr><th bgcolor=\"#CCCCFF\">Positive bio Marker \</br> Life expectancy</th>
                          <th bgcolor=\"#CCCCFF\">0 to 3 Positive biomarkers</th>
                          <th bgcolor=\"#CCCCFF\">4 to 6 Positive biomarkers</th>
                          <th bgcolor=\"#CCCCFF\">7 to 9 Positive biomarkers</th>
                </tr>";

              echo "<tr>
                <td align=\"left\">1 Year</td>
                <td align=\"left\">90-95%</td>
                <td align=\"left\">95-100%</td>
                <td align=\"left\">95-100%</td>
                </tr>
                <tr>
                <td align=\"left\">2 Years</td>
                <td align=\"left\">80-85%</td>
                <td align=\"left\">95%</td>
                <td align=\"left\">95-100%</td>
                </tr>
                <tr>
                <td align=\"left\">3 Years</td>
                <td align=\"left\">60-65%</td>
                <td align=\"left\">85-90%</td>
                <td align=\"left\">95%</td>
                </tr>
                <tr>
                <td align=\"left\">4 Years</td>
                <td align=\"left\">50%</td>
                <td align=\"left\">80-85%</td>
                <td align=\"left\">90-95%</td>
                </tr>
                <tr>
                <td align=\"left\">5 Years</td>
                <td align=\"left\">40%</td>
                <td align=\"left\">75-80%</td>
                <td align=\"left\">90%</td>
                </tr>
                <tr>
                <td align=\"left\">6 Years</td>
                <td align=\"left\">35%</td>
                <td align=\"left\">75%</td>
                <td align=\"left\">85-90%</td>
                </tr>
                <tr>
                <td align=\"left\">7 Years</td>
                <td align=\"left\">25-30%</td>
                <td align=\"left\">70%</td>
                <td align=\"left\">85%</td>
                </tr>
                <tr>
                <td align=\"left\">8 Years</td>
                <td align=\"left\">20%</td>
                <td align=\"left\">60-65%</td>
                <td align=\"left\">80-85%</td>
                </tr>
                <tr>
                <td align=\"left\">9 Years</td>
                <td align=\"left\">15%</td>
                <td align=\"left\">55%</td>
                <td align=\"left\">75-80%</td>
                </tr>
                <tr>
                <td align=\"left\">10 Years</td>
                <td align=\"left\">5%</td>
                <td align=\"left\">40-45%</td>
                <td align=\"left\">70%</td>
                </tr>
                </table>";
              }
              //Lung cancer
              elseif ($cancer_type=='Lung')
              {
                 shell_exec ("python /var/www/html/oncoeval/pyt/OncoEval_Lung_Only.py $wholepath");


             shell_exec ("python /var/www/html/oncoeval/pyt/OncoEval_BCV2.py $wholepath 2>> ./python_error.log");


 //Reading form CSV  Result file and getting the Biomarkers
              if (($handle = fopen($python_csv, "r")) !== FALSE) {
                  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
                         $pyout= $data[0];
                            echo "<br /> <br />Number of Positve Biomarkers in your file is :  ==> ".$pyout;
                            echo "<br />";
                            echo "<br />";
                            echo "Cancer type selected is ". $cancer_type. " cancer";
                            echo "<br />";
                     // }
                  }
                fclose($handle);

                if ($pyout >=0 && $pyout<=3)
                {
                  echo "<br>Suggested Individual Chemotherapies: NA <br />";
                  echo "<br />";
                 echo "Suggested Combination Chemotherapies: NA <br />";
                 echo "<br />";
                 echo "Suggested Therapies to Avoid: NA <br />";
                 echo "<br />";
                 }
                 elseif ($pyout >=4 && $pyout<=5)
                 {
                   echo "<br>Suggested Individual Chemotherapies: NA <br />";
                   echo "<br />";
                  echo "Suggested Combination Chemotherapies: NA <br />";
                  echo "<br />";
                  echo "Suggested Therapies to Avoid: NA <br />";
                  echo "<br />";
                 }
                 elseif (($pyout >=6 && $pyout<=7))
                 {
                   echo "<br>Suggested Individual Chemotherapies: Carboplatin <br />";
                   echo "<br />";
                  echo "Suggested Combination Chemotherapies: NA <br />";
                  echo "<br />";
                  echo "Suggested Therapies to Avoid: NA <br />";
                  echo "<br />";
                 }
                 elseif (($pyout >=8 && $pyout<=10))
                 {
                   echo "<br>Suggested Individual Chemotherapies: Cisplatin <br />";
                   echo "<br />";
                  echo "Suggested Combination Chemotherapies: Cisplatin and Vinorelbine <br />";
                  echo "<br />";
                  echo "Suggested Therapies to Avoid: NA <br />";
                  echo "<br />";
                }
                elseif (($pyout >=11 && $pyout<=16))
                {
                echo "<br>Suggested Individual Chemotherapies: NA <br />";
                echo "<br />";
                echo "Suggested Combination Chemotherapies: NA <br />";
                echo "<br />";
                echo "Suggested Therapies to Avoid: Radiation Therapy <br />";
                echo "<br />";
                }
                echo "Predictive Treatment Analysis for Lung Cancer (Non-Small Cell)";
                echo "<table border=\"1\">\n";
                echo "<tr><th bgcolor=\"#CCCCFF\">Positive bio Marker \</br> Life expectancy</th>
                          <th bgcolor=\"#CCCCFF\">0 to 3 Positive biomarkers</th>
                          <th bgcolor=\"#CCCCFF\">4 to 5 Positive biomarkers</th>
                          <th bgcolor=\"#CCCCFF\">6 to 7 Positive biomarkers</th>
                          <th bgcolor=\"#CCCCFF\">8 to 10 Positive biomarkers</th>
                          <th bgcolor=\"#CCCCFF\">11 to 16 Positive biomarkers</th>

                </tr>";

              echo "<tr>
                <td align=\"left\">1 Year</td>
                <td align=\"left\">75-80%</td>
                <td align=\"left\">80%</td>
                <td align=\"left\">85%</td>
                <td align=\"left\">85-90%</td>
                <td align=\"left\">90-95%</td>
                </tr>
                <tr>
                <td align=\"left\">2 Years</td>
                <td align=\"left\">55-60%</td>
                <td align=\"left\">60-65%</td>
                <td align=\"left\">70%</td>
                <td align=\"left\">75%</td>
                <td align=\"left\">80-85%</td>

                </tr>
                <tr>
                <td align=\"left\">3 Years</td>
                <td align=\"left\">40-45%</td>
                <td align=\"left\">45-50%</td>
                <td align=\"left\">55-60%</td>
                <td align=\"left\">65%</td>
                <td align=\"left\">70-75%</td>

                </tr>
                <tr>
                <td align=\"left\">4 Years</td>
                <td align=\"left\">30-35%</td>
                <td align=\"left\">35-40%</td>
                <td align=\"left\">45-50%</td>
                <td align=\"left\">55-60%</td>
                <td align=\"left\">65-70%</td>

                </tr>
                <tr>
                <td align=\"left\">5 Years</td>
                <td align=\"left\">20-25%</td>
                <td align=\"left\">25-30%</td>
                <td align=\"left\">40%</td>
                <td align=\"left\">45-50%</td>
                <td align=\"left\">60%</td>

                </tr>
                <tr>
                <td align=\"left\">6 Years</td>
                <td align=\"left\">15-20%</td>
                <td align=\"left\">20-25%</td>
                <td align=\"left\">35%</td>
                <td align=\"left\">40-45%</td>
                <td align=\"left\">55%</td>

                </tr>
                <tr>
                <td align=\"left\">7 Years</td>
                <td align=\"left\">15%</td>
                <td align=\"left\">20%</td>
                <td align=\"left\">30-35%</td>
                <td align=\"left\">40%</td>
                <td align=\"left\">50-55%</td>

                </tr>
                <tr>
                <td align=\"left\">8 Years</td>
                <td align=\"left\">10-15%</td>
                <td align=\"left\">15-20%</td>
                <td align=\"left\">25-30%</td>
                <td align=\"left\">35%</td>
                <td align=\"left\">45-50%</td>

                </tr>
                <tr>
                <td align=\"left\">>9 Years</td>
                <td align=\"left\">>10-15%</td>
                <td align=\"left\">>15-20%</td>
                <td align=\"left\">>25-30%</td>
                <td align=\"left\">35%</td>
                <td align=\"left\">40-45%</td>

                </tr>
                <tr>
                <td align=\"left\">10 Years</td>
                <td align=\"left\">5-10%</td>
                <td align=\"left\">10%</td>
                <td align=\"left\">15-20%</td>
                <td align=\"left\">25-30%</td>
                <td align=\"left\">40%</td>

                </tr>
                </table>";
              }
              //For Melanoma
              elseif ($cancer_type=='Melanoma') {

               shell_exec ("python /var/www/html/oncoeval/pyt/OncoEval_Melanoma_Only.py $wholepath");


             shell_exec ("python /var/www/html/oncoeval/pyt/OncoEval_BCV2.py $wholepath 2>> ./python_error.log");


 //Reading form CSV  Result file and getting the Biomarkers
              if (($handle = fopen($python_csv, "r")) !== FALSE) {
                  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
                         $pyout= $data[0];
                            echo "<br /> <br />Number of Positve Biomarkers in your file is :  ==> ".$pyout;
                            echo "<br />";
                            echo "<br />";
                            echo "Cancer type selected is ". $cancer_type. " cancer";
                            echo "<br />";
                  }
                fclose($handle);

                if ($pyout >=0 && $pyout<=14)
                {
                  echo "<br>Suggested General and Surgical Therapies: Radiation Therapy <br />";
                  echo "<br />";
                 }

              //Table for Melanoma
              echo " Predictive Treatment Analysis for Melanoma";
              echo "<table border=\"1\">\n";
              echo "<tr><th bgcolor=\"#CCCCFF\">Positive bio Marker \</br> Life expectancy</th>
                        <th bgcolor=\"#CCCCFF\">0 to 3 Positive biomarkers</th>
                        <th bgcolor=\"#CCCCFF\">4 to 5 Positive biomarkers</th>
                        <th bgcolor=\"#CCCCFF\">6 to 7 Positive biomarkers</th>


              </tr>";

            echo "<tr>
              <td align=\"left\">1 Year</td>
              <td align=\"left\">90%</td>
              <td align=\"left\">90-95%</td>
              <td align=\"left\">95%</td>
              <td align=\"left\">95-100%</td>

              </tr>
              <tr>
              <td align=\"left\">2 Years</td>
              <td align=\"left\">70-75%</td>
              <td align=\"left\">80-85%</td>
              <td align=\"left\">85-90%</td>
              <td align=\"left\">90-95%</td>

              </tr>
              <tr>
              <td align=\"left\">3 Years</td>
              <td align=\"left\">55%</td>
              <td align=\"left\">70%</td>
              <td align=\"left\">75-80%</td>
              <td align=\"left\">80-85%</td>

              </tr>
              <tr>
              <td align=\"left\">4 Years</td>
              <td align=\"left\">45%</td>
              <td align=\"left\">60-65%</td>
              <td align=\"left\">70-75%</td>
              <td align=\"left\">80%</td>

              </tr>
              <tr>
              <td align=\"left\">5 Years</td>
              <td align=\"left\">40%</td>
              <td align=\"left\">55-60%</td>
              <td align=\"left\">65-70%</td>
              <td align=\"left\">75-80%</td>

            </tr>
              <tr>
              <td align=\"left\">6 Years</td>
              <td align=\"left\">30%</td>
              <td align=\"left\">45-50%</td>
              <td align=\"left\">60-65%</td>
              <td align=\"left\">70%</td>

              </tr>
              <tr>
              <td align=\"left\">7 Years</td>
              <td align=\"left\">25-30%</td>
              <td align=\"left\">40-45%</td>
              <td align=\"left\">55-60%</td>
              <td align=\"left\">65-70%</td>

              </tr>
              <tr>
              <td align=\"left\">8 Years</td>
              <td align=\"left\">20-25%</td>
              <td align=\"left\">40%</td>
              <td align=\"left\">55%</td>
              <td align=\"left\">65%</td>

              </tr>
              <tr>
              <td align=\"left\">>9 Years</td>
              <td align=\"left\">>15-20%</td>
              <td align=\"left\">>35%</td>
              <td align=\"left\">>50%</td>
              <td align=\"left\">60%</td>

              </tr>
              <tr>
              <td align=\"left\">10 Years</td>
              <td align=\"left\">15-20%</td>
              <td align=\"left\">30-35%</td>
              <td align=\"left\">45-50%</td>
              <td align=\"left\">55-60%</td>

              </tr>
              </table>";
              }
//end paranthesis opened after file moving
             }

//Connecting to DB



                       // Create connection
        //               $conn = new mysqli($servername, $username, $password);

                       // Check connection
          //             if ($conn->connect_error) {
            //               die("Connection failed: " . $conn->connect_error);
              //         }
                //       echo "Connected successfully";
// Checking Biomarkers


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

        }
        else {
          echo "The file upload is failed";
        }
            }else {
                echo "Your file is too big!";
            }
        }else {
            echo "There is an error uplading your file";
        }
    //}
  //  else {
    //    echo "You cannot upload files of this type!"; }

}
?>

<html>
Disclaimer:
XXXXXX </html>
