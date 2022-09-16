# PHP - AJAX Livesearch
Php ve Ajax ile Mysql veri tabanında livesearch işlemi.  
  
Ajax, get ve post işlemlerini sayfayı yenilemeden yapabilmenize olanak sağlar. Livesearch olayını da bu sayede mümkün kılar. Arama kutusuna girdiğiniz her karakterde veritabanından fetch edilen verileri value değerine göre filtreler.
İnternette uzun süre araştırdım ve yaptığım örneğin çoğundan daha basit ve çok daha anlaşılır olduğunu düşünüyorum.  
  
### Bu şekilde bir tablom var.
Verileri, item_name yani ürünün adına göre filtrelemek istediğimizi varsayalım.
![table](https://user-images.githubusercontent.com/106887102/189295227-e5ac4f3b-a2da-4a5e-85bb-7f1cddcb001b.png)

   
   
### Bu işlem için;
- İlk olarak veritabanı bağlantısı yapıyoruz.
```php
# db.php  

$host = "localhost";
$dbname = "apos";
$user = "root";
$pass = "";


try{
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$user,$pass);
}catch(PDOException $e){
    echo '<strong>Veritabanı Bağlantısı Başarısız Oldu</strong> : ' . $e->getMessage();
    exit;
}
```  

- Ardından ilgili tablomuzdaki ilgili sütunun, inputa girdiğimiz value değerine göre filtreliyoruz.  
Burada term, inputa girdiğimiz değeri ifade ediyor. Ardından while ile gelen sonuçları listeliyoruz.
```php
# fetch_items.php  

require_once 'db.php';
try{
    if(isset($_REQUEST["term"])){
        // create prepared statement
        $sql = "SELECT * FROM items WHERE item_name LIKE :term";
        $stmt = $db->prepare($sql);
        $term = $_REQUEST["term"] . '%';
        // bind parameters to statement
        $stmt->bindParam(":term", $term);
        // execute the prepared statement
        $stmt->execute();
        if($stmt->rowCount() > 0){
            while($row = $stmt->fetch()){
                echo '<div class="element" style="margin-top:2px;">' . $row["item_name"] . '</div>';
            }
        } else{
            echo '<div class="element">Eşleşme bulunamadı</div>';
        }
    }  
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

// Close statement
unset($stmt);
 
// Close connection
unset($db);
```  

- Son adım olarak;
  - Arama işlemi için kullanacağımız dosyaya [JQuery Kütüphanesini](https://code.jquery.com/jquery-3.5.1.min.js) dahil ediyoruz.
  ```html
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  ```
  - input ve sonuçların gösterileceği div'i ekliyoruz.
  ```html
  <div class="search-box">
    <input type="text" id="" name="livesearch" placeholder="Ürün Ara.." ><br>
    <div class="result"></div>
  </div>
  ```
  - Javascript kodlarımızı yazmaya başlayabiliriz. (jquery cdn'in altına olacak şekilde yazılmalıdır)
  ```javascript
  $(document).ready(function(){
      $('.search-box input[type="text"]').on("keyup input", function(){
          /* inputa her karakter girildiğinde verileri çek */
          var inputVal = $(this).val();
          var resultDropdown = $(this).siblings(".result");
          if(inputVal.length){
              $.get("fetch_items.php", {term: inputVal}).done(function(data){
                  // Dönen verileri görüntüle
                  resultDropdown.html(data);
              });
          } else{
              resultDropdown.empty();
          }
      });

      // Çıkan sonuca tıklandığında value değerini alsın
      $(document).on("click", ".result .element", function(){
          $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
          $(this).parent(".result").empty();
      });
  });
  ```
  - İsterseniz stil ekleyebilirsiniz.
  ```css
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
  ```
 ## Geçmiş olsun 👻

![Sonuç](https://user-images.githubusercontent.com/106887102/189302420-1737fb2b-e39b-4e46-ac05-c3e46f7e7a67.gif)


