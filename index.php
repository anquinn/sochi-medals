<?php

    // include the library
    include('simple_html_dom.php');
    
    // these are the global arrays
    $theData = array();
    $tableData = array();
    $canada = array();

    // scrape the page
    $theData = getMedals('http://olympics.cbc.ca/medals/');
    $canada = weAreWinter($theData);
    $weAreBetter = notAsGood($theData, $canada[0]);

    function getMedals($page) {
        // Create DOM from URL
        $html = file_get_html($page);
        $table = $html->find('#or-tbl-medal');

        $count = 0;

        // Find all row blocks
        foreach($html->find('tr') as $row) {
            // initialize array to store the cell data from each row
            $rowData = array();

            foreach($row->find('td') as $cell) {
                // push the cell's text to the array
                $rowData[] = $cell->innertext;
            }

            $tableData[$count]=$rowData;
            $count++;
            // push the row's data array to the 'big' array
            $theData[] = $rowData;
        }

        return $theData;
        
    }

    function weAreWinter($theData) {
        for($x=1; $x < count($theData); $x++) {
            if (strcmp(trim(strip_tags($theData[$x][1])), "CAN") ==0 ) {
                $canada[0] = $theData[$x][0];
                $canada[1] = $theData[$x][2];
                $canada[2] = $theData[$x][3];
                $canada[3] = $theData[$x][4];
                $canada[4] = strip_tags($theData[$x][5]);

            }
        } 

        return $canada;
    }

    function notAsGood($theData, $rank) {
        for($x=1; $x < count($theData); $x++) {
            if (strcmp(trim(strip_tags($theData[$x][1])), "USA") ==0 ) {
                $us = $theData[$x][0];
                $diff = $us - $rank;
            }
        } 
        return $diff;
    }

?>


<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Canadian Medals</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php if(strcmp(trim(strip_tags($theData[$x][1])),"CAN")==0) {echo "yes";} ?>
        <div class="main">
            <div class="table">
                <div class="canada">
                    <div class="flag">
                        <img src="flag.png" width="250" >
                    </div>
                    <h1>Canada</h1>
                    <?php
                        echo "<div class='can-rank'>Rank: " . $canada[0] . "</div>";
                        echo "<div class='can-medals'>Medal Count: " . "<span class=' medal-can m-gold'>" . $canada[1] . "</span>" . "<span class=' medal-can m-silver'>" . $canada[2] . "</span>" . "<span class=' medal-can m-bronze'>" . $canada[3] . "</span>" . "<span class='medal-can m-total'>" . "<span class='total-medals'>" .$canada[4] . "</span></span> </div>";
                        echo "<div class='can-place'>Places Above US: " . $weAreBetter . "</div>";
                    ?>
                    

                </div>

                <h2>Medal Count</h2>
                <table class="zebra"> 
                    <thead> 
                        <tr> 
                            <th></th> 
                            <th class="title t-country">Country</th> 
                            <th class="title t-gold"><span class=" medal m-gold"></span></th> 
                            <th class="title t-silver"><span class=" medal m-silver"></span></th> 
                            <th class="title t-bronze"><span class=" medal m-bronze"></span></th>
                            <th class="title t-total"><span class=" medal medal-total"></span></th> 
                        </tr> 
                    </thead>
                    <tbody>
                        <?php
                            for($x=1; $x < count($theData); $x++) {
                                echo "<tr>"; 
                                    echo "<td class='rank'>" . $theData[$x][0] . "</td>"; 
                                    echo "<td class='country'>" . trim(strip_tags($theData[$x][1])) . "</td>";
                                    echo "<td class='gold'>" . $theData[$x][2] . "</td>"; 
                                    echo "<td class='silver'>" . $theData[$x][3] . "</td>"; 
                                    echo "<td class='bronze'>" . $theData[$x][4] . "</td>";
                                    echo "<td class='total'>" . strip_tags($theData[$x][5]) . "</td>"; 
                                echo "</tr>";     
                            } 
                        ?>
                         
                    </tbody>
                </table>
            </div>
        </div>


        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

        <!-- Google Analytics -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
    </body>
</html>








