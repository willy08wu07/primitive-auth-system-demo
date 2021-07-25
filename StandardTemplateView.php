<?php

include_once 'IView.php';

class StandardTemplateView implements IView
{
    private $view;

    public function __construct(IView $view)
    {
        $this->view = $view;
    }

    public function output(array $params = [])
    {
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8" />
    <title>㊣ 超原始認證系統</title>
    <style>
        html, body {
            background-color: #334;
            color: #ccd;
        }
        h1 {
            text-align: center;
        }
        .warning {
            color: #fc4;
            text-align: center;
        }
        .welcome {
            text-align: center;
        }
        .form {
            width: 200px;
            margin: 0 auto;
        }
        .form input {
            background-color: #334;
            color: #eef;
            box-sizing: border-box;
            width: 100%;
            margin: 8px 0;
            border: 2px #ccd solid;
            padding: 4px;
        }
        .form button {
            background-color: #fc4;
            color: #334;
            box-sizing: border-box;
            width: 100%;
            margin: 8px 0;
            border: 2px #fc4 outset;
            padding: 4px;
        }
        .form button:active {
            background-color: #c94;
            border: 2px #c94 inset;
        }
    </style>
</head>

<body>

<div>
    <h1>㊣ 超原始認證系統</h1>
</div>

<?php
        $this->view->output($params);
?>

</body>

</html>
<?php
    }
}
