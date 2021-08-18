<!DOCTYPE html>
<html lang="en-gb">
  <head>
    <title>
      A diff implementation for PHP
    </title>
    <style type="text/css">
        body{
            font-family:Consolas,'Courier New',Courier,monospace;
        font-size:0.75em;
        line-height:1.333;
        }
      .diff td{
        padding:0 0.667em;
        vertical-align:top;
      }

      del,
      ins,
      span{
        display:block;
        min-height:1.333em;
        margin-top:-1px;
        padding:0 3px;
        white-space:pre;
        white-space:pre-wrap;
        text-decoration:none;
      }

      * html .diff span{
        height:1.333em;
      }

      .diff span:first-child{
        margin-top:0;
      }
        del,
      .diffDeleted span{
        border:1px solid #c06;
        background:rgb(255,224,224);
      }
        ins,
      .diffInserted span{
        border:1px solid #0c6;
        background:rgb(224,255,224);
      }

      #toStringOutput{
        margin:0 2em 2em;
      }

    </style>
  </head>
  <body>
<?php 
// include the Diff class
require_once 'class.Diff.php';

$diff = Diff::compareFiles('TutorFrontForm_Resource/edu-subuser.hanpda.com/front/tutor/create.html','index.html');
echo Diff::toTable($diff,'','');
?>
  </body>
</html>
