
<?php

session_start();
// Connecting, selecting database
$dbconn = pg_connect("host=localhost  dbname=experiment user=postgres")
or die('Could not connect: ' . pg_last_error());
if (isset($_GET['q']))
    $param =  $_GET['q'];
else
    $param =  0;

if ($param == 0) {
    $categories = array(139 => array(0, 10, 20), 1 => array(0, 10, 20), 36 => array(0, 10, 20));
    $categories_numbers = array(139, 1, 36);
}
else {
    $categories = $_SESSION['cat'];
    $categories_numbers = $_SESSION['cat_num'];
}


//print_r($categories);

$categories_count =  count($categories);
$random = random_int(1, $categories_count) - 1;
$categories_index = $categories_numbers[$random];
//if (in_array($categories_index, $categories))
$random = random_int(1, count($categories[$categories_index])) - 1;
$index = $categories[$categories_index][$random];

unset($categories[$categories_index][$random]);
$categories[$categories_index] = array_values($categories[$categories_index]);
if (count($categories[$categories_index]) == 0) {
    unset($categories[$categories_index]);
    $key = array_search($categories_index, $categories_numbers);
    unset($categories_numbers[$key]);
    $categories_numbers = array_values($categories_numbers);
}

$_SESSION['cat'] = $categories;
$_SESSION['cat_num'] = $categories_numbers;
//
   /* echo $categories_index;
    echo " ";
    echo $index;
    echo "/n";*/



// Performing SQL query
$query = 'SELECT * FROM products WHERE category_id='. $categories_index .'  OFFSET ' . $index . ' LIMIT 10';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Printing results in HTML
//echo "<table>\n";
$captions = array();
$subscriptions = array();
$images = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    //echo "\t<tr>\n";
    //foreach ($line as $col_value) {
        //echo "\t\t<td>" . $line['caption'] . "</td>\n";
    //}
   // echo "\t</tr>\n";
    array_push($captions, $line['title_short']);
    array_push($subscriptions, $line['title_short']);
    array_push($images, $line['image']);
}
//echo "</table>\n";



// Free resultset
//pg_free_result($result);

// Closing connection
//pg_close($dbconn);
?>


<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="index.css">
    <title>Hello, world!</title>
</head>
<body style="height:100%">
<svg class="next" height="100" width="100" style="position:fixed; top:50%; left:50%; transform: translate(-50%, -50%);">
    <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red"></circle>
</svg>

<div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="container" style="height:100%;     padding-top: 2.5%;
    padding-bottom: 2.5%;">
    <div class="row" style="height:calc( 100% / 3);">
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[0] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[0] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[1] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[1] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu">V ruke orosený pohár plný kvasnicového piva a pred vami množstvo uvoľňujúcich procedúr... </p></div>
        </div>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[2] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[2] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[3] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[3] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
    </div>
    <div class="row" style="height:calc( 100% / 3)";>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[4] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[4] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
        <div class="col hide"></div>
        <div class="col hide"></div>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[5] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[5] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
    </div>
    <div class="row" style="height:calc( 100% / 3)";>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[6] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[6] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[7] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[7] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[8] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[8] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
        <div class="col">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[9] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[9] ?>" width="100%" class="img">
            </div>
            <div class="cena"><span class="cena-produktu">Cena: 50.49 eur</span></div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="./index.js"></script>
</body>
</html>



