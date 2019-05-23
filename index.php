<html>
 <head>
 <Title>Submission 2 </Title>
 <style type="text/css">
 	body { background-color: #fff; 
 	    color: #333; font-size: .85em; margin: 20; padding: 20;
 	    font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
 	}
 	h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
 	h1 { font-size: 2em; }
 	h2 { font-size: 1.75em; }
 	h3 { font-size: 1.2em; }
 	table { margin-top: 0.75em; }
 	th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
 	td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
    .thumb {
        height: 150px;
        border: 1px solid #000;
        margin: 10px 5px 0 0;
    }
 </style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

 </head>


<?php
/**----------------------------------------------------------------------------------
* Microsoft Developer & Platform Evangelism
*
* Copyright (c) Microsoft Corporation. All rights reserved.
*
* THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, 
* EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES 
* OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR PURPOSE.
*----------------------------------------------------------------------------------
* The example companies, organizations, products, domain names,
* e-mail addresses, logos, people, places, and events depicted
* herein are fictitious.  No association with any real company,
* organization, product, domain name, email address, logo, person,
* places, or events is intended or should be inferred.
*----------------------------------------------------------------------------------
**/

/** -------------------------------------------------------------
# Azure Storage Blob Sample - Demonstrate how to use the Blob Storage service. 
# Blob storage stores unstructured data such as text, binary data, documents or media files. 
# Blobs can be accessed from anywhere in the world via HTTP or HTTPS. 
#
# Documentation References: 
#  - Associated Article - https://docs.microsoft.com/en-us/azure/storage/blobs/storage-quickstart-blobs-php 
#  - What is a Storage Account - http://azure.microsoft.com/en-us/documentation/articles/storage-whatis-account/ 
#  - Getting Started with Blobs - https://azure.microsoft.com/en-us/documentation/articles/storage-php-how-to-use-blobs/
#  - Blob Service Concepts - http://msdn.microsoft.com/en-us/library/dd179376.aspx 
#  - Blob Service REST API - http://msdn.microsoft.com/en-us/library/dd135733.aspx 
#  - Blob Service PHP API - https://github.com/Azure/azure-storage-php
#  - Storage Emulator - http://azure.microsoft.com/en-us/documentation/articles/storage-use-emulator/ 
#
**/

require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

const ACCOUNT_NAME = "dravertwebapp";
const ACCOUNT_KEY = "JMQDg8da/HEV9md4HqAACLYPX3sCPMZPB3vsGgpno1M64Vt4l/mMq1Se0hkeIBcR+CHBPFBzYDawBqZha0VDtw==";

$connectionString = "DefaultEndpointsProtocol=https;AccountName=".ACCOUNT_NAME.";AccountKey=".ACCOUNT_KEY;

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

$fileToUpload = "";
$containerName = "";
$fileName = "";

if(isset($_POST["submit"])){
    
    if (!isset($_GET["Cleanup"]) && isset($_FILES["fileToUpload"])) {
        $fileToUpload = $_FILES["fileToUpload"]["tmp_name"];
        $fileName = $_FILES["fileToUpload"]["name"];
        // Create container options object.
        $createContainerOptions = new CreateContainerOptions();

        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
    
        // Set container metadata.
        $createContainerOptions->addMetaData("key1", "value1");
        $createContainerOptions->addMetaData("key2", "value2");
    
          $containerName = "blockblobs".generateRandomString();
    
        try {
            // Create container.
            $blobClient->createContainer($containerName, $createContainerOptions);
    
            // Getting local file so that we can upload it to Azure
            $myfile = fopen($fileToUpload, "r") or die("Unable to open file!");
            fclose($myfile);
            
            // # Upload file as a block blob
            // echo "Uploading BlockBlob: ".PHP_EOL;
            // echo $fileToUpload;
            // echo "<br />";
            
            $content = fopen($fileToUpload, "r");
    
            //Upload blob
            $blobClient->createBlockBlob($containerName, $fileName, $content);

            echo "
            <script>
                alert('File uploaded sucessfully');
                
                $(document).ready(function(){
                    displayImage('$containerName', '$fileName');
                    processImage('$containerName', '$fileName');
                });
                
            </script>";
    
        }
        catch(ServiceException $e){
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179439.aspx
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
        catch(InvalidArgumentTypeException $e){
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179439.aspx
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
    } 
    
}
?>

 <body>
     <h1>Analisa Gambar dan Blob Storage</h1>
     <p>Pilih Gambar yang akan dianalisa.</p>
     <form method="post" action="phpQS.php?upload" enctype="multipart/form-data">
        <p><input type="file" name="fileToUpload" id="fileToUpload"></p>
        <p><button type="submit" name="submit">Upload</button></p>
     </form>
    
     <br><br>
    <div id="wrapper" style="width:1020px; display:table;">
        <div id="jsonOutput" style="width:600px; display:table-cell;">
            Response:
            <br><br>
            <textarea id="responseTextArea" class="UIInput"
                    style="width:580px; height:400px;"></textarea>
        </div>
        <div id="imageoutput" style="width:420px; display:table-cell;">
            Source image:
            <br><br>
            <div id="list"></div>
            <?php
                if(isset($_POST['submit'])){
                    ?>
                    <div id="caption">
                        Description : <br>
                        <img id="picture" src='' class="thumb">
                        <div id="description"></div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>
 </body>
 <script type="text/javascript">
    function processImage(container, fileName) {
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************
 
        // Replace <Subscription Key> with your valid subscription key.
        var subscriptionKey = "2474f1ef95bd4578ad602107d5e6ac66";
 
        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
 
        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };

        var sourceImageUrl = "https://dravertwebapp.blob.core.windows.net/" + container + "/" + fileName;
 
        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),
 
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
 
            type: "POST",
 
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })
 
        .done(function(data) {
            // Show formatted JSON on webpage.
            var result = JSON.stringify(data, null, 2);
            $("#responseTextArea").val(result);

            var description = data.description.captions[0].text;
            $("#description").html(description);
        })
 
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
    };

    function displayImage(container, fileName){
        var image = document.getElementById('picture');
        image.src = "https://dravertwebapp.blob.core.windows.net/" + container + "/" + fileName;
    }

</script>

 </html>

