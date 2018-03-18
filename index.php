
<?php
session_start();
// Connecting, selecting database
$dbconn = pg_connect("host=localhost  dbname=experiment user=postgres")
or die('Could not connect: ' . pg_last_error());

if (isset($_POST['feed']) && isset($_POST['user_id']) && isset($_POST['time']) && isset($_POST['item_id']))
{
    $category_id = $_SESSION['category_index'];
    $offset = $_SESSION['offset'];
    $query = "INSERT INTO feed (feedback,time,user_id, item_id, category_id, \"offset\") VALUES 
    ('$_POST[feed]','$_POST[time]','$_POST[user_id]', '$_POST[item_id]', '$category_id', '$offset')";
    $result = pg_query($query);
}
if (isset($_GET['q']))
    $param =  $_GET['q'];
else
    $param =  0;

if ($param > 8) {
    ?> <p> Experiment skončil. Ďakujem za tvoj čas!</p>
    <?php
    exit();
}
if ($param == 0) {
    $categories = array(139 => array(0, 10, 20), 1 => array(0, 10, 20), 36 => array(0, 10, 20));
    $categories_numbers = array(139, 1, 36);
}
else {
    $categories = $_SESSION['cat'];
    $categories_numbers = $_SESSION['cat_num'];
}

if (isset($_GET['id'])) :

    $query = 'SELECT * FROM products WHERE id =' . $_GET['id'];
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());

    $line = pg_fetch_array($result, null, PGSQL_ASSOC);

    $icon_class = json_decode($line['tags'],TRUE);
    ?>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><?php echo $line['title_deal'] ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="img-detail"  style=" background-image:url('https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $line['image_hd'] ?>');">
        </div>
        <div class="cena-kupit">
            <div class="cena-modal">5.49 €</div>
            <div class="kupit">Kúpiť</div>
        </div>
        <div class="modal-body">
            <p><?php echo $line['title_desc'] ?></p>
            <ul>
                <?php
                foreach ($icon_class as $key => $icon) {
                    echo "<li><span class='" . $icon['class'] . "'></span>" . $icon['text'] ."</li>\n";
                }
                ?>
            </ul>
        </div>
        <div class="modal-footer">
        </div>
    </div>
<?php else :



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
$_SESSION['category_index'] = $categories_index;
$_SESSION['offset'] = $index;



// Performing SQL query
$query = 'SELECT * FROM products WHERE category_id='. $categories_index .'  OFFSET ' . $index . ' LIMIT 10';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Printing results in HTML
$captions = array();
$subscriptions = array();
$images = array();
$all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

    array_push($captions, $line['title_short']);
    array_push($subscriptions, $line['title_short']);
    array_push($images, $line['image_hd']);
    array_push($all, $line);
}

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
    <link rel="stylesheet" type="text/css" href="ikony.css">
    <title>Hello, world!</title>
</head>
<body style="height:100%">
<a href="#" class="display"><svg height="100" width="100" style="position:fixed; top:50%; left:50%; transform: translate(-50%, -50%);">
    <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red">
    </circle>
</svg></a>

<a href="#" class="display"><svg height="100" width="100" style="position:fixed; top:50%; left:50%; transform: translate(-50%, -50%);">
        <circle cx="50" cy="50" r="5" stroke="black" stroke-width="3" fill="red">
        </circle>
    </svg></a>
<a href="#" class="dalej" ><span>Preskočiť</span></a>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="myModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Title</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" tabindex="-1" id="dotaz" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ďakujeme za Váš výber!</h5>
            </div>
            <div class="modal-body">
                <p style="padding-bottom:20px; font-size: 28px;">Čo Vás na tejto ponuke zaujalo najviac?</p>
                <form>
                    <div class="form-group">
                        <textarea name="feed" class="form-control" id="feedback" rows="3"></textarea>
                    </div>
                    <button type="button" style="margin:15px;" class="btn btn-secondary odoslat">Odoslať odpoveď</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="category" id="<?php echo $categories_index; ?>"></div>
<div class="offset" id="<?php echo $index; ?>"></div>
<div class="container" style="height:100%;     padding-top: 2.5%;
    padding-bottom: 2.5%;">
    <div class="row" style="height:calc( 100% / 3);">
        <div class="col" id="<?php echo $all[0]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[0] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[0] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[0]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
        <div class="col" id="<?php echo $all[1]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[1] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[1] ?>" width="100%" class="img">
        </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[1]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
        <div class="col" id="<?php echo $all[2]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[2] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[2] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[2]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
        <div class="col" id="<?php echo $all[3]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[3] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[3] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[3]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
    </div>
    <div class="row" style="height:calc( 100% / 3)";>
        <div class="col" id="<?php echo $all[4]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[4] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[4] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[4]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
        <div class="col hide"></div>
        <div class="col hide"></div>
        <div class="col" id="<?php echo $all[5]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[5] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[5] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[5]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
    </div>
    <div class="row" style="height:calc( 100% / 3)";>
        <div class="col" id="<?php echo $all[6]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[6] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[6] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[6]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
        <div class="col" id="<?php echo $all[7]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[7] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[7] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[7]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
        <div class="col" id="<?php echo $all[8]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[8] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[8] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[8]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
        <div class="col" id="<?php echo $all[9]['id']; ?>">
            <div class="nazov"><p class="nazov-produktu"><?php echo $captions[9] ?></p></div>
            <div class="img">
                <span class="helper"></span><img src="https://creati.cdn.platon.sk/zlavadna.sk/<?php echo $images[9] ?>" width="100%" class="img">
            </div>
            <div class="popis"><p class="popis-produktu"><?php echo mb_substr($all[9]['title_desc'],0,80, "utf-8") . "..."  ?></p></div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="./index.js"></script>
</body>
</html>
<?php endif; ?>


