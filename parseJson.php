<?php
echo "Hello";
$con = new mysqli("localhost", "root", "root", "DBBurger");
$counter = 0;

    if(!$con)
    {
        echo "Error";
        die('could not connect:'.mysql_error());
    }

$jsonObject = '{
    "menu": {
        "meats": [
            {
                "name": "1/3 lb. Beef",
                "price": 2
            },
            {
                "name": "1/2 lb. Beef",
                "price": 2.25
            },
            {
                "name": "2/3 lb. Beef",
                "price": 2.5
            },
            {
                "name": "Turkey",
                "price": 2
            },
			{
                "name": "Veggie",
                "price": 2
            }
        ],
        "buns": [
            {
                "name": "White",
                "price": 0.50
            },
            {
                "name": "Wheat",
                "price": 0.50
            },
            {
                "name": "Texas Toast",
                "price": 0.75
            }
        ],
        "cheeses": [
            {
                "name": "Cheddar",
                "price": 0.35
            },
            {
                "name": "American",
                "price": 0.35
            },
            {
                "name": "Swiss",
                "price": 0.35
            }
        ],
		"toppings": [
			{
                "name": "Tomatoes",
                "price": 0
            },
			{
                "name": "Lettuce",
                "price": 0
            },
            {
                "name": "Onions",
                "price": 0
            },
			{
                "name": "Pickles",
                "price": 0
            },
			{
				"name": "Bacon",
				"price": 1
			},
			{
                "name": "Red onion",
                "price": 0
            },
            {
                "name": "Mushrooms",
                "price": 0
            },
			{
                "name": "Jalapenos",
                "price": 0
            }
        ],
        "sauces": [
            {
                "name": "Ketchup",
                "price": 0
            },
            {
                "name": "Mustard",
                "price": 0
            },
            {
                "name": "Mayonnaise",
                "price": 0
            },
            {
                "name": "BBQ",
                "price": 0
            }
        ],
        "sides": [
            {
                "name": "French fries",
                "price": 1
            },
            {
                "name": "Tater tots",
                "price": 1
            },
            {
                "name": "Onion rings",
                "price": 1
            }
        ]
    }
}';

$result = json_decode($jsonObject, true);

// copy menu items each into a separate corresponding array
    $fillMenu = $result['menu'];
    $meats = $fillMenu['meats'];
    $buns = $fillMenu['buns'];
    $cheeses = $fillMenu['cheeses'];
    $toppings = $fillMenu['toppings'];
    $sauces = $fillMenu['sauces'];
    $sides = $fillMenu['sides'];


    // write overall menu with new arrays
    $sections = [$meats, $buns, $cheeses, $toppings, $sauces, $sides];
    $sectionNames = ['meats', 'buns', 'cheeses', 'toppings', 'sauces', 'sides'];
    $i = 0;


    //prepare statement 
    $sql = $con->prepare("INSERT INTO Food(name, price, id, type) values (?, ?,?,?)");

    // loop thorugh each sub array of sections to populate table 
   
foreach ($sections as $part) 
    {
        $type = $sectionNames[$i];
       
            foreach ($part as $map) 
            {
                $name = $map['name'];
                //echo $name;
                $price = $map['price'];
                //echo "counter ".$counter."foodname".$name."price ".$price." type: ".$type;   

                // call to insert into mySQL database
                $sql->bind_param('sdis', $name, $price, $counter, $type);
                $sql->execute();
                $counter = $counter +1;
                
                //printf("%d rows ", $sql->affected_rows);
                
            }
        $i++;
        
    }


 /* $sql = "select name from Food"; //get the current burgerID
    $result= $con->query($sql);
   	while($r = mysqli_fetch_array($result)) 
   	{ 
        $rows[]=$r;
    }

print_r($rows);
echo " Finished!! " */
?>