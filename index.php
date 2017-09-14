<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Twitter App</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/album.css" rel="stylesheet">
  </head>

  <body>

    <div class="collapse bg-dark" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 py-4">
            <h4 class="text-white">About</h4>
            <p class="text-muted">Uplift Nigeria is an NGO that help in empowering youth to get an idea on what they want to be in the computer field.</p>
          </div>
          <div class="col-sm-4 py-4">
            <h4 class="text-white">Contact</h4>
            <ul class="list-unstyled">
              <li><a href="#" class="text-white">Follow on Twitter</a></li>
              <li><a href="#" class="text-white">Like on Facebook</a></li>
              <li><a href="#" class="text-white">Email</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar navbar-dark bg-dark">
      <div class="container d-flex justify-content-between">
        <a href="#" class="navbar-brand">Twitte</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>

    <section class="jumbotron text-center">
      <div class="container">
        <h1 class="jumbotron-heading">Get your twitter counts here!</h1> <br>
        
         <input id="submit" name="submit" type="submit" value="Go" />
            <span>or press enter</span>
      </div>
    </section>

    <div class="album text-muted">
      <div class="container">

                 <form action="index.php" method="get" onsubmit="submitTermsw(); return false;">
            <label for="query">Search for: </label>
            <input id="query" name="query" type="text" />
            <br />
           <!-- <label for="startPage">Start on what page (must be greater than 1) </label> -->
           <!-- <input id="startPage" name="startPage" type="text" value="1 " /> -->
            <br />
           <!-- ( <label for="pageTotal">How many pages (0 to search until no more results) </label>) -->
            <!--<input id="pageTotal" name="pageTotal" type="text" value="0" />-->
            <!-- <input id="submit" name="submit" type="submit" value="Go" />
            <span>or press enter</span>-->
          </form>

         <?php
function queryTwitter($search)
{
  $url = "https://api.twitter.com/1.1/search/tweets.json";
  if($search != "")
      $search = "#".$search;
  $query = array( 'count' => 100, 'q' => urlencode($search), "result_type" => "recent");
  $oauth_access_token = "433308178-ysPMQKr8sTbsOMbDnmjANWBZtqHsngeHWDUBcORn";
  $oauth_access_token_secret = "4u5W2oCgUfOHRvRM5z7mKYz3zZDzoYgdWyAjjoEnlVmMr";
  $consumer_key = "nCckMZFHK2vbwCyHXbWlYePiK";
  $consumer_secret = "y0qfEZVIl795YkNYPrDC1Q3rG3PeKtF6dMTv1lTbk7oH27iRRz";

  $oauth = array(
                  'oauth_consumer_key' => $consumer_key,
                  'oauth_nonce' => time(),
                  'oauth_signature_method' => 'HMAC-SHA1',
                  'oauth_token' => $oauth_access_token,
                  'oauth_timestamp' => time(),
                  'oauth_version' => '1.0');

  $base_params = empty($query) ? $oauth : array_merge($query,$oauth);
  $base_info = buildBaseString($url, 'GET', $base_params);
  $url = empty($query) ? $url : $url . "?" . http_build_query($query);

  $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
  $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
  $oauth['oauth_signature'] = $oauth_signature;

  $header = array(buildAuthorizationHeader($oauth), 'Expect:');
  $options = array( CURLOPT_HTTPHEADER => $header,
                    CURLOPT_HEADER => false,
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false);

  $feed = curl_init();
  curl_setopt_array($feed, $options);
  $json = curl_exec($feed);
  curl_close($feed);
  return  json_decode($json);
}

function buildBaseString($baseURI, $method, $params)
{
  $r = array();
  ksort($params);
  foreach($params as $key=>$value){
      $r[] = "$key=" . rawurlencode($value);
  }
  return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth)
{
  $r = 'Authorization: OAuth ';
  $values = array();
  foreach($oauth as $key=>$value)
      $values[] = "$key=\"" . rawurlencode($value) . "\"";
  $r .= implode(', ', $values);
  return $r;
}


// $search_text = "aliko";

// $var = queryTwitter("$search_text");

// var_dump($var);

 ?>

        <?php 
        if(isset($_GET['query'])){
          
          $search_string = $_GET['query'];

          $count = 0;

          $var = queryTwitter($search_string);

          foreach ($var as $key => $value) {
            $getSome = $var->statuses;

            foreach ($getSome as $key => $val__) {
              $count = $count+1;
              
         ?>
        

       
        <div class="row">
          <div class="alert alert-info">
            <?php echo "<img class='img-circle' src='".$val__->user->profile_image_url."'/> User-FullName: ". $val__->user->name. " User-S-Name: ". $val__->user->screen_name ."<br/>"; ?>
            <?php echo "$val__->text" ;  ?>
          </div>

        </div>

        <?php 
          }
          } 

            echo "<h2>$search_string posted $count times</h2>";

          } ?>

      </div>
    </div>

    <footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#">Back to top</a>
        </p>
        <p>Twitter example is &copy; Bootstrap</p>
        <p>New to Bootstrap? <a href="../../">Visit the homepage</a> or read our <a href="../../getting-started/">getting started guide</a>.</p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/holder.min.js"></script>
    <script>
      $(function () {
        Holder.addTheme("thumb", { background: "#55595c", foreground: "#eceeef", text: "Thumbnail" });
      });
    </script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
