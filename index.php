<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Ajax Shopping Cart</title>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
    integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" 
    integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
</head>
<body>
    <h1>PHP Ajax Shopping Cart</h1>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><img src="images/brand.jpg" class="nav_image"></a>
                <!-- <form class="form-inline my-2 my-lg-0" method="POST" action="search.php">
                    <input type="text" placeholder="Search" class="form-control" name="search">
                </form> -->
                <form class="form-inline my-2 my-lg-0">
                    <input type="text" placeholder="Search" class="form-control" name="search_text" id="search_text">
                </form>
                <div id="navbar-cart" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li>
                            <a class="btn btn-outline-success" id="cart-popover" data-placement="bottom" title="Shopping Cart">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge"></span>
                                <span class="total_price">$ 0.00</span>
                            </a>
                        </li>
                    </ul>                
                </div>

            </div>
        </nav>
        <div id="popover_content_wrapper">
            <span id="cart_details"></span>
            <div>
                <a href="#" class="btn btn-outline-primary" id="check_out_cart">
                    <i class="fas fa-shopping-cart"></i> Check out
                </a>
                <a href="#" class="btn btn-outline-dark" id="clear_cart">
                    <i class="fas fa-trash"></i> Clear
                </a>
            </div>
        </div>
        <div id="display_item">
            
        </div>
    </div>
    <script>
        $(document).ready(function() {
            load_product();
            load_cart_data();
            
            function load_product() {
                $.ajax({
                    url: "fetch_item.php",
                    method: "POST",
                    success: function(data) {
                        $('#display_item').html(data);
                    }
                })
            };

            $('#search_text').keyup(function() {
                var txt =  $(this).val();
                if(txt != '') {
                    $.ajax({
                        url: "search.php",
                        type: 'GET',
                        data: {search:txt},
                        success: function(data) {
                            $('#display_item').html(data);
                        }
                    });
                }
            });

            function load_cart_data() {
                $.ajax({
                    url: "fetch_cart.php",
                    method: "POST",
                    dataType: "json",
                    success: function(data) {
                        $('#cart_details').html(data.cart_details);
				        $('.total_price').text(data.total_price);
				        $('.badge').text(data.total_item);
                    }
                });
            }

            $('#cart-popover').popover({
                html : true,
                sanitize: false,
                container: 'body',
                content: function() {
                    return $('#popover_content_wrapper').html();
                }
            });
            
            $(document).on('click', '.add_to_cart', function() {
                var action = "add";
                var product_id = $(this).attr("id");
                var product_name = $('#name' + product_id + '').val();
                var product_price = $('#price' + product_id + '').val();
                var product_quantity = $('#quantity' + product_id).val();
                if(product_quantity > 0) {
                    $.ajax({
                        url: "action.php",
                        method: "POST",
                        data: {product_id:product_id, product_name:product_name, product_price:product_price, product_quantity:product_quantity, action:action},
                        success: function(data)
                        {
                            load_cart_data();
                            alert("Item has been added into Cart");
                        }
                    });
                }
                else
                {
                    alert("Please enter number of quantity");
                }
            });

            $(document).on('click', '.delete', function(){
                var product_id = $(this).attr("id");
                var action = 'remove';
                if(confirm("Are you sure you want to remove this product?")) {
                    $.ajax({
                        url: "action.php",
                        method: "POST",
                        data: {product_id:product_id, action:action},
                        success: function()
                        {
                            load_cart_data();
                            $('#cart-popover').popover('hide');
                            alert("Item has been removed from Cart");
                        }
                    })
                }
                else
                {
                    return false;
                }
            });

            $(document).on('click', '#clear_cart', function(){
                var action = 'empty';
                $.ajax({
                    url: "action.php",
                    method: "POST",
                    data: {action:action},
                    success: function()
                    {
                        load_cart_data();
                        $('#cart-popover').popover('hide');
                        alert("Your Cart has been clear");
                    }
                });
	        });
        });
    </script>
</body>
</html>