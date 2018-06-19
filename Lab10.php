<?php
//****** Hint ******
//connect database and fetch data here
try{
    //连接数据库
    $pdo = new PDO('mysql:host=localhost;dbname=travel', 'root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //fetch the continents     +++ContinentCode
    $sqlContinent = "select ContinentCode, ContinentName from continents";
    $resultContinent = $pdo->query($sqlContinent);
    //fetch the countries      +++Continent,
    $sqlCountries = "select * from countries";
    $resultCountries = $pdo->query($sqlCountries);
    //fetch the IDs of the images, Continent, CountryName
    $sqlID = "select * from imagedetails";
    $resultID = $pdo->query($sqlID);
}catch(PDOException $e){
    echo "Couldn't connect to the database;" . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Chapter 14</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/captions.css" />
    <link rel="stylesheet" href="css/bootstrap-theme.css" />    

</head>

<body>
    <?php include 'header.inc.php'; ?>

    <!-- Page Content -->
    <main class="container">
        <div class="panel panel-default">
          <div class="panel-heading">Filters</div>
          <div class="panel-body">
            <form action="Lab10.php" method="get" class="form-horizontal">
              <div class="form-inline">
                  <form>
                      <select name="continents[]" class="form-control">
                          <option value="0">Select Continent</option>
                          <?php
                          //+++++++++++continents+++++++++++++
                          try{
                              while($row = $resultContinent->fetch()) {//fetch_assoc()
                                  echo '<option value=' . $row['ContinentCode'] . '>' . $row['ContinentName'] . '</option>';
                              }
                          }catch(PDOException $e){
                              die( $e->getMessage() );
                          }
                          ?>
                      </select>

                      <select name="countries[]" class="form-control">

                          <option value="0">Select Country</option>
                          <?php
                          //++++++++++countries+++++++++++++
                          try{
                              while($row = $resultCountries->fetch()) {//fetch_assoc()
                                  echo '<option value=' . $row['ISO'] . '>' . $row['CountryName'] . '</option>';
                              }
                          }catch(PDOException $e2){
                              die( $e2->getMessage() );
                          }
                          ?>
                      </select>

                      <input type="text"  placeholder="Search title" class="form-control" name="title" onclick="loadXMLDoc()">
                      <button type="submit" class="btn btn-primary" name="button">Filter</button>
                  </form>
                  <script>
                      function loadXMLDoc(){
                          xmlhttp = new XMLHttpRequest();//创建XMLHttpRequest对象
                          xmlhttp.open("GET","test1.txt",true);
                          xmlhttp.send();
                      }
                  </script>

              </div>
            </form>

          </div>
        </div>

        <?php
        function filterByContinent($continentID){
            $rightContinent = false;
            if(array_key_exists('button', $_GET)){
                try{
                    $a = join(' ', $_GET['continents']);
                    if($a == $continentID || $a == false){
                        $rightContinent = true;
                    }
                }catch(Exception $e){
                    echo 'filterByContinent';
                }
            }else{
                $rightContinent = true;
            }
            return $rightContinent;
        }

        function filterByCountry($countryID){
            $rightCountry = false;
            if(array_key_exists('button', $_GET)){
                try{
                    $b = join(' ', $_GET['countries']);
                    //echo '张艺兴：$b:'.$b.'; ';
                    if($b == $countryID || $b == false){
                        $rightCountry = true;
                    }
                }catch(Exception $e){
                    echo 'filterByCountry';
                }
            }else{
                $rightCountry = true;
            }
            return $rightCountry;
        }

        ?>

		<ul class="caption-style-2">
            <?php
            //****** Hint ******
            $count = 0;
            try{
                while($row = $resultID->fetch()) {//fetch_assoc()
                    $continentID = $row['ContinentCode'];
                    $countryID = $row['CountryCodeISO'];
                    $rightConti = filterByContinent($continentID);
                    //echo '$rightConti是：'. $rightConti .".";
                    $rightCoun = filterByCountry($countryID);
                    //echo '$countryID:'. $countryID;
                    //echo '; $rightCoun是：'.$rightCoun . '.<br>';
                    if($rightCoun && $rightConti){
                        echo "<li>";
                        echo "<a href='detail.php?id=";
                        echo $row['ImageID'];
                        echo "class='img-responsive>";
                        echo "<img src='images/square-medium/";
                        echo $row['Path'];
                        echo "' alt='";
                        echo $row['Title'];
                        echo "'>";
                        echo "<div class='caption'>";
                        echo "<div class='blur'>";
                        echo "<p>";
                        echo trim($row['Title'],' \n');
                        echo "</p>";
                        echo "</div>";
                        echo "<div class='caption-text'>";
                        echo "</div></div>";
                        echo "</a></li>";
                    }else{
                        //echo $count;
                    }
                    $count++;

                }
            }catch(PDOException $e3){
                //echo '$e3';
                die($e3->getMessage());
            }

            /* use while loop to display images that meet requirements ... sample below ...replace ???? with field data
            <li>
              <a href="detail.php?id=????" class="img-responsive">
                <img src="images/square-medium/222222.jpg" alt="222222.jpg">
                <div class="caption">
                  <div class="blur"></div>
                  <div class="caption-text">
                    <p>????</p>
                  </div>
                </div>
              </a>
            </li>        
            */
            ?>
       </ul>       

      
    </main>
    
    <footer>
        <div class="container-fluid">
                    <div class="row final">
                <p>Copyright &copy; 2017 Creative Commons ShareAlike</p>
                <p><a href="#">Home</a> / <a href="#">About</a> / <a href="#">Contact</a> / <a href="#">Browse</a></p>
            </div>            
        </div>
        

    </footer>
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>

</html>