# Shop



Shop is a small PHP CLI application made with Laravel Zero.


### Installation


```sh
1. navigate to Shop/builds folder
2. execute the following command: php Shop
```




### Availible commands

This is a list of custom commands. 
To see every command availible in the application use command: php Shop -h. 
To see the details of each availible command use: php Shop <command name> -h.

| Command | Description |
| ------ | ------ |
| php Shop ADD <sku> <product name> <quantity> <product price> | Adding products to database : when you start the app you can add the products to the database  |
| php Shop END |  Use this command to move to the shopping cart stage of the app |
| php Shop ADD <sku> <quantity> | Adding products to shopping cart (this also checks if the products exist and if you are trying to buy more than available in the products table, be sure to add products first )  |
| php Shop REMOVE <sku> <quantity> | Removing the product from the shopping cart (you cannot remove the products that are not in the shopping cart) |
| php Shop CHECKOUT | To checkout use the following command (this will also reduce the quantity of products in the products table) |
| php Shop END | To go back to the stage 1 (entering products) use this command again |