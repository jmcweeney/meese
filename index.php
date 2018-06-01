<?php
if (!empty($_REQUEST['Sender'])):
    $sender = $_REQUEST['Sender'];
    $layout = file_get_contents('./layout.html', FILE_USE_INCLUDE_PATH);

    foreach ($sender as $key => $value) {
        $key         = strtoupper($key);
        $start_if    = strpos($layout, '[[IF-' . $key . ']]');
        $end_if      = strpos($layout, '[[ENDIF-' . $key . ']]');
        $length      = strlen('[[ENDIF-' . $key . ']]');

        if (!empty($value)) {
            // Add the value at its proper location.
            $layout = str_replace('[[IF-' . $key . ']]', '', $layout);
            $layout = str_replace('[[ENDIF-' . $key . ']]', '', $layout);
            $layout = str_replace('[[' . $key . ']]', $value, $layout);
        } elseif (is_numeric($start_if)) {
            // Remove the placeholder and brackets if there is an if-statement but no value.
            $layout = str_replace(substr($layout, $start_if, $end_if - $start_if + $length), '', $layout);
        } else {
            // Remove the placeholder if there is no value.
            $layout = str_replace('[[' . $key . ']]', '', $layout);
        }
    }

    // Clean up any leftover placeholders. This is useful for booleans,
    // which are not submitted if left unchecked.
    $layout = preg_replace("/\[\[IF-(.*?)\]\]([\s\S]*?)\[\[ENDIF-(.*?)\]\]/u", "", $layout);

    if (!empty($_REQUEST['download'])) {
        header('Content-Description: File Transfer');
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename=meese-email-signature.html');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }

    echo $layout;
else: ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Meese custom email signature designer">
        <meta name="author" content="Additional">

        <title>Meese Signature Designer</title>

        <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/meese.css" rel="stylesheet">
    </head>

    <body>

    <div class="container-fluid d-flex h-100 mx-auto">
      <div class="row align-items-center">
        <div class="col-sm-8 offset-sm-2 col-lg-4 offset-lg-0 align-items-center">
          <div class="brand my-4 mt-sm-5 mx-md-5">
            <img src="img/meese-logo.svg"/>
            <h2 class="brand-title text-uppercase mt-2 mb-5">Signature Designer</h2>
          </div>
          <div class="my-5 mx-md-5">
            <p class="text-muted">To create a custom signature containing the new Meese logo and website, complete this form and copy/paste the results to Outlook (or download them for later). If you'd like some tips on setting up an HTML signature, <a href="https://support.office.com/en-us/article/Change-the-message-format-to-HTML-Rich-Text-Format-or-plain-text-338a389d-11da-47fe-b693-cf41f792fefa" target="_blank">visit Microsoft Office support</a>.</p>
          </div>
        </div>
        <div class="col-sm-8 offset-sm-2 col-lg-4 offset-lg-1 align-items-center">
            <div class="card">
              <div class="card-body">
                  <form role="form" method="post" target="preview" id="form">
                      <div class="form-group">
                          <label for="Name">First &amp; Last Name</label>
                          <input type="text" class="form-control" id="Name" name="Sender[NAME]" placeholder="Jane Smith">
                      </div>
                      <div class="form-group">
                          <label for="Title">Title</label>
                          <input type="text" class="form-control" id="Title" name="Sender[TITLE]" placeholder="Chief Executive Officer">
                      </div>
                      <div class="form-group">
                          <label for="Email">Email</label>
                          <input type="email" class="form-control" id="Email" name="Sender[EMAIL]" placeholder="jsmith@meese-inc.com">
                      </div>
                      <div class="form-group">
                          <label for="Phone">Office Phone</label>
                          <input type="phone" class="form-control" id="Phone" name="Sender[PHONE]" placeholder="Optional">
                      </div>
                      <div class="form-group">
                          <label for="Mobile">Mobile Phone</label>
                          <input type="mobile" class="form-control" id="Mobile" name="Sender[MOBILE]" placeholder="Optional">
                      </div>
                      <div class="mt-5">
                        <button id="preview" type="submit" class="btn btn-primary mr-3">Create</button>
                        <button id="download" class="btn btn-outline-secondary">Download</button>
                        <input type="hidden" name="download" id="will-download" value="">
                      </div>
                  </form>
                </div>
                <iframe src="about:blank" name="preview" width="100%" height="200">
                  <!-- Created signature appears here to be copied -->
                </iframe>
            </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    $( document ).ready(function() {
        $("#download").bind( "click", function() {
            $('#will-download').val('true');
            $('#form').removeAttr('target').submit();
        });

        $("#preview").bind( "click", function() {
            $('#will-download').val('');
            $('#form').attr('target','preview');
        });
    });
    </script>
    </body>
</html>
<?php endif; ?>
