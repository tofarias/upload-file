```php
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Upload File</title>
    
  </head>

  <body>
    
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        
            <table align="center" border="0" width="50%">
                <tr>
                    <td><input type="file" name="<?=INPUT_FILE?>"></td>
                </tr>
                <tr>
                    <td><label>Renomear arquivo:</label></td>
                </tr>
                <tr>
                    <td><input autofocus type="text" name="renamefile" value=""></td>
                </tr>
                <tr>
                    <td align="left"><input type="submit" name="submit" value="Enviar"></td>
                </tr>
            </table>
        
        </form>

        <br><br>

        <?php
        
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                if( isset($_FILES) ){

                    $upload = new Upload($_FILES);
                    $upload->setAllowedExtension(['xls','xlsx']);
                    
                    if( $upload->moveUploadedFile("upload", $_POST['renamefile']) ){
                        echo $upload->getSuccessMessage();
                        echo $upload->getNameFileUploaded();
                    }else{
                        echo $upload->getErrorMessage();
                    }
                }
            }

        ?>

  </body>
</html>
