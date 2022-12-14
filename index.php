<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP AJAX Livesearch</title>
</head>
<body>
    <style>
        body{
            background-color: #191c24;
            font-family: sans-serif;
        }
        input,
        .element{
            padding: 0.625rem 0.6875rem;
            background-color: #2A3038;
            border: none;
            border-radius: 15px;
            outline: none;
            color: whitesmoke;
        }
        .search-box{
            width: 99vw;
            height: 30vw;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .result{
            position: absolute;
            top: 33.5vh;
            left: 44vw;
        }
    </style>
    <div class="search-box">
    <input type="text" id="" name="livesearch" placeholder="Ürün Ara.." ><br>
    <div class="result"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.search-box input[type="text"]').on("keyup input", function(){
                /* Get input value on change */
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                if(inputVal.length){
                    $.get("fetch_items.php", {term: inputVal}).done(function(data){
                        // Display the returned data in browser
                        resultDropdown.html(data);
                    });
                } else{
                    resultDropdown.empty();
                }
            });
            
            // Set search input value on click of result item
            $(document).on("click", ".result .element", function(){
                $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                $(this).parent(".result").empty();
            });
        });
    </script>
</body>
</html>
