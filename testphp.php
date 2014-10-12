<?php
echo "Hello";
    
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
$con = mysql_connect('localhost', 'root', 'root');
$counter =1;

    if(!$con)
    {
        die('could not connect:'.mysql_error());
    }
    mysql_select_db("DBBurger", $con)
        or die ("unable to connect".mysql_error());

foreach ($result['menu'] as $item)
{
    
    foreach ($item as $value)
    {
        $foodname = $value['name'];
        $foodPrice= $value['price'];
        $query = "INSERT INTO food (name, price, id) VALUES ($foodname, $foodPrice, $counter)";
        mysql_query($query, $con);
        $counter = $counter + 1;    
        echo $query;
    }
    
    
}


echo " Finished!! "
?>